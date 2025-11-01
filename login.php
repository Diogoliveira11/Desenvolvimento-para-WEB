<?php
session_start();
include 'dbconnection.php';

$erro = "";

// ✅ MOSTRAR MENSAGEM DE SUCESSO APÓS REGISTO
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem_sucesso = "Conta criada com sucesso! Pode fazer login.";
}

// ✅ MOSTRAR MENSAGEM DE ERRO DO AUTENTICA.PHP
if (isset($_GET['erro']) && $_GET['erro'] == 1) {
    $erro = "Utilizador e/ou password incorretos!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilizador = trim($_POST['utilizador']);
    $pass = $_POST['pass'];
    
    // ✅ SEGURO - Prepared Statement
    $stmt = mysqli_prepare($link, "SELECT * FROM utilizadores WHERE utilizador = ?");
    mysqli_stmt_bind_param($stmt, "s", $utilizador);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($pass, $user['pass'])) {
            $_SESSION['iduser'] = $user['iduser'];
            $_SESSION['utilizador'] = $user['utilizador'];
            $_SESSION['logado'] = true;
            header("Location: paginainicial.php");
            exit();
        } else {
            // ✅ MENSAGEM GENÉRICA - Não diz se foi user ou pass
            $erro = "Utilizador e/ou password incorretos!";
        }
    } else {
        // ✅ MESMA MENSAGEM GENÉRICA
        $erro = "Utilizador e/ou password incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESPAÇO LUSITANO - LOGIN</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-white from-10% via-[#f5f5f0] via-50% to-[#c8c8b2] to-90% min-h-screen flex items-center justify-center p-4">
  
  <!-- Cartão Principal -->
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden">
    
    <!-- Header com Títulos e Botão da Casa -->
    <div class="bg-gradient-to-r from-[#d8d8c8] to-[#c8c8b2] p-8 text-center text-[#707070] relative">
      <!-- Botão da casa com cores suaves -->
      <a href="index.html" class="absolute top-4 right-4 w-10 h-10 bg-gradient-to-br from-[#a8a892] to-[#989882] rounded-full flex items-center justify-center hover:from-[#989882] hover:to-[#a8a892] transition duration-300 shadow-lg text-white">
        <i class="bi bi-house text-lg"></i>
      </a>
      
      <!-- Títulos do formulário no header -->
      <div class="mt-1">
        <h2 class="text-3xl font-bold text-[#707070]">Bem-vindo</h2>
        <p class="text-[#707070] opacity-80">Entre na sua conta para continuar</p>
      </div>
    </div>

    <!-- Formulário -->
    <div class="p-9">
      <?php if (isset($mensagem_sucesso)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
          <i class="bi bi-check-circle mr-3 text-xl"></i>
          <span><?php echo $mensagem_sucesso; ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($erro)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center">
          <i class="bi bi-shield-exclamation mr-3 text-xl"></i>
          <span><?php echo $erro; ?></span>
        </div>
      <?php endif; ?>

      <!-- ✅ CORREÇÃO: Mudar action para login.php -->
      <form method="POST" action="login.php" class="space-y-6">
        <!-- Campo Utilizador -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-person text-[#707070]"></i>
          </div>
          <input 
            type="text" 
            name="utilizador" 
            placeholder="Nome de utilizador" 
            required
            class="w-full pl-10 pr-4 py-3 border border-[#d8d8c8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300"
          >
        </div>

        <!-- Campo Password -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-lock text-[#707070]"></i>
          </div>
          <input 
            type="password" 
            name="pass" 
            placeholder="Password" 
            required
            class="w-full pl-10 pr-4 py-3 border border-[#d8d8c8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300"
          >
        </div>

        <!-- Botão Login (mais estreito) -->
        <div class="flex justify-center">
          <button 
            type="submit" 
            class="w-3/5 bg-[#707070] text-white font-bold py-3 rounded-xl hover:bg-[#c8c8b2] hover:text-[#707070] transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl text-sm"
          >
            <i class="bi bi-box-arrow-in-right mr-2"></i>
            ENTRAR NA CONTA
          </button>
        </div>
      </form>

      <!-- Divisor -->
      <div class="flex items-center my-6">
        <div class="flex-1 border-t border-[#d8d8c8]"></div>
        <span class="px-4 text-[#707070] text-sm">ou</span>
        <div class="flex-1 border-t border-[#d8d8c8]"></div>
      </div>

      <!-- Link para Registo (mais estreito) -->
      <div class="flex justify-center">
        <a href="signin.php" class="w-3/5 border-2 border-[#d8d8c8] text-[#707070] font-semibold py-2 rounded-xl hover:bg-[#c8c8b2] hover:text-[#707070] transition duration-300 text-sm text-center block">
          <i class="bi bi-person-plus mr-2"></i>
          CRIAR NOVA CONTA 
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div class="bg-[#e8e8dc] p-4 text-center">
      <p class="text-[#707070] text-sm">
        &copy; 2025 Espaço Lusitano · Encontre a sua próxima estadia
      </p>
    </div>
  </div>
</body>
</html>