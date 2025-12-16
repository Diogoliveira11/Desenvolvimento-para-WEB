<?php
require '../dbconnection.php'; 

// Consulta para contar o total de utilizadores
$sql = "SELECT COUNT(id_user) AS total FROM utilizadores";
$result = mysqli_query($link, $sql);

$total = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
}

header('Content-Type: application/json');
echo json_encode(['total' => $total]);
exit;