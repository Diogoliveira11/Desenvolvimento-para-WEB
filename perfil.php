<?php
session_start();
require 'dbconnection.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php"); 
    exit();
}

$idUtilizador = $_SESSION['iduser'];
$nomeUtilizador = "";
$emailUtilizador = "";
$erro = "";

$stmt = mysqli_prepare($link, "SELECT utilizador, email FROM utilizadores WHERE iduser = ?");
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
    header("Location: login.php?erro=perfil");
    exit();
}

// Variáveis para o header.php
$pageTitle = 'O Meu Perfil';
$pageSubtitle = 'Consulte todas as suas informações!';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
  <?php include 'header.php'; ?>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <main class="max-w-4xl mx-auto w-full flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8"> 

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-8 py-12 w-full"> 

      <div>
        <h2 class="text-2xl font-bold text-[#565656] border-b border-gray-200 pb-2 mb-4">
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
        <h2 class="text-2xl font-bold text-[#565656] border-b border-gray-200 pb-2 mb-4">
          Minhas Reservas
        </h2>
        <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg flex items-center">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
          </svg>
          <p class="text-sm">De momento, não tem nenhuma reserva ativa.</p>
        </div>
      </div>

      <div>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="alterarpass.php" class="w-full sm:w-auto text-center bg-[#c8c8b2] text-[#565656] font-bold py-2 px-5 rounded-lg hover:bg-[#565656] hover:text-white transition-all duration-300">
            Alterar Password
          </a>
          <a href="apagarconta.php" class="w-full sm:w-auto text-center bg-red-600 text-white font-bold py-2 px-5 rounded-lg hover:bg-red-700 transition-all duration-300">
            Apagar Conta
          </a>
        </div>
      </div>

    </div> 
  </main>

  <script src="js/global.js" defer></script>

  <?php include 'footer.php'; ?>

</body>
</html>