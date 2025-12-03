<?php
session_start();
require 'dbconnection.php'; 

// Define que a resposta será em JSON (crucial para o JavaScript)
header('Content-Type: application/json');

// --- 1. CONFIGURAÇÃO E VALIDAÇÃO DE MÉTODO ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
    exit;
}

// 2. RECOLHA DE DADOS 
// Os dados serão ligados ao prepared statement, não necessitam de mysqli_real_escape_string aqui.
$id_alojamento = $_POST['id_alojamento'] ?? '';
$id_utilizador = $_SESSION['id_user'] ?? $_POST['id_utilizador'] ?? ''; 
$data_check_in  = $_POST['data_check_in'] ?? '';
$data_check_out = $_POST['data_check_out'] ?? '';
$num_hospedes = (int)($_POST['num_hospedes'] ?? 1);
$preco_total = (float)($_POST['preco_total'] ?? 0.00);

// Dados Pessoais e de Contacto
$nome_cliente    = $_POST['nome'] ?? '';
$apelido_cliente = $_POST['apelido'] ?? '';
$morada_reserva  = $_POST['morada'] ?? '';
$cidade_reserva  = $_POST['cidade'] ?? '';
$codigo_postal   = $_POST['codigo_postal'] ?? '';
$pais_reserva    = $_POST['pais'] ?? '';

// 3. VALIDAÇÃO MÍNIMA
if (empty($nome_cliente) || empty($data_check_in) || $preco_total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados essenciais em falta ou preço inválido.']);
    exit;
}

// --- 4. INSERÇÃO SEGURA NA BD USANDO PREPARED STATEMENTS ---

$query = "INSERT INTO reservas (
            id_alojamento, id_utilizador, data_check_in, data_check_out, num_hospedes, 
            preco_total, nome_cliente, apelido_cliente, morada_reserva, cidade_reserva, 
            codigo_postal_reserva, pais_reserva, data_reserva, estado
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Confirmada')";

$stmt = mysqli_prepare($link, $query);

// Tipos de dados (12 colunas)
mysqli_stmt_bind_param(
    $stmt, 
    "iissdsssssss", 
    $id_alojamento, 
    $id_utilizador, 
    $data_check_in, 
    $data_check_out, 
    $num_hospedes, 
    $preco_total, 
    $nome_cliente, 
    $apelido_cliente, 
    $morada_reserva, 
    $cidade_reserva,
    $codigo_postal, 
    $pais_reserva
);

if (mysqli_stmt_execute($stmt)) {
    $id_reserva = mysqli_insert_id($link);
    
    // Sucesso: Retorna o ID da reserva para o JavaScript
    echo json_encode(['success' => true, 'id_reserva' => $id_reserva]);
} else {
    // ERRO CRÍTICO: Devolve uma mensagem de erro JSON com detalhe do MySQL
    http_response_code(500); 
    echo json_encode(['error' => 'Erro ao inserir na BD. Detalhe: ' . mysqli_error($link)]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);