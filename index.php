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


<body class="bg-gray-50 font-sans container-overflow">
  <!-- Header -->
  <header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
      
      <!-- Logo e título à esquerda -->
      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="index.php">
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
        <a href="login.php" 
           class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg
                  hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300">
          LOGIN
        </a>
  
        <a href="signin.php" 
           class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg
                  hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300">
          SIGNIN
        </a>
  
        <a href="suporte.html" 
           class="flex items-center justify-center w-7 h-7 lg:w-8 lg:h-8 xl:w-10 xl:h-10 cursor-pointer hover:-translate-y-0.5 transition-transform duration-300">
          <img src="imagens/SUPORTE.png" class="w-full h-full object-contain" alt="Suporte">
        </a>
      </div>
  
      <!-- Hamburger menu (mobile) - SEMPRE VISÍVEL EM MOBILE -->
      <div class="md:hidden flex items-center ml-1 flex-shrink-0">
        <button id="menu-btn" class="text-xl sm:text-2xl lg:text-3xl text-[#565656] focus:outline-none">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  
<!-- Versão com ícones e posição ajustada -->
<div id="mobile-menu" class="hidden md:hidden absolute top-16 right-4 sm:right-6 lg:right-8 w-44 sm:w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
  <div class="py-2">
    <!-- Login -->
    <a href="login.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
      <svg class="w-4 h-4 mr-3 stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
      </svg>
      LOGIN
    </a>
    
    <!-- Sign In -->
    <a href="signin.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
      <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
      </svg>
      SIGNIN
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
  <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
    <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
    <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1">
      <h5 class="font-bold text-base sm:text-lg md:text-xl min-h-[3rem]">HOTEL ALGARVE .................................</h5>
      <div class="flex-1">
        <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
      </div>
      
      <div class="flex justify-between items-center mt-auto">
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
  <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
    <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
    <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1">
      <h5 class="font-bold text-base sm:text-lg md:text-xl min-h-[3rem]">HOTEL ALGARVE</h5>
      <div class="flex-1">
        <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
      </div>
      
      <div class="flex justify-between items-center mt-auto">
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
  <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
    <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
    <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1">
      <h5 class="font-bold text-base sm:text-lg md:text-xl min-h-[3rem]">HOTEL ALGARVE</h5>
      <div class="flex-1">
        <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
      </div>
      
      <div class="flex justify-between items-center mt-auto">
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
  <div class="flex-none w-[85%] max-w-[300px] scroll-snap-align-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
    <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
    <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1">
      <h5 class="font-bold text-base sm:text-lg md:text-xl min-h-[3rem]">HOTEL ALGARVE</h5>
      <div class="flex-1">
        <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 md:mb-6">Quarto para 2 pessoas</p>
      </div>
      
      <div class="flex justify-between items-center mt-auto">
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
            <h5 class="font-bold text-base sm:text-lg md:text-xl">HOTEL ALGARVE asdjkashdjasdkhsajd hasgdhagsjhd <</h5>
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
        
      <!-- CARROSSEL para as regiões - RESPONSIVO EM TODAS AS TELAS -->
      <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth gap-4 sm:gap-6 p-4 -mx-2 sm:-mx-3 lg:-mx-4 scrollbar-thin scrollbar-thumb-[#c8c8b2] scrollbar-track-[#f1f1f1]">
       
        <!-- Região 1 -->
        <div class="flex-none w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/LISBOA/LISBOA.jpg" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">LISBOA</h5>
          </div>
        </div>

        <!-- Região 2 -->
        <div class="flex-none w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/PORTO/PORTO.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">PORTO</h5>
          </div>
        </div>

        <!-- Região 3 -->
        <div class="flex-none w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/BRAGA/BRAGA.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">BRAGA</h5>
          </div>
        </div>

        <!-- Região 4 -->
        <div class="flex-none w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">ALGARVE</h5>
          </div>
        </div>

        <!-- Região 5 (opcional - para ter mais conteúdo no carrossel) -->
        <div class="flex-none w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer">
          <img src="imagens/ALGARVE/ALGARVE.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2]">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">MADEIRA</h5>
          </div>
        </div>

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
    // Script para scroll suave nos carrosseis
    document.addEventListener('DOMContentLoaded', function() {
      // Selecionar todos os containers de carrossel pelas suas classes
      const carrosseis = document.querySelectorAll('.flex.overflow-x-auto');
      
      carrosseis.forEach(carrossel => {
        // Apenas garantir scroll suave
        carrossel.style.scrollBehavior = 'smooth';
        carrossel.style.WebkitOverflowScrolling = 'touch';
      });
    });
  </script>
</body>
</html>