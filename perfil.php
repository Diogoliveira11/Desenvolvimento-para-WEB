<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: auth/login.php"); 
    exit();
}

$idUtilizador = $_SESSION['id_user'];
$nomeUtilizador = "";
$emailUtilizador = "";
$erro = "";

// 1. BUSCA DE DADOS BÁSICOS DO PERFIL
$stmt = mysqli_prepare($link, "SELECT utilizador, email FROM utilizadores WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $nomeUtilizador = htmlspecialchars($user['utilizador']);
    $emailUtilizador = htmlspecialchars($user['email']);
} else {
    $erro = "Não foi possível carregar os dados do perfil.";
    session_destroy();
    header("Location: auth/login.php?erro=perfil");
    exit();
}
mysqli_stmt_close($stmt);

// 2. BUSCA DE RESERVAS DO UTILIZADOR
$query_reservas = "
    SELECT 
        R.id_reserva, R.id_alojamento, R.data_check_in, R.data_check_out, R.preco_total, R.num_quartos, R.estado,
        A.nome AS nome_alojamento, A.localizacao, A.check_in_out,
        (SELECT COUNT(*) FROM avaliacoes WHERE id_reserva = R.id_reserva) AS ja_avaliado
    FROM 
        reservas AS R
    INNER JOIN 
        alojamento AS A ON R.id_alojamento = A.id_alojamento
    WHERE 
        R.id_utilizador = $idUtilizador
    ORDER BY 
        R.data_check_out DESC, R.data_check_in DESC
";
$result_reservas = mysqli_query($link, $query_reservas);
$minhas_reservas = $result_reservas ? mysqli_fetch_all($result_reservas, MYSQLI_ASSOC) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'desativar_conta') {
    $password = $_POST['password_confirmacao'];
    
    $stmt = mysqli_prepare($link, "SELECT pass FROM utilizadores WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['pass'])) {
        $stmt_update = mysqli_prepare($link, "UPDATE utilizadores SET estado = 0 WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt_update, "i", $idUtilizador);

        if (mysqli_stmt_execute($stmt_update)) {
            session_unset();
            session_destroy();
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro na base de dados.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'A password está incorreta.']);
        exit();
    }
}

// 3. PROCESSAMENTO DE ALTERAÇÃO DE PASSWORD
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'alterar_password') {
        $passwordAtual = $_POST['password_atual'];
        $novaPassword = $_POST['nova_password'];
        $confirmarPassword = $_POST['confirmar_password'];

    if ($novaPassword !== $confirmarPassword) {
        echo json_encode(['success' => false, 'message' => 'A nova password e a confirmação não coincidem!']);
        exit();
    }

    $stmt = mysqli_prepare($link, "SELECT pass FROM utilizadores WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($passwordAtual, $user['pass'])) {
        $novaPasswordHash = password_hash($novaPassword, PASSWORD_DEFAULT);
        $stmt_update = mysqli_prepare($link, "UPDATE utilizadores SET pass = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt_update, "si", $novaPasswordHash, $idUtilizador);

        if (mysqli_stmt_execute($stmt_update)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a base de dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'A password atual está incorreta!']);
    }
    exit();
}

