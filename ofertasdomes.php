<?php session_start();
// Definimos o título e subtítulo específicos para esta página.
$pageTitle = 'Ofertas do Mês';
$pageSubtitle = 'Poupe em estadias para este mês de Outubro';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ofertas do Mês - ESPAÇO LUSITANO</title>
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
          <h2 class="text-xs sm:text-sm md:text-base font-bold text-[#565656]">Poupe em estadias para este mês de Outubro</h2>
          </div>
      </div>
        
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">MOOV HOTEL LISBOA ORIENTE</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Lisboa, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
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

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA2.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ALGARVE</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Algarve, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 4 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.1</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">200€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">150€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA3.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">CASA RÚSTICA PORTO</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Porto, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Casa inteira (6 pessoas)</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.8</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">180€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">130€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA4.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">APARTAMENTO AVEIRO</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Aveiro, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.4</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">90€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">75€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL COIMBRA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Coimbra, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.2</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">110€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">85€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA2.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">CASA MADEIRA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Madeira, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 3 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.0</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">140€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">110€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA3.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL BRAGA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Braga, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.1</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">100€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">80€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA4.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">QUINTA DOURO</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Douro, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.5</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">220€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">190€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>
        
        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL ÉVORA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Évora, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.0</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">100€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">80€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA2.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">APARTAMENTO LISBOA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Lisboa, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 4 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.2</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">210€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">180€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA3.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOSTEL BRAGA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Braga, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Cama em dormitório</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.7</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">70€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">50€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA4.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL FARO CENTRAL</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Faro, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.4</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">130€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">110€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">PENSÃO AÇORES</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Açores, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.9</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">95€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">70€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA2.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">HOTEL SERRA DA ESTRELA</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Serra da Estrela, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 3 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.3</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">160€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">140€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA3.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">APARTAMENTO SETÚBAL</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Setúbal, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>8.3</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">110€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">90€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>

        <a href="alojamento.php" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA4.png" class="w-full h-40 sm:h-48 md:h-56 object-cover">
          <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
            <div class="flex-shrink-0">
              <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight">CASA DE CAMPO ALENTEJO</h5>
              <p class="text-gray-600 text-sm sm:text-base md:text-lg">Alentejo, Portugal</p>
              <p class="text-gray-400 text-xs sm:text-sm md:text-base">Quarto para 2 pessoas</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-shrink-0 mt-4">
              <div class="flex justify-between items-center">
                <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                  <span>9.8</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                  </svg>
                </div>
                <div class="flex items-baseline gap-1 sm:gap-2">
                  <p class="text-red-500 text-xs sm:text-sm md:text-md line-through">250€</p>
                  <p class="text-black font-bold text-base sm:text-lg md:text-xl">220€ por noite</p>
                </div>
              </div>
            </div>
          </div>
        </a>
        
      </div>
    </div>
  </section>

  <script src="js/global.js" defer></script>

  <?php include 'footer.php'; ?>

</body>
</html>