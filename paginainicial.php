<?php
session_start();

// VERIFICAÇÃO DE SEGURANÇA
$sessao_valida = (
    isset($_SESSION['logado']) && 
    $_SESSION['logado'] === true && 
    isset($_SESSION['iduser']) && 
    is_numeric($_SESSION['iduser']) && 
    $_SESSION['iduser'] > 0
);

if (!$sessao_valida) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESPAÇO LUSITANO - PÁGINA INICIAL</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans overflow-x-hidden max-w-full relative">
  <!-- Header -->
  <header class="w-full py-3 bg-[#e5e5dd]">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
      
      <!-- Logo e título à esquerda -->
      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0">
        <a href="paginainicial.php">
          <img src="imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-[#565656]">
            Encontre a sua próxima estadia
          </h1>
          <p class="hidden sm:block text-xs md:text-base lg:text-xl text-[#707070]">
            Encontre promoções em hóteis, apartamentos e muito mais...
          </p>
        </div>
      </div>
  
      <!-- Botões e suporte (desktop) -->
      <div class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-shrink-0">
        <!-- BOTÃO DE LOGOUT -->
        <a href="logout.php" 
           class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg
                  hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300">
          TERMINAR SESSÃO
        </a>
  
        <a href="suporte.html" 
           class="flex items-center justify-center w-7 h-7 lg:w-8 lg:h-8 xl:w-10 xl:h-10 cursor-pointer hover:-translate-y-0.5 transition-transform duration-300">
          <img src="imagens/SUPORTE.png" class="w-full h-full object-contain" alt="Suporte">
        </a>
      </div>
  
      <!-- Hamburger menu (mobile) -->
      <div class="md:hidden flex items-center ml-1 flex-shrink-0">
        <button id="menu-btn" class="text-xl sm:text-2xl lg:text-3xl text-[#565656] focus:outline-none">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  
    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-16 right-4 sm:right-6 lg:right-8 w-44 sm:w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
      <div class="py-2">
        <!-- Logout no mobile -->
        <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          TERMINAR SESSÃO
        </a>
        
        <!-- Suporte -->
        <a href="suporte.html" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          SUPORTE
        </a>
      </div>
    </div>
  </header>

  <script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <!-- Seção de ofertas com 4 hotéis EM CARROSSEL -->
  <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-6">
    <div class="mx-auto">

      <!-- Título e botão VER MAIS -->
      <div class="flex flex-row justify-between items-center mb-3 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Ofertas do mês</h2>
          <h2 class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Poupe em estadias para este mês de Outubro</h2>
        </div>
      
        <!-- Botão VER MAIS -->
        <a href="ofertasdomes.php" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap flex-shrink-0">
          VER MAIS
        </a>
      </div>
        
      <!-- CARROSSEL para os 4 hotéis -->
      <div class="flex overflow-x-auto scroll-snap-type-x-mandatory scroll-behavior-smooth -webkit-overflow-scrolling-touch gap-6 p-4 mx-[-0.5rem] scrollbar-thin scrollbar-thumb-[#c8c8b2] scrollbar-track-[#f1f1f1] md:grid md:grid-cols-2 md:overflow-x-visible md:gap-6 md:p-0 md:mx-0 lg:grid-cols-4">
       
        <!-- Hotel 1 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>

            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>

              <div class="flex justify-end items-baseline gap-1 sm:gap-2 md:gap-3">
                <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 2 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>

            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>

              <div class="flex justify-end items-baseline gap-1 sm:gap-2 md:gap-3">
                <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 3 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>

              <div class="flex justify-end items-baseline gap-1 sm:gap-2 md:gap-3">
                <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 4 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>

              <div class="flex justify-end items-baseline gap-1 sm:gap-2 md:gap-3">
                <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Indicadores do carrossel (apenas mobile) -->
      <div class="flex justify-center gap-2 mt-4 md:hidden" id="carrosselIndicators">
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300 active:bg-[#565656]"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
      </div>
    </div>

    <!-- Segunda seção - Alojamentos melhor avaliados -->
    <div class="mx-auto mt-6 sm:mt-12">

      <!-- Título e botão VER MAIS -->
      <div class="flex flex-row justify-between items-center mb-4 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Alojamentos melhor avaliados</h2>
          <h2 class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Desfrute dos alojamentos mais acolhidos pelos hóspedes</h2>
        </div>
      
        <!-- Botão VER MAIS -->
        <a href="alojamentosmelhoravaliados.php" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap flex-shrink-0">
          VER MAIS
        </a>
      </div>
        
      <!-- CARROSSEL para os 4 hotéis -->
      <div class="flex overflow-x-auto scroll-snap-type-x-mandatory scroll-behavior-smooth -webkit-overflow-scrolling-touch gap-6 p-4 mx-[-0.5rem] scrollbar-thin scrollbar-thumb-[#c8c8b2] scrollbar-track-[#f1f1f1] md:grid md:grid-cols-2 md:overflow-x-visible md:gap-6 md:p-0 md:mx-0 lg:grid-cols-4">
       
        <!-- Hotel 1 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>
              
              <div class="flex items-baseline gap-1 sm:gap-2">
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 2 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>
              
              <div class="flex items-baseline gap-1 sm:gap-2">
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 3 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>
              
              <div class="flex items-baseline gap-1 sm:gap-2">
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hotel 4 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2]">
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE</h5>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
            
            <div class="flex justify-between items-center">
              <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                <span>8.5</span>
                <i class="bi bi-star-fill text-xs"></i>
              </div>
              
              <div class="flex items-baseline gap-1 sm:gap-2">
                <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Indicadores do carrossel (apenas mobile) -->
      <div class="flex justify-center gap-2 mt-4 md:hidden" id="carrosselIndicators2">
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300 active:bg-[#565656]"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
      </div>
    </div>

    <!-- Terceira seção - Explore as seguintes regiões: -->
    <div class="mx-auto mt-6 sm:mt-12">

      <!-- Título -->
      <div class="flex flex-row justify-between items-center mb-4 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Explore as seguintes regiões:</h2>
          <h2 class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Explore todos os distritos de Portugal</h2>
        </div>
      </div>
        
      <!-- CARROSSEL para as regiões -->
      <div class="flex overflow-x-auto scroll-snap-type-x-mandatory scroll-behavior-smooth -webkit-overflow-scrolling-touch gap-6 p-4 mx-[-0.5rem] scrollbar-thin scrollbar-thumb-[#c8c8b2] scrollbar-track-[#f1f1f1] md:grid md:grid-cols-2 md:overflow-x-visible md:gap-6 md:p-0 md:mx-0 lg:grid-cols-4">
       
        <!-- Região 1 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/LISBOA/LISBOA.jpg" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-5 sm:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center sm:text-lg md:text-4xl">LISBOA</h5>
          </div>
        </div>

        <!-- Região 2 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/PORTO/PORTO.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-5 sm:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center sm:text-lg md:text-4xl">PORTO</h5>
          </div>
        </div>

        <!-- Região 3 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/BRAGA/BRAGA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-5 sm:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center sm:text-lg md:text-4xl">BRAGA</h5>
          </div>
        </div>

        <!-- Região 4 -->
        <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-5 sm:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center sm:text-lg md:text-4xl">ALGARVE</h5>
          </div>
        </div>
      </div>

      <!-- Indicadores do carrossel (apenas mobile) -->
      <div class="flex justify-center gap-2 mt-4 md:hidden" id="carrosselIndicators3">
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300 active:bg-[#565656]"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
        <div class="w-2.5 h-2.5 rounded-full bg-[#c8c8b2] cursor-pointer transition-colors duration-300"></div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="mt-4 sm:mt-6 lg:mt-8 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd]">
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Diogo Oliveira | a2023120056@alumni.iscac.pt</p>
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Eduardo Vieira | a2023129589@alumni.iscac.pt</p>
    <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; 2025 - ESPAÇO LUSITÂNO</p>
  </footer>

  <script>
    // Script para os carrosseis
    document.addEventListener('DOMContentLoaded', function() {
      // Configurar todos os carrosseis
      const carrosseis = [
        { carrossel: 'carrossel', indicators: 'carrosselIndicators' },
        { carrossel: 'carrossel2', indicators: 'carrosselIndicators2' },
        { carrossel: 'carrossel3', indicators: 'carrosselIndicators3' }
      ];

      carrosseis.forEach(({ carrossel: carrosselId, indicators: indicatorsId }) => {
        const carrossel = document.getElementById(carrosselId);
        const indicators = document.querySelectorAll(`#${indicatorsId} > div`);

        if (carrossel && indicators.length > 0) {
          // Atualizar indicadores quando o carrossel é rolado
          carrossel.addEventListener('scroll', function() {
            const scrollPos = carrossel.scrollLeft;
            const itemWidth = carrossel.querySelector('div').offsetWidth + 24;
            const activeIndex = Math.round(scrollPos / itemWidth);
            
            indicators.forEach((indicator, index) => {
              if (index === activeIndex) {
                indicator.classList.add('bg-[#565656]');
                indicator.classList.remove('bg-[#c8c8b2]');
              } else {
                indicator.classList.add('bg-[#c8c8b2]');
                indicator.classList.remove('bg-[#565656]');
              }
            });
          });

          // Navegação por cliques nos indicadores
          indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
              const itemWidth = carrossel.querySelector('div').offsetWidth + 24;
              carrossel.scrollTo({
                left: index * itemWidth,
                behavior: 'smooth'
              });
            });
          });
        }
      });
    });
  </script>

</body>
</html>