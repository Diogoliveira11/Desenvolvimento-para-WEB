<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php'); 
    exit;
}

$user_id = $_SESSION['id_user'] ?? die("ID de Utilizador em falta.");

$alojamento_id = isset($_GET['id']) ? mysqli_real_escape_string($link, $_GET['id']) : die("ID de Alojamento em falta.");
$check_in_str = isset($_GET['check_in']) ? $_GET['check_in'] : die("Data de Check-in em falta.");
$check_out_str = isset($_GET['check_out']) ? $_GET['check_out'] : die("Data de Check-out em falta.");
$num_hospedes = isset($_GET['hospedes']) ? (int)$_GET['hospedes'] : 1;

// --- 2. CÁLCULO DE DATAS E PREÇOS ---

// 2.1. Obter Preço Base, Nome e IMAGEM DO ALOJAMENTO
$query_alojamento = "SELECT nome, localizacao, check_in_out, preco_base, preco_final, COALESCE(preco_final, preco_base) AS preco_a_usar 
                     FROM alojamento 
                     WHERE id_alojamento = $alojamento_id";
$result_alojamento = mysqli_query($link, $query_alojamento);
if (!$result_alojamento || mysqli_num_rows($result_alojamento) === 0) { exit("Erro: Alojamento não encontrado."); }
$alojamento_data = mysqli_fetch_assoc($result_alojamento);

$nome_alojamento = htmlspecialchars($alojamento_data['nome']);
$localizacao_alojamento = htmlspecialchars($alojamento_data['localizacao']);

$horarios_raw = $alojamento_data['check_in_out'] ?? '14:00/12:00'; // Default se faltar na BD
$horarios = explode('/', $horarios_raw); // Divide a string em duas partes
$horario_check_in = trim($horarios[0] ?? '14:00');
$horario_check_out = trim($horarios[1] ?? '12:00');

$preco_a_usar_noite = (float)$alojamento_data['preco_a_usar'];

// Lógica para obter a IMAGEM PRINCIPAL
$query_imagem = "SELECT caminho_ficheiro FROM imagens WHERE id_alojamento = $alojamento_id AND imagem_principal = 1 LIMIT 1";
$result_imagem = mysqli_query($link, $query_imagem);
$imagem_path = 'imagens/placeholder.png'; 
if ($result_imagem && mysqli_num_rows($result_imagem) > 0) {
    $row_imagem = mysqli_fetch_assoc($result_imagem);
    $imagem_path = $row_imagem['caminho_ficheiro'];
}

// 2.2. Cálculo de Noites
$check_in = new DateTime($check_in_str);
$check_out = new DateTime($check_out_str);
if ($check_out <= $check_in) { die("Erro: A data de Check-out deve ser posterior à data de Check-in."); }
$diferenca = $check_in->diff($check_out);
$num_noites = $diferenca->days;
if ($num_noites <= 0) { die("As datas de Check-out devem ser posteriores às de Check-in."); }

// Variáveis para pluralização
$texto_noites = ($num_noites == 1) ? 'noite' : 'noites';
$texto_hospedes = ($num_hospedes == 1) ? 'hóspede' : 'hóspedes';

// --- Lógica para mapear os dias da semana para português (pt-pt) ---
$dias_semana = [
    'Monday' => 'Segunda',
    'Tuesday' => 'Terça',
    'Wednesday' => 'Quarta',
    'Thursday' => 'Quinta',
    'Friday' => 'Sexta',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];
$dia_en_in = $check_in->format('l'); 
$dia_en_out = $check_out->format('l');
$dia_pt_in = $dias_semana[$dia_en_in] ?? $dia_en_in; 
$dia_pt_out = $dias_semana[$dia_en_out] ?? $dia_en_out;

// 2.3. Cálculo de Preço Bruto
$preco_bruto = $preco_a_usar_noite * $num_noites; 

// 2.4. APLICAÇÃO DE SUPLEMENTO POR HÓSPEDE EXTRA (Acima de 2)
$suplemento_noite_extra = 20.00;
$suplemento_total = 0.00;
$num_hospedes_extra = 0; 
if ($num_hospedes > 2) {
    $num_hospedes_extra = $num_hospedes - 2; 
    $suplemento_total = $num_hospedes_extra * $suplemento_noite_extra * $num_noites;
    $preco_bruto += $suplemento_total;
}

