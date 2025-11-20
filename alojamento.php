<?php session_start();
// O alojamento.php não precisa de verificar o login, mas precisa do session_start
// para que o header.php saiba se deve mostrar o link "ENTRAR" ou o menu de Perfil.

// Definimos o título e subtítulo específicos para esta página.
$pageTitle = 'Moov Hotel Lisboa Oriente';
$pageSubtitle = 'Encontre a sua próxima estadia'; // Reutilizando o subtítulo principal
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moov Hotel Lisboa Oriente - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  
  <script src="https://cdn.tailwindcss.com"></script>
  
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
  <?php include 'header.php'; ?>
  
  <nav class="bg-[#c8c8b2] h-2"></nav>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-grow">

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

  <script src="js/global.js" defer></script>

  <?php include 'footer.php'; ?>

</body>
</html>