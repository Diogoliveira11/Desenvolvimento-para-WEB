<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: auth/login.php'); 
    exit;
}

// Inicializa variáveis com NULL para evitar Warnings
$user_id = $_SESSION['id_user'] ?? die("ID de Utilizador em falta.");

$alojamento_id = $_GET['id'] ?? null;
$check_in_str = $_GET['data_check_in'] ?? null;
$check_out_str = $_GET['data_check_out'] ?? null;

// VALIDAÇÃO RIGOROSA DE PARÂMETROS GET
if (empty($alojamento_id) || empty($check_in_str) || empty($check_out_str)) {
    $erro_falta = [];
    if (empty($alojamento_id)) $erro_falta[] = "ID do Alojamento";
    if (empty($check_in_str)) $erro_falta[] = "Data de Check-in";
    if (empty($check_out_str)) $erro_falta[] = "Data de Check-out";
    $mensagem_erro = "Erro: Parâmetros essenciais em falta: " . implode(', ', $erro_falta) . ". Volte atrás e preencha as datas.";
    
    die('<!DOCTYPE html><html lang="pt-pt"><head><title>Erro</title><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-red-50 p-8 text-center flex items-center justify-center min-h-screen"><div class="bg-white p-6 rounded-lg shadow-xl border border-red-300"><h1 class="text-3xl font-bold text-red-700 mb-4">Acesso Negado</h1><p class="text-gray-700">' . $mensagem_erro . '</p><a href="javascript:history.back()" class="mt-4 inline-block px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">Voltar à Página Anterior</a></div></body></html>');
}

$alojamento_id = mysqli_real_escape_string($link, $alojamento_id);

// 1. DADOS DE ENTRADA DO ALOJAMENTO.PHP
$num_quartos = isset($_GET['quartos_selecionados']) ? (int)$_GET['quartos_selecionados'] : 1; 
$num_quartos = max(1, $num_quartos); 


// 2. CÁLCULO DE DATAS E PREÇOS
// 2.1. Obter Preço Base, Nome e IMAGEM DO ALOJAMENTO (Query limpa)
$query_alojamento = "SELECT nome, localizacao, check_in_out, preco_base, preco_final, COALESCE(preco_final, preco_base) AS preco_a_usar 
                     FROM alojamento 
                     WHERE id_alojamento = $alojamento_id";
$result_alojamento = mysqli_query($link, $query_alojamento);
if (!$result_alojamento || mysqli_num_rows($result_alojamento) === 0) { exit("Erro: Alojamento não encontrado."); }
$alojamento_data = mysqli_fetch_assoc($result_alojamento);

$nome_alojamento = htmlspecialchars($alojamento_data['nome']);
$localizacao_alojamento = htmlspecialchars($alojamento_data['localizacao']);

$horarios_raw = $alojamento_data['check_in_out'] ?? '14:00/12:00'; 
$horarios = explode('/', $horarios_raw); 
$horario_check_in = trim($horarios[0] ?? '14:00');
$horario_check_out = trim($horarios[1] ?? '12:00');

$preco_a_usar_noite_por_quarto = (float)$alojamento_data['preco_a_usar'];

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
$texto_quartos = ($num_quartos == 1) ? 'quarto' : 'quartos';

// Lógica de mapeamento de dias
$dias_semana = [
    'Monday' => 'Segunda', 'Tuesday' => 'Terça', 'Wednesday' => 'Quarta',
    'Thursday' => 'Quinta', 'Friday' => 'Sexta', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
];
$dia_en_in = $check_in->format('l'); 
$dia_en_out = $check_out->format('l');
$dia_pt_in = $dias_semana[$dia_en_in] ?? $dia_en_in; 
$dia_pt_out = $dias_semana[$dia_en_out] ?? $dia_en_out;

// 2.3. Cálculo de Preço Bruto (Preço por Noite * Noites * Quartos)
$preco_bruto = $preco_a_usar_noite_por_quarto * $num_noites * $num_quartos; 

