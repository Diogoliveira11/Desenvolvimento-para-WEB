<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

$idUtilizador = $_SESSION['id_user'];
$nomeUtilizador = $_SESSION['utilizador'];
$erro = "";
$pageTitle = 'Apagar Conta';
$pageSubtitle = 'Confirmação de Segurança.';


// 2. PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $password = $_POST['password_confirmacao'];

    // 2.1. Busca o hash da password atual na base de dados
    $stmt = mysqli_prepare($link, "SELECT pass FROM utilizadores WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $hashArmazenado = $user['pass'];
        
        // 2.2. Verifica se a password fornecida está correta
        if (password_verify($password, $hashArmazenado)) {
            
            // 2.3. EXECUTA A ELIMINAÇÃO DA CONTA
            $stmt_delete = mysqli_prepare($link, "DELETE FROM utilizadores WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $idUtilizador);

            if (mysqli_stmt_execute($stmt_delete)) {
                
                // 2.4. Destroi a sessão e redireciona para a página inicial
                session_unset();
                session_destroy();
                header("Location: index.php?sucesso=conta_apagada");
                exit();
                
            } else {
                $erro = "Erro ao tentar apagar a conta na base de dados.";
            }
            mysqli_stmt_close($stmt_delete);

        } else {
            $erro = "A password fornecida está incorreta.";
        }

    } else {
        $erro = "Utilizador não encontrado. Ação bloqueada.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APAGAR CONTA - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-white from-10% via-[#f5f5f0] via-50% to-[#c8c8b2] to-90% min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-red-600 to-red-700 p-8 text-center text-white relative">
            
            <div class="mt-1">
                <h2 class="text-3xl font-bold">Apagar Conta</h2>
                <p class="opacity-80">Confirmação de segurança</p>
            </div>
        </div>

        <div class="p-9">
            
            <div class="mb-6 pb-3 border-b border-gray-200">
                <p class="text-lg font-bold text-red-600">Atenção! Esta ação é irreversível.</p>
                <p class="text-sm font-medium text-gray-500 mt-2">Confirme a sua password para eliminar permanentemente a conta <?php echo htmlspecialchars($nomeUtilizador); ?>.</p>
            </div>

            <?php if (!empty($erro)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center text-sm">
                <span><?php echo $erro; ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
                
                <div class="relative">
                    <label for="password_confirmacao" class="block text-sm font-medium text-gray-700">Password</label> 
                    <input type="password" name="password_confirmacao" id="password_confirmacao" placeholder="Introduza a sua password" required 
                           class="mt-1 w-full pl-3 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 text-base">
                </div>
                
                <div class="flex justify-between space-x-4 pt-4">
                    
                    <a href="perfil.php" class="w-1/2 text-center bg-gray-200 text-gray-700 font-medium py-3 rounded-xl hover:bg-gray-300 transition-all duration-300 text-base flex items-center justify-center">
                        Manter Conta
                    </a>

                    <button type="submit" class="w-1/2 bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl text-sm flex items-center justify-center">
                        ELIMINAR PERMANENTEMENTE
                    </button>
                </div>
                
            </form>
            
        </div>
        
        <div class="bg-[#e8e8dc] p-4 text-center">
            <p class="text-[#707070] text-sm">
                &copy; 2025 Espaço Lusitano · Decisão irreversível
            </p>
        </div>
    </div>
</body>
</html>