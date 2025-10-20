<?php
// paginainicial.php - PROTEÇÃO + SEU HTML ORIGINAL
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

// SE CHEGOU AQUI, ESTÁ AUTENTICADO - MOSTRA O HTML ORIGINAL
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESPAÇO LUSITANO - PÁGINA INICIAL</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <link rel="stylesheet" href="css/nav.css">
</head>

<body>

  <header>
    <a href="paginainicial.php">
        <img src="imagens/LOGO MAIOR.png" style="width: 100px; cursor: pointer;"></a>

        <div>
            <div style="font-size: 45px; font-weight: bold;">Encontre a sua próxima estadia</div>
            <div style="font-size: 25.5px;">Encontre promoções em hóteis, apartamentos e muito mais...</div>
        </div>

        <div class="user-menu-container" id="userMenuContainer">
                <button class="user-btn" id="userBtn">    
                    <img src="imagens/USER.png" style="width: 60px; cursor: pointer;">
                </button>
                <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <img src="imagens/USER.png" alt="Utilizador">
                                <div class="text-dropdown">
                                    <div class="user-name"><a href="perfil.html">João Silva</div>
                                    <div class="user-email"><a href="perfil.html">joao.silva@exemplo.com</div>
                                </div>
                        </div>
                        <div class="text-dropdown">
                                <ul>
                                    <li class="logout"><a href="logout.html">Terminar Sessão</a></li>
                                </ul>

                        </div>
                </div>
        </div>

        <a href="suporte.html" class="suporte-btn">
          <img src="imagens/SUPORTE.png" style="width: 35px;">
        </a>
 </header>
    
  <nav style="background: rgb(200, 200, 178); padding: 1px;">&nbsp;</nav>

  <p></p>

  <div style="font-size: 35px; color: rgb(112, 112, 112); font-weight: bold; margin-left: 40px;">Ofertas do mês</div>
  <div style="font-size: 20px; color: rgb(112, 112, 112); font-weight: bold; margin-left: 40px;">Poupe em estadias para este mês de Outubro</div>

  <div id="galeria">
     <a href="hotel.html" target="_blank" class="card-container">
        <div class="card">
          <img src="imagens/MOOVHOTEL/HOTELMOOV.png" alt="">  
        </div>
        <div class="alojamento-info">
          <div class="alojamento-name">Moov Hotel Lisboa Oriente</div>
          <div class="alojamento-location">Lisboa, Portugal</div>
          <div class="alojamento-capacity">Quarto para 4 pessoas</div>
          <div class="price">
            <div class="last-price ">99€</div>
            <div class="actual-price">58€ por noite</div>
          </div>
        </div>
      </a>

      <a href="hotel.html" target="_blank" class="card-container">
        <div class="card">
          <img src="imagens/HOTELESTACÃO/HOTELESTACAO.png" alt="">
        </div>
        <div class="alojamento-info">
          <div class="alojamento-name">Hotel Estacão</div>
          <div class="alojamento-location">Braga, Portugal</div>
          <div class="alojamento-capacity">Quarto para 4 pessoas</div>
          <div class="price">
            <div class="last-price ">92€</div>
            <div class="actual-price">53€ por noite</div>
          </div>
        </div>
      </a>

     <a href="hotel.html" target="_blank" class="card-container">
        <div class="card">
          <img src="imagens/HOTELLISBOA/HOTELLISBOA.png" alt="">
        </div>
        <div class="alojamento-info">
          <div class="alojamento-name">Hotel Lisboa</div>
          <div class="alojamento-location">Lisboa, Portugal</div>
          <div class="alojamento-capacity">Quarto para 2 pessoas</div>
          <div class="price">
            <div class="last-price ">112€</div>
            <div class="actual-price">62€ por noite</div>
          </div>
        </div>
      </a>

     <a href="hotel.html" target="_blank" class="card-container">
        <div class="card">
          <img src="imagens/PORTOPALACIOHOTEL/PORTOPALACIO.png" alt="">
        </div>
        <div class="alojamento-info">
          <div class="alojamento-name">Porto Palácio Hotel Editory</div>
          <div class="alojamento-location">Porto, Portugal</div>
          <div class="alojamento-capacity">Quarto para 2 pessoas</div>
          <div class="price">
            <div class="last-price ">133€</div>
            <div class="actual-price">82€ por noite</div>
          </div>
        </div>
      </a>

  </div>

  <div style="font-size: 35px; color: rgb(112, 112, 112); font-weight: bold; margin-left: 40px;">Alojamentos melhor avaliados</div>
  <div style="font-size: 20px; color: rgb(112, 112, 112); font-weight: bold; margin-left: 40px;">Desfrute dos alojamentos mais acolhidos pelos hóspedes</div>

  <div id="galeria">
    <a href="hotel.html" target="_blank" class="card-container">
       <div class="card">
         <img src="imagens/HOTELMARQUES/HOTELMARQUES.png" alt="">  
       </div>
       <div class="alojamento-info">
         <div class="alojamento-name">Hotel Marquês de Pombal</div>
         <div class="alojamento-location">Lisboa, Portugal</div>
         <div class="alojamento-capacity">Quarto para 2 pessoas</div>
         <div class="price">
          <div class="last-price ">139€</div>
          <div class="actual-price">109€ por noite</div>
        </div>
       </div>
    </a>

     <a href="hotel.html" target="_blank" class="card-container">
       <div class="card">
         <img src="imagens/HOTELOCA/HOTELOCA.png" alt="">
       </div>
       <div class="alojamento-info">
         <div class="alojamento-name">Oca Vitoria Village</div>
         <div class="alojamento-location">Porto, Portugal</div>
         <div class="alojamento-capacity">Quarto para 2 pessoas</div>
         <div class="price">
          <div class="last-price ">123€</div>
          <div class="actual-price">76€ por noite</div>
        </div>
       </div>
      </a>

    <a href="hotel.html" target="_blank" class="card-container">
       <div class="card">
         <img src="imagens/MAISONALBAR/MAISONALBAR.png" alt="">
       </div>
       <div class="alojamento-info">
         <div class="alojamento-name">Maison Albar - Amoure</div>
         <div class="alojamento-location">Braga, Portugal</div>
         <div class="alojamento-capacity">Quarto para 2 pessoas</div>
         <div class="price">
          <div class="last-price ">239€</div>
          <div class="actual-price">182€ por noite</div>
        </div>
       </div>
    </a>

    <a href="hotel.html" target="_blank" class="card-container">
       <div class="card">
         <img src="imagens/VILAVITA/VILAVITA.png" alt="">
       </div>
       <div class="alojamento-info">
         <div class="alojamento-name">Vila Vita Parc</div>
         <div class="alojamento-location">Algarve, Portugal</div>
         <div class="alojamento-capacity">Quarto para 2 pessoas</div>
         <div class="price">
          <div class="last-price ">281€</div>
          <div class="actual-price">268€ por noite</div>
        </div>
       </div>
    </a>
    
 </div>

  
 <div style="font-size: 35px; color: rgb(112, 112, 112); font-weight: bold; margin-left: 40px;">Explore as seguintes regiões:</div>

  <div id="galeria">
     <a href="hotel.html" target="_blank" class="region-card">
        <div class="card">
          <img src="imagens/LISBOA/LISBOA.jpg" alt="">  
        </div>
        <div class="alojamento-info">
          <div style="font-size: 40px; text-align: center; font-weight: bold;"> LISBOA </div>
        </div>
     </a>

      <a href="hotel.html" target="_blank" class="region-card">
        <div class="card">
          <img src="imagens/PORTO/PORTO.png" alt="">
        </div>
        <div class="alojamento-info">
          <div style="font-size: 40px; text-align: center; font-weight: bold;"> PORTO </div>
        </div>
      </a>

     <a href="hotel.html" target="_blank" class="region-card">
        <div class="card">
          <img src="imagens/BRAGA/BRAGA.PNG" alt="">
        </div>
        <div class="alojamento-info">
         <div style="font-size: 40px; text-align: center; font-weight: bold;"> BRAGA </div>
        </div>
      </a>

     <a href="hotel.html" target="_blank" class="region-card">
        <div class="card">
          <img src="imagens/ALGARVE/ALGARVE.png" alt="">
        </div>
        <div class="alojamento-info">
          <div style="font-size: 40px; text-align: center; font-weight: bold;"> ALGARVE </div>
        </div>
      </a>
     
  </div>

  <footer>
    <p>Diogo oliveira | a2023120056@alumni.iscac.pt</p>
    <p>Eduardo Vieira | a2023129589@alumni.iscac.pt</p>
    <p>&copy; 2025 - ESPAÇO LUSITÂNO</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const userBtn = document.getElementById('userBtn');
      const userDropdown = document.getElementById('userDropdown');
      const userMenuContainer = document.getElementById('userMenuContainer');
      
      userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = userDropdown.classList.contains('active');
        
        if (isOpen) {
          userDropdown.classList.remove('active');
          userMenuContainer.classList.remove('active');
        } else {
          userDropdown.classList.add('active');
          userMenuContainer.classList.add('active');
        }
      });
      
      document.addEventListener('click', function(e) {
        if (!userMenuContainer.contains(e.target)) {
          userDropdown.classList.remove('active');
          userMenuContainer.classList.remove('active');
        }
      });
      
      userDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    });
  </script>

</body>
</html>