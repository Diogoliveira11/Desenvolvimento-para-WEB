<?php
// 1. Verifica o estado de login
$logado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// 2. Prepara o nome do utilizador se estiver logado
$nomeUtilizador = $logado ? htmlspecialchars($_SESSION['utilizador']) : '';

// 3. Aceita variáveis para o Título e Subtítulo
$headerTitle = isset($pageTitle) ? $pageTitle : 'ESPAÇO LUSITANO';
$headerSubtitle = isset($pageSubtitle) ? $pageSubtitle : 'Encontre promoções em hóteis, apartamentos e muito mais...'; 

// 4. Deteta as páginas
$currentPage = basename($_SERVER['PHP_SELF']);
$isPerfilPage = ($currentPage == 'perfil.php' || $currentPage == 'alterarpass.php' || $currentPage == 'apagarconta.php'); 

// 5. Deteta as páginas onde o botão VOLTAR é NECESSÁRIO
$showVoltar = ($currentPage == 'alojamento.php' || 
               $currentPage == 'ofertasdomes.php' ||  
               $currentPage == 'suporte.php' || 
               $currentPage == 'perfil.php' || 
               $currentPage == 'melhoravaliados.php' ||
               $currentPage == 'alterarpass.php' || 
               $currentPage == 'apagarconta.php' ||
               $currentPage == 'alojamentoregiao.php' ||
               $currentPage == 'checkout.php' ||
               $currentPage == 'confirmacao.php' || 
               $currentPage == 'avaliaralojamento.php'); 

// 6. Determina onde o SUPORTE deve aparecer (em todo o lado exceto na sua própria página)
$showSuporte = ($currentPage != 'suporte.php');

// 7. Determina se deve mostrar o menu de perfil no desktop/mobile
// Esconde o menu de perfil se estiver numa página relacionada com o perfil 
$showProfileMenu = $logado && !$isPerfilPage; 

$homeLink = 'index.php';
?>
 
 <header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
      
      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="<?php echo $homeLink; ?>"> <img src="imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-[#565656]">
            <?php echo $headerTitle; ?>
          </h1>
          <p class="hidden sm:block text-xs md:text-base lg:text-xl text-[#707070]">
            <?php echo $headerSubtitle; ?>
          </p>
        </div>
      </div>
  
    <div class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-shrink-0">
        
        <?php if ($showVoltar): ?>
            <a onclick="history.back();" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer">
                VOLTAR
            </a>
        <?php endif; ?>

        <?php if ($showProfileMenu): // SÓ MOSTRA SE LOGADO E NÃO ESTIVER NO PERFIL ?>
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
                  <span class="font-medium"><?php echo $nomeUtilizador; ?></span>
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
        <?php elseif (!$logado): ?>
          <a href="login.php" 
            class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg
                  hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300">
            ENTRAR
          </a>
        <?php endif; ?>

        <?php if ($showSuporte): ?>
          <a href="suporte.php" 
            class="flex items-center justify-center w-7 h-7 lg:w-8 lg:h-8 xl:w-10 xl:h-10 cursor-pointer hover:-translate-y-0.5 transition-transform duration-300">
            <img src="imagens/SUPORTE.png" class="w-full h-full object-contain" alt="Suporte">
          </a>
        <?php endif; ?>

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
        
        <?php if ($logado && !$isPerfilPage): // SÓ MOSTRA SE LOGADO E NÃO ESTIVER NO PERFIL ?>
          <a href="perfil.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span class="text-sm font-bold text-[#565656]"><?php echo $nomeUtilizador; ?></span>
          </a>
        <?php elseif (!$logado): ?>
          <a href="login.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-3 stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            ENTRAR
          </a>
          <a href="signin.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"></path>
            </svg>
            REGISTAR
          </a>
        <?php endif; ?>

        <?php if ($showVoltar || $showSuporte): ?>
            <div class="border-t border-gray-100 my-1"></div>
        <?php endif; ?>

        <?php if ($showVoltar): ?>
          <a onclick="history.back();" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200 cursor-pointer">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            VOLTAR
          </a>
        <?php endif; ?>
        
        <?php if ($showSuporte): ?>
          <a href="suporte.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            SUPORTE
          </a>
        <?php endif; ?>

        <?php if ($logado && !$isPerfilPage): // SÓ MOSTRA SE LOGADO E NÃO ESTIVER NO PERFIL ?>
          <div class="border-t border-gray-100 my-1"></div>
          <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3H7.5A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l4.5 4.5m0 0L12 18m4.5-4.5H5.25" />
            </svg>
            LOGOUT
          </a>
        <?php endif; ?>
    </div>
</div>
</header>