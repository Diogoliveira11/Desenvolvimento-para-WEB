<?php
session_start();
require '../dbconnection.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

// 1. Verifica se o ID da reserva foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_reserva'])) {
    http_response_code(400);
    $response['message'] = 'Requisição inválida ou ID de Reserva em falta.';
    echo json_encode($response);
    exit;
}

$id_reserva = $_POST['id_reserva'];

// 2. Verifica a conexão MySQLi
if (!isset($link) || !$link) {
    http_response_code(500);
    $response['message'] = 'Erro de ligação à base de dados.';
    echo json_encode($response);
    exit;
}

// INÍCIO DA ATUALIZAÇÃO
try {
    // 3. Query SQL para ATUALIZAR o estado para 'Confirmada'
    $query = "UPDATE reservas SET estado = 'Confirmada' WHERE id_reserva = ? AND estado = 'Pendente'";
    $stmt = mysqli_prepare($link, $query);

    // VERIFICAÇÃO ADICIONAL CRUCIAL: Ver se o prepared statement falhou
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = 'Falha na preparação da query SQL. Verifique a sintaxe da query: ' . mysqli_error($link);
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_reserva); 
    mysqli_stmt_execute($stmt);
    
    // 4. Verifica se a atualização foi bem-sucedida
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response['success'] = true;
        $response['message'] = 'Reserva confirmada com sucesso.';
    } else {
        // Se 0 linhas foram afetadas, pode ser porque a reserva não existe ou já não está Pendente
        $response['message'] = 'Reserva não encontrada ou já foi Confirmada/Cancelada.';
    }
    
    mysqli_stmt_close($stmt);

} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = 'Erro interno do servidor durante a confirmação.';
    $response['details'] = $e->getMessage();
}

// 5. Envia a resposta
mysqli_close($link);
echo json_encode($response);
?>