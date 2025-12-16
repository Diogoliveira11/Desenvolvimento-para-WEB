<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'dbconnection.php'; 

// 1. Encontrar reservas que terminaram e ainda não foram processadas
$query_finalizar = "
    SELECT 
        r.id_reserva, r.id_alojamento, r.num_quartos
    FROM 
        reservas r
    WHERE 
        DATE_ADD(r.data_check_out, INTERVAL 14 HOUR) <= NOW() 
        AND r.estado = 'Confirmada'
";

$result = mysqli_query($link, $query_finalizar);
$total_finalizadas = 0;

if ($result && mysqli_num_rows($result) > 0) {
    while ($reserva = mysqli_fetch_assoc($result)) {
        
        $id_alojamento = $reserva['id_alojamento'];
        $num_quartos = $reserva['num_quartos'];
        $id_reserva = $reserva['id_reserva'];

        // 2. Repor a disponibilidade (Isto assume que a disponibilidade é reposta após a estadia)
        $query_repor = "
            UPDATE alojamento
            SET disponibilidade = disponibilidade + $num_quartos
            WHERE id_alojamento = $id_alojamento;
        ";
        
        if (mysqli_query($link, $query_repor)) {
            // 3. Marcar a reserva como processada (para evitar repetição)
            $query_marcar = "UPDATE reservas SET estado = 'Finalizada' WHERE id_reserva = $id_reserva";
            
            mysqli_query($link, $query_marcar);
            $total_finalizadas++;
        }
    }
}

echo "Cron Job de finalização concluído. Total de reservas finalizadas (pós check-out): $total_finalizadas\n";

mysqli_close($link);
?>