// 2.5. Aplicação de Desconto (10% se for a PRIMEIRA reserva e logado)
$desconto_percentagem = 0;
$desconto_valor = 0.00;
$is_first_reservation = false;

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // 1. Verificar se o utilizador já tem reservas na tabela 'reservas' 
    $query_check_reserva = "SELECT id_reserva FROM reservas WHERE id_utilizador = $user_id LIMIT 1";
    $result_check = mysqli_query($link, $query_check_reserva);

    if (mysqli_num_rows($result_check) == 0) {
        $is_first_reservation = true;
        $desconto_percentagem = 10;
        $desconto_valor = $preco_bruto * 0.10; 
    }
}

// Cálculo do Preço Final
$preco_final = $preco_bruto - $desconto_valor;

$pageTitle = 'Finalizar reserva | ' . $nome_alojamento;

// Dados do Utilizador logado:
$user_saudacao_nome = $_SESSION['utilizador'] ?? ''; 
$user_email = $_SESSION['email'] ?? ''; 

$paises = require 'paises.php'; 
asort($paises); 

?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
    <link href="css/nav.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?>
    
    <nav class="bg-[#c8c8b2] h-2"></nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-grow mb-14 py-10">
        
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                    
                    <div class="checkout-img-container rounded-lg mb-4">
                        <img src="<?php echo htmlspecialchars($imagem_path); ?>" alt="<?php echo $nome_alojamento; ?>">
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo $nome_alojamento; ?></h3>
                    <p class="text-sm text-gray-600 mb-4"><?php echo $localizacao_alojamento; ?></p>
                    
                    <div class="border-t pt-3 space-y-1">
                        <p class="font-semibold text-gray-800">Dados da sua reserva</p>
                        <p class="text-sm">Check-in: <?php echo $dia_pt_in; ?>, <?php echo $check_in->format('d/m/Y'); ?> às <?php echo $horario_check_in; ?></p>
                        <p class="text-sm">Check-out: <?php echo $dia_pt_out; ?>, <?php echo $check_out->format('d/m/Y'); ?> às <?php echo $horario_check_out; ?></p>
                        <p class="text-sm">Estadia: <?php echo $num_noites; ?> <?php echo $texto_noites; ?>, <?php echo $num_hospedes; ?> <?php echo $texto_hospedes; ?></p>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Resumo do preço</h3>
                    <div class="border-t pt-3">
                        
                        <?php if ($desconto_valor > 0): ?>
                        <div class="flex justify-between text-sm text-gray-700 mb-1">
                            <span>Subtotal (<?php echo $num_noites; ?> <?php echo $texto_noites; ?>):</span>
                            <span>€<?php echo number_format($preco_bruto, 2, ',', '.'); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($desconto_valor > 0): ?>
                        <div class="flex justify-between text-base text-green-600 font-semibold mb-2">
                            <span>Desconto (Primeira Reserva - 10%):</span>
                            <span>- €<?php echo number_format($desconto_valor, 2, ',', '.'); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="flex justify-between font-bold text-2xl text-gray-900">
                            <span>Total a pagar:</span>
                            <span>€<?php echo number_format($preco_final, 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="lg:col-span-2 space-y-8">
                
                <form id="form-pagamento" action="processapagamento.php" method="POST" class="space-y-6"> 
                    
                    <input type="hidden" name="id_alojamento" value="<?php echo $alojamento_id; ?>">
                    <input type="hidden" name="id_utilizador" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="data_check_in" value="<?php echo $check_in_str; ?>">
                    <input type="hidden" name="data_check_out" value="<?php echo $check_out_str; ?>">
                    <input type="hidden" name="num_hospedes" value="<?php echo $num_hospedes; ?>">
                    <input type="hidden" name="preco_total" value="<?php echo number_format($preco_final, 2, '.', ''); ?>">
                    
                    <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-200" style="margin-top: 0px">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">
                            Insira os seus dados
                        </h2>

                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="w-1/2">
                                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                                    <input type="text" id="nome" name="nome" value="" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="w-1/2">
                                    <label for="apelido" class="block text-sm font-medium text-gray-700">Apelido</label>
                                    <input type="text" id="apelido" name="apelido" value="" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Endereço de e-mail</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            
                            <div class="flex space-x-4">
                                <div class="w-2/3">
                                    <label for="morada" class="block text-sm font-medium text-gray-700">Morada</label>
                                    <input type="text" id="morada" name="morada" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="w-1/3">
                                    <label for="cidade" class="block text-sm font-medium text-gray-700">Cidade</label>
                                    <input type="text" id="cidade" name="cidade" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            
                            <div class="flex space-x-4">
                                <div class="w-1/2">
                                    <label for="codigo_postal" class="block text-sm font-medium text-gray-700">Código Postal (opcional)</label>
                                    <input type="text" id="codigo_postal" name="codigo_postal" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="w-1/2">
                                    <label for="pais" class="block text-sm font-medium text-gray-700">País</label>
                                    <select id="pais" name="pais" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        <option value="" disabled selected>Selecione um País</option>
                                        
                                        <?php foreach ($paises as $valor => $nome): ?>
                                            <option value="<?php echo htmlspecialchars($valor); ?>" 
                                                    <?php echo ($valor === 'Portugal') ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($nome); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">
                            Como gostaria de realizar o pagamento?
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-start gap-4">
                                <div class="border border-gray-400 rounded-lg p-3 w-1/4 flex flex-col items-center justify-center text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-600 mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5zM10 16.5h4a.5.5 0 000-1h-4a.5.5 0 000 1zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-xs font-semibold">Novo cartão</span>
                                </div>
                                <div class="border border-gray-300 rounded-lg p-3 w-1/4 flex flex-col items-center justify-center text-center">
                                    <div class="h-5 mb-2 bg-gray-300 w-full"></div>
                                    <span class="text-xs text-gray-500">Google Pay</span>
                                </div>
                                <div class="border border-gray-300 rounded-lg p-3 w-1/4 flex flex-col items-center justify-center text-center">
                                    <div class="h-5 mb-2 bg-gray-300 w-full"></div>
                                    <span class="text-xs text-gray-500">PayPal</span>
                                </div>
                                <div class="border border-gray-300 rounded-lg p-3 w-1/4 flex flex-col items-center justify-center text-center relative">
                                    <span class="absolute top-0 right-0 text-xs bg-yellow-400 text-gray-800 px-1 rounded-bl-lg">NOVIDADE</span>
                                    <div class="h-5 mb-2 bg-gray-300 w-full"></div>
                                    <span class="text-xs text-gray-500">Revolut Pay</span>
                                </div>
                            </div>
                            
                            <div class="border-t pt-4">
                                <h4 class="font-semibold text-gray-800 mb-3">Novo cartão</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="card_nome" class="block text-sm font-medium text-gray-700">Nome do titular do cartão</label>
                                        <input type="text" id="card_nome" name="card_nome" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="card_numero" class="block text-sm font-medium text-gray-700">Número do cartão</label>
                                        <input type="text" id="card_numero" name="card_numero" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div class="flex space-x-4">
                                        <div class="w-1/2">
                                            <label for="card_exp" class="block text-sm font-medium text-gray-700">Validade (MM/AA)</label>
                                            <input type="text" id="card_exp" name="card_exp" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="w-1/2">
                                            <label for="card_cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                            <input type="text" id="card_cvv" name="card_cvv" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button id="submit-pagamento" type="submit" 
                            class="w-full py-3 bg-[#c8c8b2] text-[#707070] font-bold rounded-xl hover:bg-[#707070] hover:text-white 
                                    transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl text-xl flex items-center justify-center">
                        CONFIRMAR E PAGAR
                    </button>
                    
                </form>
            </div>
            
        </div>
    </main>

    <div id="modal-confirmacao" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden items-center justify-center" aria-modal="true" role="dialog">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-h-full max-w-lg w-full m-4 animate-modal-pop-in">
            <div id="modal-content">
                <div class="text-center p-8">
                    <svg class="animate-spin h-8 w-8 text-[#c8c8b2] mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-700">A processar pagamento e a finalizar reserva...</p>
                </div>
            </div>
            <div id="modal-fechar-btn" class="p-4 border-t hidden">
                <button onclick="document.getElementById('modal-confirmacao').classList.add('hidden'); location.href='index.php';" 
                        class="w-full py-2 bg-[#707070] text-white font-bold rounded-lg hover:bg-[#565656] transition-colors">
                    Voltar à Página Inicial
                </button>
            </div>
        </div>
    </div>

    <div id="modal-avaliacao" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden items-center justify-center" aria-modal="true" role="dialog">
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-h-full max-w-xl w-full m-4 animate-modal-pop-in">
        <button onclick="document.getElementById('modal-avaliacao').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <div id="modal-avaliacao-content" class="p-6">
            <div class="text-center p-8">
                <svg class="animate-spin h-8 w-8 text-[#c8c8b2] mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-700">A carregar formulário de avaliação...</p>
            </div>
        </div>
    </div>
    </div>

    <script src="js/global.js"></script>

    <?php include 'includes/footer.php'; ?>

</body>
</html> 