<?php
session_start();
require 'dbconnection.php'; 

header('Content-Type: application/json');

// Recebe o ID da reserva a ser cancelada
$id_reserva = $_POST['id_reserva'] ?? $_GET['id_reserva'] ?? null; 

if (empty($id_reserva) || !is_numeric($id_reserva)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de Reserva inválido.']);
    exit;
}

mysqli_begin_transaction($link);

try {
    // 1. BUSCAR DADOS CRUCIAIS
    $query_select = "
        SELECT 
            id_alojamento, num_quartos, estado
        FROM 
            reservas
        WHERE 
            id_reserva = ?
        FOR UPDATE; 
    ";
    $stmt_select = mysqli_prepare($link, $query_select);
    mysqli_stmt_bind_param($stmt_select, "i", $id_reserva);
    mysqli_stmt_execute($stmt_select);
    $result_select = mysqli_stmt_get_result($stmt_select);
    $reserva = mysqli_fetch_assoc($result_select);
    mysqli_stmt_close($stmt_select);

    if (!$reserva) {
        throw new Exception("Reserva não encontrada.", 404);
    }

    if ($reserva['estado'] === 'Cancelada') {
        throw new Exception("A reserva já se encontra cancelada.", 400);
    }
    
    $id_alojamento = $reserva['id_alojamento'];
    $num_quartos = $reserva['num_quartos'];

    // 2. ATUALIZAR ESTADO DA RESERVA para 'Cancelada'
    $query_update_reserva = "
        UPDATE reservas
        SET estado = 'Cancelada'
        WHERE id_reserva = ?
    ";
    $stmt_update_reserva = mysqli_prepare($link, $query_update_reserva);
    mysqli_stmt_bind_param($stmt_update_reserva, "i", $id_reserva);
    mysqli_stmt_execute($stmt_update_reserva);
    mysqli_stmt_close($stmt_update_reserva);

    // 3. REPOR A DISPONIBILIDADE
    $query_repor_disp = "
        UPDATE alojamento
        SET disponibilidade = disponibilidade + ?
        WHERE id_alojamento = ?;
    ";
    $stmt_repor = mysqli_prepare($link, $query_repor_disp);
    mysqli_stmt_bind_param($stmt_repor, "ii", $num_quartos, $id_alojamento);
    mysqli_stmt_execute($stmt_repor);
    
    if (mysqli_stmt_affected_rows($stmt_repor) === 0) {
        error_log("ERRO: Falha ao repor disponibilidade para Alojamento ID: {$id_alojamento}");
    }
    mysqli_stmt_close($stmt_repor);

    // 4. COMMIT
    mysqli_commit($link);

    echo json_encode([
        'success' => true, 
        'message' => 'Reserva cancelada e disponibilidade reposta com sucesso. (Quartos repostos: ' . $num_quartos . ')'
    ]);

} catch (Exception $e) {
    mysqli_rollback($link); 
    http_response_code($e->getCode() === 404 ? 404 : 500);
    echo json_encode([
        'success' => false, 
        'message' => 'Falha no cancelamento da reserva.',
        'details' => $e->getMessage()
    ]);
}

mysqli_close($link);
?>