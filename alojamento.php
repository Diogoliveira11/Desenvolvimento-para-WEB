<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moov Hotel Lisboa Oriente - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  
  <script src="https://cdn.tailwindcss.com"></script>
  
</head>

<body class="bg-gray-50">
  <header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
      
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
  
      <div class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-shrink-0">
        <a href="login.php" 
           class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg
                  hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300">
          ENTRAR
        </a>
  
        <a class="js-voltar bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer"> 
          VOLTAR 
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
        <a href="login.php" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-3 stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
          </svg>
          ENTRAR
        </a>
        
        <a class="js-voltar flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200 cursor-pointer">
          <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          VOLTAR
        </a>
      </div>
    </div>
  </header>
  
  <nav class="bg-[#c8c8b2] h-2"></nav>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-2 md:grid-cols-4 md:grid-rows-2 gap-2 my-8 rounded-lg overflow-hidden shadow-lg">
      
      <div class="col-span-2 row-span-2">
        <img src="imagens/MOOVHOTEL/HOTELMOOV.png" alt="Vista principal do Moov Hotel" class="w-full h-full object-cover rounded-lg">
      </div>
      <div>
        <img src="imagens/MOOVHOTEL/HOTELMOOV1.png" alt="Detalhe Moov Hotel 1" class="w-full h-full object-cover rounded-lg">
      </div>
      <div>
        <img src="imagens/MOOVHOTEL/HOTELMOOV2.png" alt="Detalhe Moov Hotel 2" class="w-full h-full object-cover rounded-lg">
      </div>
      <div>
        <img src="imagens/MOOVHOTEL/HOTELMOOV3.png" alt="Detalhe Moov Hotel 3" class="w-full h-full object-cover rounded-lg">
      </div>
      
      <div class="relative">
        <img src="imagens/MOOVHOTEL/HOTELMOOV4.png" alt="Detalhe Moov Hotel 4" class="w-full h-full object-cover brightness-75 rounded-lg">
        <div class="absolute inset-0 flex items-center justify-center">
          <button id="open-gallery-btn" class="bg-white text-gray-900 font-bold py-2 px-5 rounded-lg hover:bg-gray-200 transition-all shadow-lg">
            Ver Mais
          </button>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
      
      <div class="w-full lg:w-2/3">
        <h1 class="text-4xl font-bold text-gray-900">Moov Hotel Lisboa Oriente</h1>
        <div class="text-lg text-gray-600 mt-1">Lisboa, Portugal</div>
        
        <div class="flex items-center gap-3 my-4">
          <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 text-xl flex items-center gap-1">
            <span>8.5</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-star-fill w-4 h-4" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </div>
        </div>
                
        <div class="space-y-4 text-gray-700 leading-relaxed">
          <p>O Moov Hotel Lisboa Oriente está localizado no coração do Parque das Nações, uma das áreas mais modernas e vibrantes de Lisboa. A apenas 5 minutos a pé da estação de comboios Gare do Oriente e do centro comercial Vasco da Gama, este hotel oferece uma localização privilegiada para explorar a cidade.</p>
          <p>Os quartos são modernos e funcionais, equipados com ar condicionado, TV de ecrã plano e casa de banho privativa. O hotel dispõe ainda de Wi-Fi gratuito em todas as áreas e um bar onde os hóspedes podem desfrutar de bebidas e snacks.</p>
          <p>Com uma equipa multilingue e sempre disponível para ajudar, o Moov Hotel Lisboa Oriente é a escolha ideal para quem procura conforto, praticidade e uma excelente relação qualidade-preço.</p>
        </div>
        
        <div class="mt-8">
          <h3 class="text-2xl font-semibold mb-4 text-gray-900">Comodidades do hotel</h3>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-3">
            
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Wi-Fi gratuito</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Ar condicionado</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Estacionamento pago</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Bar</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Recepção 24 horas</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Quartos não fumadores</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Acesso para deficientes</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              <span>Elevador</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="w-full lg:w-1/3">
        <div class="bg-gray-100 p-6 rounded-lg shadow-lg lg:sticky top-8">
          
          <div class="flex flex-col items-start">
            <div class="text-2xl text-red-500 line-through">99€</div>
            <div class="flex items-baseline gap-1">
              <div class="text-4xl font-bold text-gray-900">58€</div>
              <div class="text-gray-700 text-lg">por noite</div>
            </div>
          </div>
          
          <div class="mt-6 border-t pt-6">
            <h3 class="text-xl font-semibold mb-2 text-gray-900">Detalhes do quarto:</h3>
            <ul class="text-gray-700 space-y-1 list-disc list-inside">
              <li>Quarto para 4 pessoas</li>
              <li>2 camas de casal</li>
              <li>Área: 25 m²</li>
            </ul>
          </div>
          
          <div class="mt-6 border-t pt-6">
            <h3 class="text-xl font-semibold mb-2 text-gray-900">Check-in / Check-out</h3>
            <ul class="text-gray-700 space-y-1 list-disc list-inside">
              <li>Check-in: a partir das 15:00</li>
              <li>Check-out: até às 12:00</li>
            </ul>
          </div>

          <div class="mt-6 border-t pt-6">
            <h3 class="text-xl font-semibold mb-2 text-gray-900">Contacto:</h3>
            <ul class="text-gray-700 space-y-1 list-disc list-inside">
              <li>911 111 111</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </main>

  <div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-4 rounded-lg shadow-xl max-w-4xl w-11/12 max-h-[90vh] overflow-y-auto relative">
      
      <button id="close-gallery-btn" class="absolute top-2 right-4 text-gray-500 hover:text-gray-900 text-3xl font-bold z-10">&times;</button>
      
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Galeria de Fotos</h2>
      
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <img src="imagens/MOOVHOTEL/HOTELMOOV.png" alt="Vista principal" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV1.png" alt="Detalhe 1" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV2.png" alt="Detalhe 2" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV3.png" alt="Detalhe 3" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV4.png" alt="Detalhe 4" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV5.png" alt="Detalhe 5" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV6.png" alt="Detalhe 6" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV7.png" alt="Detalhe 7" class="w-full h-auto object-cover rounded-lg">
        <img src="imagens/MOOVHOTEL/HOTELMOOV8.png" alt="Detalhe 8" class="w-full h-auto object-cover rounded-lg">
      </div>
    </div>
  </div>


  <footer class="mt-4 sm:mt-6 lg:mt-8 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd]">
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Diogo Oliveira | a2023120056@alumni.iscac.pt</p>
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Eduardo Vieira | a2023129589@alumni.iscac.pt</p>
    <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; 2025 - ESPAÇO LUSITANO</p>
  </footer>

  <script src="js/global.js" defer></script>

</body>
</html>