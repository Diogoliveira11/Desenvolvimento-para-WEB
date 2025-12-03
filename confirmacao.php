<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    http_response_code(403);
    exit("Acesso negado.");
}

$id_reserva = isset($_GET['reserva']) ? mysqli_real_escape_string($link, $_GET['reserva']) : exit("ID de Reserva em falta.");

// 2. BUSCAR DETALHES DA RESERVA E DO ALOJAMENTO
$query_reserva = "
    SELECT 
        R.*, 
        A.nome AS nome_alojamento,
        A.id_alojamento
    FROM 
        reservas AS R
    INNER JOIN 
        alojamento AS A ON R.id_alojamento = A.id_alojamento
    WHERE 
        R.id_reserva = '$id_reserva'
        AND R.id_utilizador = '{$_SESSION['id_user']}'
";

$result_reserva = mysqli_query($link, $query_reserva);

if (!$result_reserva || mysqli_num_rows($result_reserva) === 0) {
    exit("Reserva não encontrada ou não pertence ao utilizador logado.");
}

$reserva = mysqli_fetch_assoc($result_reserva);

// 3. Formatação de Datas e Textos
$dias_semana = ['Sun' => 'Domingo', 'Mon' => 'Segunda', 'Tue' => 'Terça', 'Wed' => 'Quarta', 'Thu' => 'Quinta', 'Fri' => 'Sexta', 'Sat' => 'Sábado'];

function get_dia_pt(\DateTime $date, array $map) {
    $dia_en = $date->format('D');
    return $map[$dia_en] ?? $dia_en;
}

try {
    $check_in_dt = new DateTime($reserva['data_check_in']);
    $check_out_dt = new DateTime($reserva['data_check_out']);
    $reserva_dt = new DateTime($reserva['data_reserva']);
    
    $dia_pt_in = get_dia_pt($check_in_dt, $dias_semana);
    $dia_pt_out = get_dia_pt($check_out_dt, $dias_semana);
    
    $intervalo = $check_in_dt->diff($check_out_dt);
    $num_noites = $intervalo->days > 0 ? $intervalo->days : 1;

} catch (Exception $e) {
    die("Erro ao formatar datas: " . $e->getMessage());
}

$texto_noites = $num_noites > 1 ? 'noites' : 'noite';

// Formatação de Preço e IDs
$preco_total_formatado = number_format($reserva['preco_total'], 2, ',', '.');
$id_alojamento_fk = $reserva['id_alojamento']; 

// Lógica de Negócio: O botão de avaliação só aparece se a data de check-out passou
$check_out_dt_full = new DateTime($reserva['data_check_out'] . ' 12:00:00'); // Assume o check-out é ao meio-dia
$hoje = new DateTime();
$pode_avaliar = $check_out_dt_full < $hoje; 
?>

<div class="p-4">
    <div class="text-center mb-6">
        <svg class="w-16 h-16 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h2 class="text-3xl font-bold text-gray-900 mb-2" id="modal-title">Reserva Confirmada!</h2>
        <p class="text-gray-600">O seu pagamento de <?php echo $preco_total_formatado; ?>€ foi processado.</p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-3">
        <p class="font-bold text-lg text-gray-800 border-b pb-2">Detalhes</p>
        
        <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V10.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 10.5v8.25m-18 0h18" />
            </svg>
            <span class="font-normal">Check-in: <?php echo $dia_pt_in; ?>, <?php echo $check_in_dt->format('d/m/Y'); ?></span>
        </p>
        
        <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V10.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 10.5v8.25m-18 0h18" />
            </svg>
            <span class="font-normal">Check-out: <?php echo $dia_pt_out; ?>, <?php echo $check_out_dt->format('d/m/Y'); ?></span>
        </p>
        
        <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span class="font-normal">Hóspedes: <?php echo $reserva['num_hospedes']; ?></span>
        </p>
        
        <p class="flex items-center gap-2 font-bold text-gray-900 border-t pt-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="font-bold">Total Pago: <?php echo $preco_total_formatado; ?>€</span>
        </p>
    </div>
    
    <div class="mt-4 pt-3 flex justify-center border-t">
        <?php if ($pode_avaliar): ?>
            <button data-alojamento-id="<?php echo $reserva['id_alojamento']; ?>"
               id="abrir-modal-avaliacao"
               class="inline-flex items-center px-4 py-2 text-base font-semibold rounded-lg shadow-lg 
                      text-[#565656] bg-[#c8c8b2] hover:bg-[#565656] hover:text-white transition-colors">
                Faça a sua avaliação!
            </button>
        <?php else: ?>
            <p class="text-sm text-gray-500 italic">Poderá avaliar este alojamento após a data de Check-out (<?php echo $check_out_dt->format('d/m/Y'); ?>).</p>
        <?php endif; ?>
    </div>
    
</div>