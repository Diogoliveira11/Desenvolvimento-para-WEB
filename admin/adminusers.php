<?php
session_start();
require '../dbconnection.php'; 

// CONFIGURAÇÕES
$homeLink = "admin.php"; 
$tabela_users = "utilizadores"; 
$coluna_id = "id_user";
$coluna_nome = "utilizador"; 
$coluna_email = "email";
$coluna_estado = "estado";

$error_message = "";
$success_message = "";

// 1. PROCESSAMENTO: CRIAR NOVO UTILIZADOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_user') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $estado = 1; 

    $sql_create = "INSERT INTO $tabela_users ($coluna_nome, $coluna_email, pass, $coluna_estado) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql_create);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $pass, $estado);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=created");
            exit();
        } else {
            $error_message = "Erro ao executar criação: " . mysqli_stmt_error($stmt);
        }
    }
}

// 2. PROCESSAMENTO: ATUALIZAR DADOS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $id = (int)$_POST['user_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nova_pass = $_POST['pass'];

    if (!empty($nova_pass)) {
        $pass_hash = password_hash($nova_pass, PASSWORD_DEFAULT);
        $sql_edit = "UPDATE $tabela_users SET $coluna_nome = ?, $coluna_email = ?, pass = ? WHERE $coluna_id = ?";
        $stmt = mysqli_prepare($link, $sql_edit);
        mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $pass_hash, $id);
    } else {
        $sql_edit = "UPDATE $tabela_users SET $coluna_nome = ?, $coluna_email = ? WHERE $coluna_id = ?";
        $stmt = mysqli_prepare($link, $sql_edit);
        mysqli_stmt_bind_param($stmt, "ssi", $nome, $email, $id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=updated");
        exit();
    } else {
        $error_message = "Erro ao atualizar: " . mysqli_error($link);
    }
}

// 3. PROCESSAMENTO: BLOQUEAR / ATIVAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $user_id = (int)$_POST['user_id'];
    $novo_estado = (int)$_POST['new_status'];
    
    $sql_update = "UPDATE $tabela_users SET $coluna_estado = ? WHERE $coluna_id = ?";
    $stmt = mysqli_prepare($link, $sql_update);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $novo_estado, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit();
}

// 4. CONSULTA DA LISTA
$query = "SELECT $coluna_id, $coluna_nome, $coluna_email, $coluna_estado FROM $tabela_users ORDER BY $coluna_nome ASC";
$result = mysqli_query($link, $query);
$users = ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
$num_users = count($users);

// Mensagens de feedback
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'updated') $success_message = "Utilizador atualizado com sucesso!";
    if ($_GET['msg'] == 'created') $success_message = "Novo utilizador criado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GERIR UTILIZADORES | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="../imagens/FAVICON.ico">
    <script src="https://cdn.tailwindcss.com"></script> 
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">

<header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">

      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="<?php echo $homeLink; ?>"> <img src="../imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-[#565656]">
            Gestão de utilizadores
          </h1>
        </div>
      </div>
    </div>
 </header>

<nav class="h-2 bg-[#c8c8b2]"></nav>

