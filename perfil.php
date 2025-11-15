<?php
session_start();
require 'dbconnection.php';

// 1. SEGURANÇA: Verifica se o utilizador está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php"); // Redireciona se não estiver
    exit();
}

// 2. BUSCAR DADOS: Vai à BD buscar os dados do utilizador logado
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

<body class="bg-gray-50 font-sans">

  <header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">

      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="paginainicial.php">
          <img src="imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-[#565656]">
            O Meu Perfil
          </h1>
          <p class="hidden sm:block text-xs md:text-base lg:text-xl text-[#707070]">
            Consulte todas as suas informações!
          </p>
        </div>
      </div>

      <div class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-shrink-0">

        <div class="relative">
          <button id="profile-btn-desktop" class="flex items-center justify-center w-7 h-7 lg:w-8 lg:h-8 xl:w-10 xl:h-10 bg-[#c8c8b2] text-[#565656] rounded-full hover:bg-[#565656] hover:text-white transition-all duration-300">
            <svg class="w-4 h-4 lg:w-5 lg:h-5 xl:w-6 xl:h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </button>

          <div id="profile-dropdown-desktop" class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
            <div class="py-2">

              <a href="perfil.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
                <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="font-medium"><?php echo htmlspecialchars($_SESSION['utilizador']); ?></span>
              </a>

              <div class="border-t border-gray-100 my-1"></div>
              <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
                <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3H7.5A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l4.5 4.5m0 0L12 18m4.5-4.5H5.25" />
                </svg>
                LOGOUT
              </a>
            </div>
          </div>
        </div>

        <a href="suporte.php" class="flex items-center justify-center w-7 h-7 lg:w-8 lg:h-8 xl:w-10 xl:h-10 cursor-pointer hover:-translate-y-0.5 transition-transform duration-300">
          <img src="imagens/SUPORTE.png" class="w-full h-full object-contain" alt="Suporte">
        </a>
      </div>

      <div class="md:hidden flex items-center ml-1 flex-shrink-0">
        <button id="menu-btn" class="text-[#565656] focus:outline-none">
          <svg class="w-7 h-7 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path> 
          </svg>
        </button>
      </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-16 right-4 sm:right-6 lg:right-8 w-44 sm:w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
      <div class="py-2">
        <a href="perfil.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
          </svg>
          <span class="text-sm font-bold text-[#565656]"><?php echo htmlspecialchars($_SESSION['utilizador']); ?></span>
        </a>
        
        <a href="suporte.html" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          SUPORTE
        </a>

        <div class="border-t border-gray-100 my-1"></div>
        <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3H7.5A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l4.5 4.5m0 0L12 18m4.5-4.5H5.25" />
          </svg>
          LOGOUT
        </a>
      </div>
    </div>
  </header>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <main class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 mt-6 mb-12">
    
<?php if (isset($_SESSION['DESCONTO_ATIVO']) && $_SESSION['DESCONTO_ATIVO'] === true): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
      <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
      </svg>
      <div>
        <strong class="font-bold">Desconto</strong>
        <p class="text-sm">Tem um desconto de 10% disponível para a sua próxima reserva. Será aplicado automaticamente.</p>
    </div>
  </div>
<?php endif; ?>

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-8">

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
          <a href="#" class="w-full sm:w-auto text-center bg-red-600 text-white font-bold py-2 px-5 rounded-lg hover:bg-red-700 transition-all duration-300">
            Apagar Conta
          </a>
        </div>
      </div>

    </div>
    
  </main>


  <footer class="mt-4 sm:mt-6 lg:mt-8 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd]">
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Diogo Oliveira | a2023120056@alumni.iscac.pt</p>
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Eduardo Vieira | a2023129589@alumni.iscac.pt</p>
    <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; 2025 - ESPAÇO LUSITANO</p>
  </footer>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const profileBtnDesktop = document.getElementById('profile-btn-desktop');
    const profileDropdownDesktop = document.getElementById('profile-dropdown-desktop');

    // Lógica para o menu mobile (ícone "hamburguer")
    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', function(event) {
        event.stopPropagation(); 
        mobileMenu.classList.toggle('hidden');
        if (profileDropdownDesktop) {
            profileDropdownDesktop.classList.add('hidden');
        }
      });
    }

    // Lógica para o menu de perfil desktop (ícone de utilizador)
    if (profileBtnDesktop && profileDropdownDesktop) {
      profileBtnDesktop.addEventListener('click', function(event) {
        event.stopPropagation();
        profileDropdownDesktop.classList.toggle('hidden');
        if (mobileMenu) {
            mobileMenu.classList.add('hidden');
        }
      });
    }

    document.addEventListener('click', function(event) {
      if (mobileMenu && !mobileMenu.classList.contains('hidden') && !mobileMenu.contains(event.target) && !menuBtn.contains(event.target)) {
        mobileMenu.classList.add('hidden');
      }

      if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden') && !profileDropdownDesktop.contains(event.target) && !profileBtnDesktop.contains(event.target)) {
        profileDropdownDesktop.classList.add('hidden');
      }
    });

  });
</script>
</body>
</html>