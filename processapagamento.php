<?php
session_start();
require 'dbconnection.php'; 

header('Content-Type: application/json');

// --- 1. CONFIGURAÇÃO ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
    exit;
}

// 2. Recolher e sanitizar os dados do formulário
$id_alojamento = mysqli_real_escape_string($link, $_POST['id_alojamento'] ?? '');
$id_utilizador = mysqli_real_escape_string($link, $_POST['id_utilizador'] ?? '');
$data_check_in  = mysqli_real_escape_string($link, $_POST['data_check_in'] ?? '');
$data_check_out = mysqli_real_escape_string($link, $_POST['data_check_out'] ?? '');
$num_hospedes = (int)($_POST['num_hospedes'] ?? 1);
$preco_total = (float)($_POST['preco_total'] ?? 0.00);

// Dados Pessoais e de Contacto
$nome_cliente    = mysqli_real_escape_string($link, $_POST['nome'] ?? '');
$apelido_cliente = mysqli_real_escape_string($link, $_POST['apelido'] ?? '');
$email_cliente   = mysqli_real_escape_string($link, $_POST['email'] ?? '');
$morada_reserva  = mysqli_real_escape_string($link, $_POST['morada'] ?? '');
$cidade_reserva  = mysqli_real_escape_string($link, $_POST['cidade'] ?? '');
$codigo_postal   = mysqli_real_escape_string($link, $_POST['codigo_postal'] ?? '');
$pais_reserva    = mysqli_real_escape_string($link, $_POST['pais'] ?? '');

// 3. Validação Mínima
if (empty($nome_cliente) || empty($email_cliente) || empty($data_check_in) || $preco_total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados essenciais em falta.']);
    exit;
}

// 4. Inserir a Reserva na BD
$query = "INSERT INTO reservas (
            id_alojamento, id_utilizador, data_check_in, data_check_out, num_hospedes, 
            preco_total, nome_cliente, apelido_cliente, morada_reserva, cidade_reserva, 
            codigo_postal_reserva, pais_reserva, data_reserva, estado
          ) VALUES (
            '$id_alojamento', '$id_utilizador', '$data_check_in', '$data_check_out', '$num_hospedes', 
            '$preco_total', '$nome_cliente', '$apelido_cliente', '$morada_reserva', '$cidade_reserva',
            '$codigo_postal', '$pais_reserva', NOW(), 'Confirmada'
          )";

if (mysqli_query($link, $query)) {
    $id_reserva = mysqli_insert_id($link);
    
    // Sucesso: Retorna o ID da reserva para o JavaScript
    echo json_encode(['success' => true, 'id_reserva' => $id_reserva]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao inserir na BD: ' . mysqli_error($link)]);
}

mysqli_close($link);
?>