$pageTitle = 'O meu perfil';
$pageSubtitle = 'Consulte todas as suas informações!';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERFIL | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet">
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'cor-principal': '#565656', 
              'cor-secundaria': '#c8c8b2',
            }
          }
        }
      }
    </script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    <?php include 'includes/header.php'; ?>

    <nav class="h-2 bg-cor-secundaria"></nav>

    <main class="max-w-4xl mx-auto w-full flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 mb-3"> 

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-8 py-12 w-full"> 

            <div>
                <h2 class="text-2xl font-bold text-cor-principal border-b border-gray-200 pb-2 mb-4">
                    Dados Pessoais
                </h2>
                <form class="space-y-4">
                    <div>
                        <label for="utilizador" class="block text-sm font-medium text-gray-700">Nome de Utilizador</label>
                        <input type="text" name="utilizador" id="utilizador" readonly 
                               class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" 
                               value="<?php echo $nomeUtilizador; ?>">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" readonly 
                               class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" 
                               value="<?php echo $emailUtilizador; ?>">
                    </div>
                </form>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-cor-principal border-b border-gray-200 pb-2 mb-4">
                    Minhas reservas
                </h2>
                
                <?php if (empty($minhas_reservas)): ?>
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm">De momento, não tem nenhuma reserva ativa.</p>
                    </div>
                <?php else: ?>
                    
                    <div class="space-y-4">
                        <?php foreach ($minhas_reservas as $reserva): 
                            
                            $num_quartos = $reserva['num_quartos'];
                            $check_in_dt = new DateTime($reserva['data_check_in']);
                            $check_out_dt = new DateTime($reserva['data_check_out']);
                            $num_noites = $check_in_dt->diff($check_out_dt)->days;

                            $texto_quartos = $num_quartos == 1 ? 'quarto' : 'quartos';
                            $texto_noites = $num_noites == 1 ? 'noite' : 'noites';

                            $check_in = $check_in_dt->format('d/m/Y');
                            $check_out = $check_out_dt->format('d/m/Y');
                            $preco = number_format($reserva['preco_total'], 2, ',', '.');
                            
                            $hoje_agora = new DateTime('now');
                            $hoje_data = new DateTime(date('Y-m-d'));
                            
                            $ci_co_times = explode('/', $reserva['check_in_out'] ?? '15:00/11:00');
                            $hora_check_out_str = trim($ci_co_times[1] ?? '11:00');
                            $hora_check_out_str = str_replace(['h', 'H'], ':', $hora_check_out_str);
                            
                            $checkout_final_dt = new DateTime($reserva['data_check_out'] . ' ' . $hora_check_out_str);

                            // Lógica de Cancelamento
                            $data_limite_cancelamento = (clone $check_in_dt)->modify('-2 days');

                            $pode_cancelar = $reserva['estado'] == 'Confirmada' && $data_limite_cancelamento > $hoje_agora;
                            
                            // Lógica de Atividade/Avaliação
                            $reserva_futura = $check_in_dt > $hoje_data;
                            $reserva_ativa = $check_in_dt <= $hoje_data && $checkout_final_dt > $hoje_agora;
                            $reserva_concluida = $checkout_final_dt <= $hoje_agora;

                            $ja_avaliado = $reserva['ja_avaliado'] > 0; 

                            $estado_cor = '';
                            if ($reserva['estado'] == 'Confirmada') {
                                $estado_cor = 'bg-green-100 text-green-800 border-green-400';
                            } elseif ($reserva['estado'] == 'Cancelada') {
                                $estado_cor = 'bg-red-100 text-red-800 border-red-400';
                            } else {
                                $estado_cor = 'bg-gray-100 text-gray-800 border-gray-400';
                            }
                        ?>
                        <div class="border border-gray-200 p-4 rounded-lg shadow-sm bg-gray-50">
                            
                            <div class="flex justify-between items-start mb-2">
                                
                                <div class="flex flex-col flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        <?php echo htmlspecialchars($reserva['nome_alojamento']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($reserva['localizacao']); ?></p>
                                </div>

                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    
                                    
                                    <?php if ($pode_cancelar): ?>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo $estado_cor; ?>">
                                                <?php echo htmlspecialchars($reserva['estado']); ?>
                                            </span>
                                            <button 
                                                type="button"
                                                class="cancelar-reserva-btn inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg shadow-md 
                                                    text-white bg-red-600 hover:bg-red-700 transition-all duration-300"
                                                data-id-reserva="<?php echo $reserva['id_reserva']; ?>"
                                                data-nome-alojamento="<?php echo htmlspecialchars($reserva['nome_alojamento']); ?>"
                                            >
                                                Cancelar Reserva
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo $estado_cor; ?>">
                                            <?php echo htmlspecialchars($reserva['estado']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-y-1 text-sm mt-4">
                                <p><span class="font-medium">Check-in:</span> <?php echo $check_in; ?></p>
                                <p><span class="font-medium">Total:</span> €<?php echo $preco; ?></p>
                                <p><span class="font-medium">Check-out:</span> <?php echo $check_out; ?></p>
                                <p><span class="font-medium">Quartos:</span> <?php echo $num_quartos; ?> <?php echo $texto_quartos; ?></p>
                                <p><span class="font-medium">Noites:</span> <?php echo $num_noites; ?> <?php echo $texto_noites; ?></p>
                            </div>
                            <div class="mt-3 pt-3 border-t flex flex-wrap gap-2 items-center">
                                
                                
                                <?php if ($reserva['estado'] == 'Cancelada'): ?>
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg text-gray-700 bg-gray-100 border border-gray-200">
                                        Não é possível avaliar, pois a reserva foi cancelada.
                                    </span>

                                <?php elseif ($reserva_concluida): ?>
                                    <?php if ($ja_avaliado): ?>
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg text-green-700 bg-green-100 border border-green-200">
                                            Já avaliado
                                        </span>
                                    <?php else: ?>
                                        <button 
                                            type="button"
                                            class="abrir-modal-avaliacao inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg shadow-md 
                                                    text-cor-principal bg-cor-secundaria hover:bg-cor-principal hover:text-white transition-all duration-300"
                                            data-id-alojamento="<?php echo $reserva['id_alojamento']; ?>"
                                            data-id-reserva="<?php echo $reserva['id_reserva']; ?>">
                                            Avaliar Estadia
                                        </button>
                                    <?php endif; ?>
                                
                                <?php elseif ($reserva_ativa): ?>
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg text-yellow-700 bg-yellow-100 border border-yellow-200">
                                        Ativo (Hospedando)
                                    </span>
                                <?php elseif ($reserva_futura): ?>
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg text-gray-700 bg-gray-100 border border-gray-200">
                                        Disponível para avaliação após <?php echo $checkout_final_dt->format('d/m/Y \à\s H:i'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="button" onclick="openPassModal()" class="w-full sm:w-auto text-center bg-cor-secundaria text-cor-principal font-bold py-2 px-5 rounded-lg hover:bg-cor-principal hover:text-white transition-all duration-300">
                        Alterar Password
                    </button>
                    <button type="button" onclick="openDesativarModal()" class="w-full sm:w-auto text-center bg-red-600 text-white font-bold py-2 px-5 rounded-lg hover:bg-red-700 transition-all duration-300">
                        Desativar Conta
                    </button>
                    <a href="auth/logout.php" class="w-full sm:w-auto text-center bg-gray-500 text-white font-bold py-2 px-5 rounded-lg hover:bg-gray-600 transition-all duration-300">
                        SAIR
                    </a>
                </div>
            </div>

        </div> 
    </main>
    
    <div id="sucesso-popup" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 hidden opacity-0 transition-opacity duration-300">
        <div class="bg-green-600 text-white p-4 rounded-lg shadow-xl flex items-center space-x-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold"></span>
        </div>
    </div>
    
    <div id="modal-confirmacao-cancelamento" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0" id="modal-cancelamento-content">
            
            <div class="p-5 flex items-center justify-center border-b border-red-200">
                <svg class="w-8 h-8 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.308 17c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="text-xl font-bold text-red-700">Confirmar Cancelamento</h3>
            </div>
            
            <div class="p-6 text-center"> 
                <p class="text-gray-700 mb-4" id="mensagem-cancelamento"></p>
                <p class="text-sm font-semibold text-red-500 mb-6">Esta ação é irreversível.</p>
                
                <div class="flex justify-center space-x-4">
                    <button id="btn-confirmar-cancelamento" 
                            data-id-reserva="" 
                            class="py-2 px-5 text-sm font-bold rounded-lg shadow-md 
                                   text-white bg-red-600 hover:bg-red-700 transition-colors duration-300">
                        SIM, CANCELAR
                    </button>
                    <button id="btn-fechar-cancelamento" 
                            class="py-2 px-5 text-sm font-bold rounded-lg shadow-md 
                                   text-cor-principal bg-gray-200 hover:bg-gray-300 transition-colors duration-300">
                        Voltar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="modal-avaliacao" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-bold text-cor-principal">
                    Avaliar: <span id="alojamento-nome-modal" class="text-cor-principal font-extrabold text-2xl"></span>
                </h3>
                <button id="fechar-modal" class="text-gray-500 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
            </div>
            
            <div id="modal-body-form" class="p-4"> 
                <div class="text-center py-10 text-gray-500">A carregar formulário...</div>
            </div>
        </div>
    </div>
    <div id="modal-desativar-conta" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-[60] p-4 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modal-desativar-content">
        
        <div class="bg-red-600 p-6 text-center text-white rounded-t-2xl">
            <h3 class="text-2xl font-bold">Desativar conta</h3>
        </div>
        
        <form id="form-desativar-conta" class="p-8">
            <p class="text-gray-700 mb-6 text-center">
                Tem certeza que deseja desativar a conta <strong><?php echo $nomeUtilizador; ?></strong>?<br>
                Introduza a sua password para confirmar.
            </p>

            <div id="erro-desativar" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4 text-sm"></div>

            <div class="mb-6 text-left">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="pass_confirm" name="password_confirmacao" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition-all"
                       placeholder="Sua password atual">
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 shadow-lg transition-all">
                    DESATIVAR
                </button>
                <button type="button" onclick="closeDesativarModal()" class="w-full bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition-all">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-alterar-pass" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-[60] p-4 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modal-pass-content">
        
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-2xl font-bold text-cor-principal">Alterar Password</h3>
        </div>
        
        <form id="form-alterar-pass" class="p-8 space-y-4">
            <div class="pb-3 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-500">A alterar a password de:</p>
                <p class="text-lg font-semibold text-cor-principal"><?php echo $nomeUtilizador; ?></p>
            </div>

            <div id="erro-pass" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded text-sm"></div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password atual</label>
                <input type="password" name="password_atual" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cor-secundaria outline-none transition-all text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nova password</label>
                <input type="password" name="nova_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cor-secundaria outline-none transition-all text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar nova password</label>
                <input type="password" name="confirmar_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cor-secundaria outline-none transition-all text-sm">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closePassModal()" class="flex-1 bg-gray-200 text-gray-700 font-bold py-2 rounded-lg hover:bg-gray-300 transition-all">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-cor-secundaria text-cor-principal font-bold py-2 rounded-lg hover:bg-cor-principal hover:text-white transition-all shadow-md">
                    Atualizar
                </button>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
    <script src="js/global.js" defer></script> 
    
    <script>
        let quillModal = null;
        let isQuillInitialized = false;

        // Função para exibir o popup de sucesso
        function showSuccessPopup(message) {
            const popup = document.getElementById('sucesso-popup');
            const span = popup.querySelector('span');
            
            span.textContent = message;
            popup.classList.remove('hidden', 'opacity-0');
            popup.classList.add('flex'); 

            requestAnimationFrame(() => {
                popup.classList.add('opacity-100');
            });

            setTimeout(() => {
                popup.classList.remove('opacity-100');
                setTimeout(() => {
                    popup.classList.add('hidden');
                    popup.classList.remove('flex');
                }, 300);
            }, 3000); 
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-avaliacao');
            const modalContent = document.getElementById('modal-content');
            const modalBodyForm = document.getElementById('modal-body-form');
            const fecharModalBtn = document.getElementById('fechar-modal');
            const abrirModalBtns = document.querySelectorAll('.abrir-modal-avaliacao');
            
            // Variáveis e funções para o Modal de Avaliação
            function showModal() {
                modal.classList.remove('hidden', 'opacity-0');
                modal.classList.add('flex', 'opacity-100');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function hideModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('flex', 'opacity-100');
                    modal.classList.add('hidden', 'opacity-0');
                    // Limpar e resetar o Quill após fechar
                    modalBodyForm.innerHTML = '<div class="text-center py-10 text-gray-500">A carregar formulário...</div>';
                    isQuillInitialized = false;
                    quillModal = null;
                }, 300);
            }

            fecharModalBtn.addEventListener('click', hideModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
            
            // LÓGICA DE CANCELAMENTO DE RESERVA
            const cancelarBtns = document.querySelectorAll('.cancelar-reserva-btn');
            const modalCancelamento = document.getElementById('modal-confirmacao-cancelamento');
            const modalCancelamentoContent = document.getElementById('modal-cancelamento-content');
            const mensagemCancelamento = document.getElementById('mensagem-cancelamento');
            const btnConfirmarCancelamento = document.getElementById('btn-confirmar-cancelamento');
            const btnFecharCancelamento = document.getElementById('btn-fechar-cancelamento');

            // Funções para o Modal de Cancelamento
            function showCancelModal(reservaId, nomeAlojamento) {
                mensagemCancelamento.textContent = `Tem certeza que deseja cancelar a reserva no alojamento "${nomeAlojamento}"?`;
                btnConfirmarCancelamento.setAttribute('data-id-reserva', reservaId);
                
                modalCancelamento.classList.remove('hidden', 'opacity-0');
                modalCancelamento.classList.add('flex', 'opacity-100');
                setTimeout(() => {
                    modalCancelamentoContent.classList.remove('scale-95', 'opacity-0');
                    modalCancelamentoContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function hideCancelModal() {
                modalCancelamentoContent.classList.remove('scale-100', 'opacity-100');
                modalCancelamentoContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modalCancelamento.classList.remove('flex', 'opacity-100');
                    modalCancelamento.classList.add('hidden', 'opacity-0');
                    btnConfirmarCancelamento.removeAttribute('data-id-reserva');
                }, 300);
            }

            // Eventos do Modal de Cancelamento
            btnFecharCancelamento.addEventListener('click', hideCancelModal);
            modalCancelamento.addEventListener('click', function(e) {
                if (e.target === modalCancelamento) {
                    hideCancelModal();
                }
            });

            cancelarBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const idReserva = this.getAttribute('data-id-reserva');
                    const nomeAlojamento = this.getAttribute('data-nome-alojamento');
                    
                    // Abrir modal personalizado
                    showCancelModal(idReserva, nomeAlojamento);
                });
            });

            btnConfirmarCancelamento.addEventListener('click', function() {
                const idReserva = this.getAttribute('data-id-reserva');
                
                if (!idReserva) return; 

                hideCancelModal();

                fetch('cancelarreserva.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_reserva=${idReserva}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessPopup('Reserva cancelada com sucesso!'); 
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000); 
                    } else {
                        alert('Erro ao cancelar a reserva: ' + (data.message || 'Erro desconhecido.'));
                    }
                })
                .catch(error => {
                    console.error('Erro de cancelamento:', error);
                    alert('Erro na comunicação com o servidor ao tentar cancelar.');
                });
            });

            // LÓGICA DE ABRIR MODAL DE AVALIAÇÃO
            abrirModalBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const idAlojamento = this.getAttribute('data-id-alojamento');
                    const idReserva = this.getAttribute('data-id-reserva');
                    
                    showModal();

                    // Carregar o formulário
                    fetch(`avaliaralojamento.php?id_alojamento=${idAlojamento}&id_reserva=${idReserva}&modal=true`)
                        .then(response => response.text())
                        .then(html => {
                            modalBodyForm.innerHTML = html;
                            initializeQuillAndForm(idAlojamento, idReserva);
                        })
                        .catch(error => {
                            console.error('Erro ao carregar formulário de avaliação:', error);
                            modalBodyForm.innerHTML = '<p class="text-red-500">Erro ao carregar o formulário. Tente novamente.</p>';
                        });
                });
            });

            // Funções de Inicialização do Formulário
            function initializeQuillAndForm(idAlojamento, idReserva) {
                const editorContainer = document.getElementById('editor-container');
                const form = document.getElementById('avaliacao-form-quill');
                const hiddenInput = document.getElementById('comentario-hidden-input');
                const alojamentoNomeModal = document.getElementById('alojamento-nome-modal');

                const nomeAlojamentoInput = form.querySelector('input[name="nome_alojamento_display"]');
                if (nomeAlojamentoInput) {
                    alojamentoNomeModal.textContent = nomeAlojamentoInput.value;
                }

                // Inicializar Quill
                if (editorContainer && !isQuillInitialized) {
                    quillModal = new Quill('#editor-container', {
                        theme: 'snow',
                        placeholder: 'Descreva a sua experiência aqui...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline', 'strike'], 
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['clean'] 
                            ]
                        }
                    });
                    isQuillInitialized = true;
                }

                // Lógica de Submissão AJAX do Formulário
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault(); 
                        
                        const content = quillModal.root.innerHTML;
                        const isContentEmpty = quillModal.getText().trim().length === 0;
                        const avaliacao = document.getElementById('avaliacao').value;

                        if (!avaliacao || avaliacao < 1 || avaliacao > 10) {
                             alert('Por favor, atribua uma nota de 1 a 10.');
                             return;
                        }

                        if (isContentEmpty) {
                            alert('O comentário é obrigatório. Por favor, escreva a sua avaliação.');
                            return;
                        } 
                        
                        hiddenInput.value = content;
                        const formData = new FormData(form);
                        
                        // Enviar via Fetch
                        fetch(form.action, {
                            method: form.method,
                            body: formData
                        })
                        .then(response => response.json()) 
                        .then(data => {
                            if (data.success) {
                                
                                hideModal();
                                
                                showSuccessPopup('Avaliação enviada com sucesso! Obrigado.');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 3000); 
                                
                            } else {
                                alert('Erro ao enviar a avaliação: ' + (data.message || 'Erro desconhecido'));
                            }
                        })
                        .catch(error => {
                            alert('Erro na comunicação com o servidor.');
                            console.error('Erro de submissão:', error);
                        });
                    });
                }
            }
        });

        function openDesativarModal() {
            const modal = document.getElementById('modal-desativar-conta');
            const content = document.getElementById('modal-desativar-content');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDesativarModal() {
            const modal = document.getElementById('modal-desativar-conta');
            const content = document.getElementById('modal-desativar-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('erro-desativar').classList.add('hidden');
                document.getElementById('form-desativar-conta').reset();
            }, 300);
        }

        document.getElementById('form-desativar-conta').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('pass_confirm').value;
            const erroDiv = document.getElementById('erro-desativar');

            const formData = new FormData();
            formData.append('action', 'desativar_conta');
            formData.append('password_confirmacao', password);

            fetch('perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php?sucesso=conta_desativada';
                } else {
                    erroDiv.textContent = data.message;
                    erroDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar o pedido.');
            });
        });

        function openPassModal() {
            const modal = document.getElementById('modal-alterar-pass');
            const content = document.getElementById('modal-pass-content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closePassModal() {
            const modal = document.getElementById('modal-alterar-pass');
            const content = document.getElementById('modal-pass-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('erro-pass').classList.add('hidden');
                document.getElementById('form-alterar-pass').reset();
            }, 300);
        }

        // Submissão do formulário de password
        document.getElementById('form-alterar-pass').addEventListener('submit', function(e) {
            e.preventDefault();
            const erroDiv = document.getElementById('erro-pass');
            const formData = new FormData(this);
            formData.append('action', 'alterar_password');

            fetch('perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closePassModal();
                    showSuccessPopup('Password alterada com sucesso!');
                } else {
                    erroDiv.textContent = data.message;
                    erroDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar o pedido.');
            });
        });

        // Fechar modais ao clicar fora
        window.addEventListener('click', function(e) {
            const modalPass = document.getElementById('modal-alterar-pass');
            const modalDesativar = document.getElementById('modal-desativar-conta');
            if (e.target === modalPass) closePassModal();
            if (e.target === modalDesativar) closeDesativarModal();
        });
        
    </script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>