<?php
require '../dbconnection.php'; 

$sql = "SELECT COUNT(id_alojamento) AS total FROM alojamento";
$result = mysqli_query($link, $sql);

$total = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
}

header('Content-Type: application/json');
echo json_encode(['total' => $total]);
exit;