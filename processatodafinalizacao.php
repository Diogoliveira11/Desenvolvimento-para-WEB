<?php

session_start();
require 'dbconnection.php';

header('Content-Type: application/json');

if (!isset($link) || $link === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro Fatal de Conexão: A variável $link não está definida após o require.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
    exit;
}

$is_connected = false;

try {
    // 1. RECOLHA E SANITIZAÇÃO DE DADOS 
    $is_connected = true;
    date_default_timezone_set('Europe/Lisbon');

    $id_alojamento_raw = $_POST['id_alojamento'] ?? null;
    $id_utilizador_raw = $_SESSION['id_user'] ?? $_POST['id_utilizador'] ?? null; 
    $data_check_in_raw  = (string)($_POST['data_check_in'] ?? null); 
    $data_check_out_raw = (string)($_POST['data_check_out'] ?? null);
    $num_quartos_reservados = (int)($_POST['num_quartos'] ?? 1); 
    $preco_total_float = (float)($_POST['preco_total'] ?? 0.00); 

    if (empty($data_check_out_raw) && !empty($data_check_in_raw)) {
        try {
            $dt_in = new DateTime($data_check_in_raw);
            $dt_in->modify('+1 day');
            $data_check_out_raw = $dt_in->format('Y-m-d');
        } catch (Exception $e) {
            $data_check_out_raw = null; 
        }
    }

    $id_alojamento = mysqli_real_escape_string($link, (string)$id_alojamento_raw);
    $id_utilizador = mysqli_real_escape_string($link, (string)$id_utilizador_raw); 
    $data_check_in  = mysqli_real_escape_string($link, (string)$data_check_in_raw); 
    $data_check_out = mysqli_real_escape_string($link, (string)$data_check_out_raw);

    $nome_cliente    = mysqli_real_escape_string($link, $_POST['nome'] ?? '');
    $apelido_cliente = mysqli_real_escape_string($link, $_POST['apelido'] ?? '');
    $morada_reserva  = mysqli_real_escape_string($link, $_POST['morada'] ?? '');
    $cidade_reserva  = mysqli_real_escape_string($link, $_POST['cidade'] ?? '');
    $pais_reserva    = mysqli_real_escape_string($link, $_POST['pais'] ?? '');
    $codigo_postal   = mysqli_real_escape_string($link, (string)($_POST['codigo_postal'] ?? '')); 

    // Dados gerados
    $entidade = mysqli_real_escape_string($link, str_pad($id_alojamento, 5, '0', STR_PAD_LEFT)); 
    $referencia = mysqli_real_escape_string($link, (string)rand(100000000, 999999999)); 
    $dt_reserva = new DateTime(); 
    $data_reserva_mysql = mysqli_real_escape_string($link, $dt_reserva->format('Y-m-d H:i:s')); 
    
    // 3. VALIDAÇÃO RIGOROSA: data_check_out não pode ser vazio!
    if (empty($data_check_in) || empty($data_check_out) || empty($nome_cliente) || $preco_total_float <= 0) {
        throw new Exception("Dados de data (Check-in/Check-out) ou cliente em falta.", 400);
    }

    mysqli_begin_transaction($link);

    // 4. Redução de Disponibilidade
    $query_update_disp = "UPDATE alojamento SET disponibilidade = disponibilidade - ? WHERE id_alojamento = ? AND disponibilidade >= ?;";
    $stmt_update = mysqli_prepare($link, $query_update_disp);
    mysqli_stmt_bind_param($stmt_update, "iii", $num_quartos_reservados, $id_alojamento, $num_quartos_reservados);
    mysqli_stmt_execute($stmt_update);

    if (mysqli_stmt_affected_rows($stmt_update) === 0) {
        mysqli_stmt_close($stmt_update);
        throw new Exception("Disponibilidade insuficiente ou alojamento não encontrado.", 409);
    }
    mysqli_stmt_close($stmt_update); 

    // 5. INSERÇÃO CRÍTICA: SQL DIRETO 
    $estado_reserva = 'Pendente'; 

    $query_insert_reserva = "
        INSERT INTO reservas (
            id_alojamento, id_utilizador, nome_cliente, apelido_cliente, 
            data_check_in, data_check_out, num_quartos, preco_total, 
            data_reserva, morada_reserva, cidade_reserva, 
            codigo_postal_reserva, pais_reserva, entidade_pagamento, referencia_pagamento, estado 
        ) VALUES (
            '$id_alojamento', '$id_utilizador', '$nome_cliente', '$apelido_cliente', 
            '$data_check_in', '$data_check_out', $num_quartos_reservados, $preco_total_float, 
            '$data_reserva_mysql', '$morada_reserva', '$cidade_reserva', 
            '$codigo_postal', '$pais_reserva', '$entidade', '$referencia', '$estado_reserva'
        )";

    if (!mysqli_query($link, $query_insert_reserva)) {
        $detalhe_erro = mysqli_error($link);
        throw new Exception("Falha ao inserir a reserva (MySQL Query Direta): " . $detalhe_erro, 500);
    }
    
    $id_reserva = mysqli_insert_id($link);

    mysqli_commit($link);

    // 6. Sucesso - Retorno JSON
    echo json_encode([
        'success' => true, 
        'id_reserva' => $id_reserva,
        'preco_total' => $preco_total_float, 
        'entidade' => $entidade, 
        'referencia' => $referencia, 
        'message' => 'Reserva criada e disponibilidade reduzida. Pagamento Pendente.'
    ]);

} catch (Exception $e) {
    if ($is_connected) {
        mysqli_rollback($link); 
    }
    
    $details = $e->getMessage();
    $code = $e->getCode() === 400 || $e->getCode() === 409 ? $e->getCode() : 500;
    
    http_response_code($code);
    echo json_encode([
        'success' => false, 
        'error' => $code === 409 ? 'Disponibilidade indisponível para esta seleção.' : 'Erro interno do servidor ou dados inválidos.', 
        'details' => $details
    ]);
}

if ($is_connected) {
    mysqli_close($link);
}
?>