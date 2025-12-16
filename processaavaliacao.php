<?php

session_start();
require 'dbconnection.php'; 

$idUtilizador = $_SESSION['id_user'] ?? null;
$idReserva = $_POST['id_reserva'] ?? null; 
$idAlojamento = $_POST['id_alojamento'] ?? null; 

// VERIFICAÇÃO
if (empty($idUtilizador) || !is_numeric($idUtilizador) || $idUtilizador <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ERRO 01: Sessão invlida. ID do utilizador no encontrado.']);
    exit();
}

if (empty($idReserva) || !is_numeric($idReserva) || empty($idAlojamento) || !is_numeric($idAlojamento)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ERRO 02: ID da Reserva ou Alojamento invlido no formulrio.']);
    exit();
}

// 1. Coleta e Validação de Dados
$user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
$alojamento_id = isset($_POST['id_alojamento']) ? (int)$_POST['id_alojamento'] : 0;
$reserva_id = isset($_POST['id_reserva']) ? (int)$_POST['id_reserva'] : 0; 
$avaliacao = isset($_POST['avaliacao']) ? (int)$_POST['avaliacao'] : 0;
$comentario_html = isset($_POST['comentario']) ? $_POST['comentario'] : '';

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada. Por favor, faça login novamente.']);
    exit;
}
if ($alojamento_id <= 0 || $reserva_id <= 0 || $avaliacao < 1 || $avaliacao > 10 || empty($comentario_html)) {
    echo json_encode(['success' => false, 'message' => 'Dados de avaliação incompletos ou inválidos.']);
    exit;
}

// 2. Tenta Inserir na Base de Dados
// INSERIR NA BASE DE DADOS
$query_insert = "INSERT INTO avaliacoes (id_user, id_alojamento, id_reserva, avaliacao, comentario, data_avaliacao) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($link, $query_insert);

if (!$stmt) {
    $message = "ERRO FATAL (PREPARAÇÃO): Falha ao preparar a consulta de BD. Erro: " . mysqli_error($link);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

mysqli_stmt_bind_param($stmt, "iiiis", $user_id, $alojamento_id, $reserva_id, $avaliacao, $comentario_html);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Avaliação enviada com sucesso!']);
} else {
    $db_error_code = mysqli_errno($link);
    $message = "ERRO FATAL (EXECUÇÃO): A inserção falhou. Código: " . $db_error_code . ". Mensagem: " . mysqli_error($link);
    
    if ($db_error_code == 1062) {
        $message = "Esta reserva já foi avaliada. Só pode avaliar uma vez.";
    } else if ($db_error_code == 1406) {
        $message = "O seu comentário é demasiado longo. Altere a coluna 'comentario' para TEXT.";
    }

    echo json_encode(['success' => false, 'message' => $message]);
}

mysqli_stmt_close($stmt);
exit;