<?php
session_start();

  // Verifica se o utilizador está logado
  if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
      header("Location: login.php"); 
      exit();
  }
  // Obtém o nome do utilizador da sessão para o mostrar
  $nomeUtilizador = htmlspecialchars($_SESSION['utilizador']);
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUPORTE - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .container {
      margin-top: 40px;
      max-width: 800px;
      background: white;
      border-radius: 15px;
      overflow: hidden;
      width: 90%;
      margin-left: auto;
      margin-right: auto;
    }
    .faq-container { padding: 0; }
    .main-question {
      padding: 16px 20px;
      border-bottom: 1px solid #eee;
      cursor: pointer;
      font-weight: bold;
      background: #f8f9fa;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .main-question:hover { background: #e9ecef; }
    .main-question::after {
      content: "▼";
      font-size: 12px;
      color: rgb(112, 112, 112);
    }
    .subquestions { display: none; background: white; }
    .subquestion {
      padding: 14px 25px;
      border-bottom: 1px solid #f0f0f0;
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  </style>
</head>

<body>

<header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
      
      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="paginainicial.php"> <img src="imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-3xl xl:text-4xl font-bold text-[#565656]">
            Suporte
          </h1>
          <p class="hidden sm:block text-xs md:text-base lg:text-xl text-[#707070]">
            Esclareça todas as suas dúvidas!
          </p>
        </div>
      </div>
  
      <div class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-shrink-0">
        
        <a onclick="window.history.back()" class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer">
            VOLTAR
        </a>

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
          <span class="text-sm font-bold text-[#565656]"><?php echo $nomeUtilizador; ?></span>
        </a>
        
        <a onclick="window.history.back()" class="flex items-center px-4 py-2 text-sm text-[#565656] hover:bg-[#e5e5dd] font-medium transition-colors duration-200 cursor-pointer">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            VOLTAR
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const profileBtnDesktop = document.getElementById('profile-btn-desktop');
    const profileDropdownDesktop = document.getElementById('profile-dropdown-desktop');
    
    // Script do Menu Mobile (Hamburger)
    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', (event) => {
        event.stopPropagation(); 
        if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden')) {
          profileDropdownDesktop.classList.add('hidden');
        }
        mobileMenu.classList.toggle('hidden');
      });
    }

    // Script do Menu de Perfil (Desktop)
    if (profileBtnDesktop && profileDropdownDesktop) {
      profileBtnDesktop.addEventListener('click', (event) => {
        event.stopPropagation(); 
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
          mobileMenu.classList.add('hidden');
        }
        profileDropdownDesktop.classList.toggle('hidden');
      });
    }

    // Fechar menus se clicar fora
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

<nav class="h-2 bg-[#c8c8b2]"></nav>

