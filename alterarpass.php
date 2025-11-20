<?php
session_start();
require 'dbconnection.php'; 

// =========================================================================
// 1. SEGURANÇA: Verifica se o utilizador está logado
// =========================================================================
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

$idUtilizador = $_SESSION['iduser'];
// NOVO: Obtém o nome de utilizador da sessão
$nomeUtilizador = $_SESSION['utilizador']; 
$erro = "";
$mensagem_sucesso = "";

// Variáveis para o header.php
$pageTitle = 'Alterar Password';
$pageSubtitle = 'Mantenha a sua conta segura.';


// =========================================================================
// 2. PROCESSAMENTO DO FORMULÁRIO
// =========================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $passwordAtual = $_POST['password_atual'];
    $novaPassword = $_POST['nova_password'];
    $confirmarPassword = $_POST['confirmar_password'];

    // 2.1. Verifica se as novas passwords coincidem
    if ($novaPassword !== $confirmarPassword) {
        $erro = "A nova password e a confirmação não coincidem!";
    } 
    // 2.2. Verifica se a nova password é suficientemente longa
    elseif (strlen($novaPassword) < 8) {
        $erro = "A nova password deve ter pelo menos 8 caracteres!";
    }
    else {
        
        // 2.3. Busca o hash da password atual na base de dados
        $stmt = mysqli_prepare($link, "SELECT pass FROM utilizadores WHERE iduser = ?");
        mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $hashArmazenado = $user['pass'];
            
            // 2.4. Verifica se a password atual está correta
            if (password_verify($passwordAtual, $hashArmazenado)) {
                
                // 2.5. Gera novo hash e atualiza a BD
                $novaPasswordHash = password_hash($novaPassword, PASSWORD_DEFAULT);
                
                $stmt_update = mysqli_prepare($link, "UPDATE utilizadores SET pass = ? WHERE iduser = ?");
                mysqli_stmt_bind_param($stmt_update, "si", $novaPasswordHash, $idUtilizador);

                if (mysqli_stmt_execute($stmt_update)) {
                    $mensagem_sucesso = "Password alterada com sucesso!";
                } else {
                    $erro = "Erro ao atualizar a password na base de dados.";
                }
                mysqli_stmt_close($stmt_update);

            } else {
                $erro = "A password atual está incorreta!";
            }

        } else {
            // Este caso só deve ocorrer se houver um erro de sessão grave
            $erro = "Erro interno: Utilizador não encontrado.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alterar Password - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
  <?php include 'header.php'; ?>
  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <main class="flex-grow w-full flex items-center justify-center p-4"> 
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-6 max-w-lg mx-auto"> 
            
            <h2 class="text-2xl font-bold text-[#565656] border-b border-gray-200 pb-2 mb-4">
              Alterar Password
            </h2>

            <div class="mb-4 pt-2 pb-3 border-b border-gray-200">
                <p class="text-sm font-medium text-gray-500">A alterar a password de:</p>
                <p class="text-lg font-semibold text-[#565656]"><?php echo htmlspecialchars($nomeUtilizador); ?></p>
            </div>


            <?php if (!empty($mensagem_sucesso)): ?>
              <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg text-sm">
                <p><?php echo $mensagem_sucesso; ?></p>
              </div>
            <?php endif; ?>

            <?php if (!empty($erro)): ?>
              <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm">
                <p><?php echo $erro; ?></p>
              </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
              
              <div class="relative">
                <label for="password_atual" class="block text-sm font-medium text-gray-700">Password atual</label> 
                <input type="password" name="password_atual" id="password_atual" required 
                       class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300 text-sm"> 
              </div>
              
              <div class="relative">
                <label for="nova_password" class="block text-sm font-medium text-gray-700">Nova password (mín. 8 caracteres)</label> 
                <input type="password" name="nova_password" id="nova_password" required 
                       class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300 text-sm">
              </div>
              
              <div class="relative">
                <label for="confirmar_password" class="block text-sm font-medium text-gray-700">Confirmar nova password</label> 
                <input type="password" name="confirmar_password" id="confirmar_password" required 
                       class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300 text-sm">
              </div>
              
              <div class="flex justify-between space-x-4 pt-4">
                  
                  <a href="perfil.php" class="w-1/2 text-center bg-gray-200 text-gray-700 font-medium py-2 px-5 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm flex items-center justify-center">
                      Cancelar
                  </a>

                  <button type="submit" class="w-1/2 bg-[#c8c8b2] text-[#565656] font-medium py-2 px-5 rounded-lg hover:bg-[#565656] hover:text-white transition-all duration-300 text-sm flex items-center justify-center">
                      Atualizar
                  </button>
              </div>
              
            </form>
            
        </div>
    </div>
  </main>

  <script src="js/global.js" defer></script>
  <?php include 'footer.php'; ?>

</body>
</html>