<?php
session_start();

$pageTitle = 'Encontre a sua próxima estadia';
$pageSubtitle = 'Encontre promoções em hóteis, apartamentos e muito mais...';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PÁGINA INICIAL - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script> 
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
  <?php include 'header.php'; ?>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-6 flex-grow">
    <div class="mx-auto">

      <div class="flex flex-row justify-between items-center mb-3 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Ofertas do mês</h2>
          <p class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Poupe em estadias para este mês de Outubro</p>
        </div>
      
        <a href="ofertasdomes.php" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap flex-shrink-0">
          VER MAIS
        </a>
      </div>
        
    <div class="flex overflow-x-auto snap-x snap-mandatory scroll-behavior-smooth -webkit-overflow-scrolling-touch gap-6 p-4 mx-[-0.5rem] md:grid md:grid-cols-2 md:overflow-x-visible md:gap-6 md:p-0 md:mx-0 lg:grid-cols-4">
 
    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between"> 
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">MOOV HOTEL LISBOA ORIENTE COM UM TEXTO MAIOR PARA TESTE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">120€</p>
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>
    </div> 
  </div>

    <div class="mx-auto mt-6 sm:mt-12">

      <div class="flex flex-row justify-between items-center mb-4 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Alojamentos melhor avaliados</h2>
          <p class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Desfrute dos alojamentos mais acolhidos pelos hóspedes</p>
        </div>
      
        <a href="alojamentosmelhoravaliados.php" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm md:text-base hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap flex-shrink-0">
          VER MAIS
        </a>
      </div>
        
    <div class="flex overflow-x-auto snap-x snap-mandatory scroll-behavior-smooth -webkit-overflow-scrolling-touch gap-6 p-4 mx-[-0.5rem] md:grid md:grid-cols-2 md:overflow-x-visible md:gap-6 md:p-0 md:mx-0 lg:grid-cols-4">
 
    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE COM UM TEXTO MAIOR PARA TESTE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="alojamento.php" class="w-[85%] max-w-[300px] flex-shrink-0 snap-start transition-transform duration-300 hover:translate-y-[-5px] md:w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
      <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
      <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between">
        <div class="flex-shrink-0">
          <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
          <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
          <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
        </div>
        <div class="flex-shrink-0 mt-4">
          <div class="flex justify-between items-center">
            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
              <span>8.5</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
              </svg>
            </div>
            <div class="flex items-baseline gap-1 sm:gap-2">
              <p class="text-black font-bold text-base sm:text-lg md:text-xl">96€ por noite</p>
            </div>
          </div>
        </div>
      </div>
    </a>
    </div>
    </div>
   </div>
 </div>

    <div class="mx-auto mt-6 sm:mt-12">

      <div class="flex flex-row justify-between items-center mb-4 gap-2 sm:gap-3 lg:gap-4">
        <div class="text-left flex-1">
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-[#565656]">Explore as seguintes regiões:</h2>
          <p class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Explore Portugal pelos distritos</p>
        </div>
      </div>
        
      <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth gap-4 sm:gap-6 p-4 -mx-2 sm:-mx-3 lg:-mx-4">        
        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/AÇORES/açores.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">AÇORES</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/AVEIRO/aveiro.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">AVEIRO</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/BRAGA/BRAGA.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">BRAGA</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/COIMBRA/COIMBRA.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">COIMBRA</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/EVORA/EVORA.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">ÉVORA</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/FARO/FARO.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">FARO</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/LISBOA/LISBOA.jpg" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">LISBOA</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/MADEIRA/madeira.jpg" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">MADEIRA</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/PORTO/PORTO.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">PORTO</h5>
          </div>
        </a>

        <a href="#" class="w-[80%] max-w-[280px] sm:w-[45%] sm:max-w-[320px] md:w-[40%] md:max-w-[350px] lg:w-[30%] lg:max-w-[380px] xl:w-[23%] flex-shrink-0 snap-start transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/SETUBAL/SETUBAL.png" class="w-full h-40 sm:h-48 md:h-56 lg:h-64 object-cover">
          <div class="p-4 sm:p-5 md:p-6 bg-[#f5f5f2] flex-1 flex flex-col justify-center">
            <h5 class="font-bold text-center text-base sm:text-lg md:text-2xl lg:text-3xl xl:text-4xl">SETÚBAL</h5>
          </div>
        </a>

    </div>
  </div>
  </section>

    <script src="js/global.js" defer></script>

    <?php include 'footer.php'; ?>
  
</body>
</html>