<div class="container max-w-3xl w-full mx-auto my-6 mt-10 bg-white rounded-lg px-4 sm:px-6 overflow-hidden">
  <div class="text-center font-bold bg-[#e5e5dd] py-4 text-2xl sm:text-3xl sm:py-6 rounded-t-lg">
    Perguntas Frequentes
  </div>
  <div class="faq-container rounded-b-lg">
    <div class="main-question rounded-t-lg" onclick="toggleSubquestions('registo')">
      Como posso registar-me?
    </div>
    <div class="subquestions" id="registo">
      <div class="subquestion">
        Pode registar-se clicando no botão "SIGNIN" localizado no canto superior direito na página inicial. Basta preencher o formulário com os seus dados pessoais e confirmar o seu e-mail.
      </div>
    </div>
    
    <div class="main-question" onclick="toggleSubquestions('entrar')">
      Como faço o "LOGIN"?
    </div>
    <div class="subquestions" id="entrar">
      <div class="subquestion">
        Pode entrar na conta só depois de se ter registado, clicando no botão "LOGIN" localizado no canto superior direito na página inicial. Basta preencher o formulário com os seus dados pessoais com que se registou.
      </div>   
    </div>

    <div class="main-question" onclick="toggleSubquestions('contapessoal')">
      Como posso entrar na conta pessoal?
    </div>
    <div class="subquestions" id="contapessoal">
      <div class="subquestion">
        Após fazer "LOGIN", terá acesso á sua área pessoal no canto superior direito.
      </div>   
    </div>

    <div class="main-question" onclick="toggleSubquestions('reserva')">
      Como faço uma reserva?
    </div>
    <div class="subquestions" id="reserva">
      <div class="subquestion">
        Após encontrar o alojamento desejado, clique nele para aceder à página de detalhes. Aí, poderá ver todas as informações, fotos e preços. Para reservar, utilize os contactos fornecidos pelo site.
      </div>
    </div>
    
    <div class="main-question" onclick="toggleSubquestions('historico')">
      Como posso consultar o histórico de reservas?
    </div>
    <div class="subquestions" id="historico">
      <div class="subquestion">
        Para consultar o seu histórico de reservas, basta aceder à sua Área Pessoal (disponível após login) e selecionar a opção "Histórico de Reservas". Aí encontrará uma lista completa de todas as suas reservas anteriores e actuais, com detalhes como datas, valores e estados das reservas.                           
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('cancelar')">
      Como posso cancelar ou alterar uma reserva?
    </div>
    <div class="subquestions" id="cancelar">
      <div class="subquestion">
        Para cancelar ou alterar uma reserva, terá que contactar o próprio alojamento utilizando os contactos fornecidos pelo site.
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('avaliar')">
      Como posso avaliar um pacote de destino?
    </div>
    <div class="subquestions" id="avaliar">
      <div class="subquestion">
        Após ter desfrutado do seu pacote de destino poderá avaliar a sua experiência, acedendo à sua Área Pessoal → Histórico de Reservas e clicar em "Avaliar" junto à reserva concluída. As avaliações ajudam outros viajantes e os anfitriões a melhorarem os seus serviços.                          
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('notificacoes')">
      Como funcionam as notificações por e-mail e no navegador?
    </div>
    <div class="subquestions" id="notificacoes">
      <div class="subquestion">
        Pode gerir as suas preferências de notificação na sua Área Pessoal → Definições de Notificações. 
        Pode optar por receber alertas por e-mail sobre promoções, reservas e actualizações e notificações no navegador para lembrete de check-in, avaliações pendentes e ofertas especiais.                  
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('redessociais')">
      Como posso partilhar as minhas viagens nas redes sociais?
    </div>
    <div class="subquestions" id="redessociais">
      <div class="subquestion">
        Após fazer "LOGIN", aceda à sua Área Pessoal → As Minhas Viagens. Seleccione a viagem que deseja partilhar e clique no ícone da rede social pretendida (Facebook, Instagram, Twitter, etc.). Pode partilhar tanto reservas futuras como experiências já concluídas.      
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('desconto')">
      Há descontos para a primeira compra?
    </div>
    <div class="subquestions" id="desconto">
      <div class="subquestion">
        Sim! Ao criar uma conta e efectuar a sua primeira reserva, recebe automaticamente 10% de desconto.                           
      </div>
    </div>

    <div class="main-question" onclick="toggleSubquestions('ajuda')">
      O que devo fazer se tiver problemas técnicos?
    </div>
    <div class="subquestions" id="ajuda">
      <div class="subquestion rounded-b-lg">
        Caso encontre dificuldades, consulte o menu de suporte na barra de navegação. Se o problema persistir, contacte o nosso suporte através do e-mail: suporte@espacolusitano.pt. 
      </div>
    </div>
    
    <div id="detailed-answer-container"></div>
  </div>
</div>

<script>
  function toggleSubquestions(category) {     
    const subquestions = document.getElementById(category);
    subquestions.style.display = subquestions.style.display === 'block' ? 'none' : 'block';
  }
</script>

<p></p>

  <footer class="mt-4 sm:mt-6 lg:mt-8 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd]">
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Diogo Oliveira | a2023120056@alumni.iscac.pt</p>
    <p class="mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base">Eduardo Vieira | a2023129589@alumni.iscac.pt</p>
    <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; 2025 - ESPAÇO LUSITANO</p>
  </footer>

</body>
</html>