<main class="w-full px-8 mt-10 flex-grow mb-12">
    <div class="mx-auto max-w-7xl">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 border-b-2 border-[#c8c8b2] pb-4">
            <h2 class="text-3xl font-bold text-[#565656]">
                Utilizadores registados (<span id="total-users-count"><?php echo $num_users; ?></span>)
            </h2>
            <button onclick="openCreateModal()"class="flex items-center justify-center bg-[#565656] hover:bg-[#454545] text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 whitespace-nowrap">
                <span class="text-xl mr-2">+</span> Adicionar Utilizador
            </button>
        </div>

        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto bg-white shadow-2xl rounded-xl border-[#c8c8b2]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#e5e5dd]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#565656] uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#565656] uppercase">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#565656] uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#565656] uppercase">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-[#565656] uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): 
                        $is_active = ($user[$coluna_estado] == 1); 
                    ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user[$coluna_id]); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($user[$coluna_nome]); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($user[$coluna_email]); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $is_active ? 'Ativo' : 'Bloqueado'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-3">
                            <button onclick="openEditModal('<?php echo $user[$coluna_id]; ?>', '<?php echo addslashes($user[$coluna_nome]); ?>', '<?php echo addslashes($user[$coluna_email]); ?>')" 
                               class="bg-[#565656] text-white font-semibold py-1 px-3 rounded-md hover:bg-[#454545] transition duration-150">
                                Editar
                            </button>
                            
                            <form method="POST" id="form-status-<?php echo $user[$coluna_id]; ?>" class="inline-block">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="user_id" value="<?php echo $user[$coluna_id]; ?>">
                                <input type="hidden" name="new_status" value="<?php echo $is_active ? 0 : 1; ?>"> 
                                
                                <button type="button" 
                                        onclick="askConfirmStatus('<?php echo $user[$coluna_id]; ?>', '<?php echo $is_active ? 'BLOQUEAR' : 'ATIVAR'; ?>', '<?php echo addslashes($user[$coluna_nome]); ?>')"
                                        class="<?php echo $is_active ? 'bg-red-500 hover:bg-red-700' : 'bg-green-600 hover:bg-green-800'; ?> text-white font-semibold py-1 px-3 rounded-md transition duration-150">
                                    <?php echo $is_active ? 'Bloquear' : 'Ativar'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main> 

<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden p-8">
        <h3 id="confirmTitle" class="text-2xl font-bold text-[#565656] mb-4">Confirmação de ação</h3>
        <p id="confirmMessage" class="text-gray-600 mb-8 text-lg">Tem certeza que deseja realizar esta ação?</p>
        
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="closeConfirmModal()" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button type="button" id="confirmBtnSubmit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
                Sim, confirmar
            </button>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-[#e5e5dd] p-4 flex justify-between items-center border-b border-[#c8c8b2]">
            <h3 class="text-xl font-bold text-[#565656]">Editar utilizador</h3>
            <button onclick="closeModal('editModal')" class="text-[#565656] hover:text-red-500 text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" class="px-6 pb-6 pt-2  space-y-4">
            <input type="hidden" name="action" value="update_user">
            <input type="hidden" name="user_id" id="modal_user_id">
            <div>
                <label class="block text-sm font-semibold text-[#565656] mb-1">Nome</label>
                <input type="text" name="nome" id="modal_nome" required class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-brand-dark">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#565656] mb-1">Email</label>
                <input type="email" name="email" id="modal_email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-brand-dark">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#565656] mb-1">Nova Palavra-passe (deixe em branco para manter a atual)</label>
                <input type="password" name="pass" id="modal_pass" class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-brand-dark" placeholder="********">
            </div>
            <div class="pt-4 flex space-x-3">
                <button type="button" onclick="closeModal('editModal')" class="flex-1 border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                <button type="submit" class="flex-1 bg-[#565656] text-white py-2 rounded-lg hover-brand-dark transition font-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-[#e5e5dd] p-4 flex justify-between items-center border-b border-[#c8c8b2]">
            <h3 class="text-xl font-bold text-[#565656]">Adicionar novo utilizador</h3>
            <button onclick="closeModal('createModal')" class="text-[#565656] hover:text-red-500 text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" class="px-6 pb-6 pt-2 space-y-4">
            <input type="hidden" name="action" value="create_user">
            <div>
                <label class="block text-sm font-semibold text-[#565656] mb-1">Nome de utilizador</label>
                <input type="text" name="nome" required class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#565656] mb-1">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>   
                <label class="block text-sm font-semibold text-[#565656] mb-1">Palavra-passe</label>
                <input type="password" name="pass" required class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div class="pt-4 flex space-x-3">
                <button type="button" onclick="closeModal('createModal')" class="flex-1 border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                <button type="submit" class="flex-1 bg-[#565656] text-white py-2 rounded-lg hover-brand-dark transition font-bold">Criar Conta</button>
            </div>
        </form>
    </div>
</div>

<footer class="py-4 text-center text-gray-700 bg-[#e5e5dd] mt-auto">
    <p class="text-sm">&copy; <?php echo date('Y'); ?> - ADMINISTRAÇÃO | ESPAÇO LUSITANO</p>
</footer>

<script>
let currentFormId = null;

// FUNÇÕES DOS MODAIS DE EDIÇÃO E CRIAÇÃO
function openEditModal(id, nome, email) {
    document.getElementById('modal_user_id').value = id;
    document.getElementById('modal_nome').value = nome;
    document.getElementById('modal_email').value = email;
    document.getElementById('modal_pass').value = '';
    document.getElementById('editModal').classList.remove('hidden');
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// FUNÇÕES DO MODAL DE CONFIRMAÇÃO (BLOQUEAR/ATIVAR)
function askConfirmStatus(userId, acao, nome) {
    currentFormId = 'form-status-' + userId;
    
    // Personaliza o texto do modal
    document.getElementById('confirmTitle').innerText = 'Confirmação de ação: ' + acao;
    document.getElementById('confirmMessage').innerText = 'Tem certeza que deseja ' + acao.toLowerCase() + ' o utilizador: ' + nome + '?';
    
    // Ajusta a cor do botão de confirmação com base na ação
    const btnSubmit = document.getElementById('confirmBtnSubmit');
    if (acao === 'BLOQUEAR') {
        // Remove cores de ativar e coloca cores de bloquear
        btnSubmit.classList.remove('bg-green-600', 'hover:bg-green-800');
        btnSubmit.classList.add('bg-red-600', 'hover:bg-red-700');
        btnSubmit.innerText = 'Sim, bloquear';
    } else {
        // Remove cores de bloquear e coloca cores de ativar
        btnSubmit.classList.remove('bg-red-600', 'hover:bg-red-700');
        btnSubmit.classList.add('bg-green-600', 'hover:bg-green-800');
        btnSubmit.innerText = 'Sim, ativar';
    }

    // Mostra o modal
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    currentFormId = null;
}

// Evento para o botão "Sim" do modal de confirmação
document.getElementById('confirmBtnSubmit').addEventListener('click', function() {
    if (currentFormId) {
        document.getElementById(currentFormId).submit();
    }
});

// Fechar modais ao clicar fora da caixa branca
window.onclick = function(event) {
    const editModal = document.getElementById('editModal');
    const createModal = document.getElementById('createModal');
    const confirmModal = document.getElementById('confirmModal');

    if (event.target == editModal) closeModal('editModal');
    if (event.target == createModal) closeModal('createModal');
    if (event.target == confirmModal) closeConfirmModal();
}

function atualizarContadorUsers() {
    fetch('totalusers.php')
        .then(response => response.json())
        .then(data => {
            const el = document.getElementById('total-users-count');
            if (el) {
                el.textContent = data.total;
            }
        })
        .catch(error => console.error('Erro ao atualizar contador de utilizadores:', error));
}
</script>

</body>
</html>