// 2.4. Aplicação de Desconto (10% se for a PRIMEIRA reserva e logado)
$desconto_percentagem = 0;
$desconto_valor = 0.00;
$is_first_reservation = false;

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // 1. Verificar se o utilizador já tem reservas
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
$preco_final_formatado = number_format($preco_final, 2, ',', '.');
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
    <title>RESERVAR ALOJAMENTO | ESPAÇO LUSITANO</title>
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
                        <p class="text-sm">Estadia: <?php echo $num_noites; ?> <?php echo $texto_noites; ?></p>
                        <p class="text-sm">Quartos: <?php echo $num_quartos; ?> <?php echo $texto_quartos; ?></p>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Resumo do preço</h3>
                    <div class="border-t pt-3">
                        
                        <?php if ($is_first_reservation): ?>
                        <div class="flex justify-between text-sm text-gray-700 mb-1">
                            <span>Subtotal (<?php echo $num_quartos; ?> <?php echo $texto_quartos; ?> x <?php echo $num_noites; ?> <?php echo $texto_noites; ?>):</span>
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
                            <span><?php echo $preco_final_formatado; ?>€</span>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="lg:col-span-2 space-y-8">
                
                <form id="form-finalizar-reserva" action="processatodafinalizacao.php" method="POST" class="space-y-6">
    
                    <input type="hidden" name="id_alojamento" value="<?php echo $alojamento_id; ?>">
                    <input type="hidden" name="id_utilizador" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="data_check_in" value="<?php echo $check_in_str; ?>">
                    <input type="hidden" name="data_check_out" value="<?php echo $check_out_str; ?>">
                    
                    <input type="hidden" name="num_quartos" value="<?php echo $num_quartos; ?>">
                    
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
                
                    
                    <button id="submit-reserva" type="submit" 
                            class="w-full py-3 bg-[#c8c8b2] text-[#707070] font-bold rounded-xl hover:bg-[#707070] hover:text-white 
                                    transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl text-xl flex items-center justify-center">
                        FINALIZAR RESERVA
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

<script>
    document.getElementById('form-finalizar-reserva').addEventListener('submit', function(event) {
        event.preventDefault(); 
        
        const form = event.target;
        const formData = new FormData(form);
        const modal = document.getElementById('modal-confirmacao'); 
        const modalContent = document.getElementById('modal-content');

        // 1. Mostrar o modal de processamento
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalContent.innerHTML = `
            <div class="text-center p-8">
                <svg class="animate-spin h-8 w-8 text-[#c8c8b2] mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-700">A finalizar reserva e a atualizar disponibilidade...</p>
            </div>
        `;
        document.getElementById('modal-fechar-btn').classList.add('hidden');

        // 2. Enviar dados por AJAX para o script de processamento
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return response.json().then(data => ({
                status: response.status,
                body: data
            }));
        })
        .then(result => {
            const data = result.body;
            
        if (result.status >= 200 && result.status < 300 && data.success) {
            
            // MENSAGEM DE SUCESSO
            modalContent.innerHTML = `
                <div class="text-center p-8 pb-4">
                    <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Reserva criada - Pagamento pendente!</h3>
          
                    <div class="bg-gray-100 p-4 rounded-xl space-y-2 mb-4 border border-gray-200 shadow-sm">
                        <p class="text-base font-bold text-gray-800 border-b border-gray-200 pb-1 mb-2">Detalhes de pagamento</p>
                        <p class="text-lg">Entidade: <span class="font-extrabold text-[#565656]">${data.entidade}</span></p>
                        <p class="text-lg">Referência: <span class="font-extrabold text-[#565656]">${data.referencia}</span></p>
                        <p class="text-xl font-bold text-gray-900 mt-2">Total: ${data.preco_total}€</p>
                    </div>
                    
                    <p class="text-sm text-gray-500 italic px-4">
                        A sua reserva ficará com o estado 'Pendente' até o pagamento ser confirmado pela administração.
                    </p>
                </div>
            `;
            document.getElementById('modal-fechar-btn').classList.remove('hidden');

                    
            } else {
                // ERRO RETORNADO PELO SERVIDOR
                console.error('Erro no processamento da finalização:', data);
                modalContent.innerHTML = `
                    <div class="text-center p-8">
                        <svg class="w-16 h-16 text-red-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Reserva falhada!</h3>
                        <p class="text-red-600">${data.error || 'Ocorreu um erro desconhecido.'}</p>
                        ${data.details ? `<p class="text-sm text-gray-500 mt-2">Detalhe: ${data.details}</p>` : ''}
                    </div>
                `;
                document.getElementById('modal-fechar-btn').classList.remove('hidden'); 
            }
        })
        .catch(error => {
            // ERRO DE CONEXÃO OU FALHA AO ANALISAR JSON
            console.error('Erro de rede ou JSON inválido:', error);
            modalContent.innerHTML = `
                <div class="text-center p-8">
                    <svg class="w-16 h-16 text-red-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Erro de Comunicação</h3>
                    <p class="text-red-600">Não foi possível comunicar com o servidor.</p>
                    <p class="text-sm text-gray-500 mt-2">Por favor, verifique os logs do servidor (processatodafinalizacao.php).</p>
                </div>
            `;
            document.getElementById('modal-fechar-btn').classList.remove('hidden');
        });
    });
</script>

    <script src="js/global.js"></script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>