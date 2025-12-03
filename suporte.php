<?php session_start();

$logado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

$nomeUtilizador = $logado ? htmlspecialchars($_SESSION['utilizador']) : '';

$pageTitle = 'Suporte';
$pageSubtitle = 'Esclareça todas as suas dúvidas!';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUPORTE - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <link href="css/nav.css" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
<?php include 'includes/header.php'; ?>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <div class="container max-w-3xl w-full mx-auto mb-12 mt-10 rounded-lg px-4 sm:px-6">
      <div class="text-center font-bold bg-[#e5e5dd] py-4 text-2xl sm:text-3xl sm:py-6 rounded-t-lg">
        Perguntas Frequentes
      </div>
      
      <div class="faq-container rounded-b-lg">
        
        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('registo')">
            Como posso registar-me?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="registo">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
                Pode registar-se clicando no botão "ENTRAR" e de seguida em "Criar Conta". Basta preencher o formulário com os seus dados pessoais.
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('entrar')">
            Como entro na conta?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="entrar">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
                Pode entrar na conta só depois de se ter registado, clicando no botão "ENTRAR" localizado no canto superior direito. Basta preencher o formulário com os seus dados pessoais com que se registou.
            </div>
        </div>
        
        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('fazerreserva')">
            Como faço uma reserva?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="fazerreserva">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Após encontrar o alojamento desejado, clique nele para aceder à página de detalhes, poderá ver todas as informações, fotos e preços. Para reservar, utilize os contactos fornecidos pelo site.          </div>
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('histreservas')">
            Como posso consultar o histórico de reservas?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="histreservas">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
              Para consultar o seu histórico de reservas, basta aceder à sua Área Pessoal e selecionar a opção "Histórico de Reservas". Aí encontrará uma lista completa de todas as suas reservas anteriores e actuais, com detalhes como datas, valores e estados das reservas.                         
            </div>
        </div>
        
        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('alterarreserva')">
          Como posso cancelar ou alterar uma reserva?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="alterarreserva">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
             Para cancelar ou alterar uma reserva, terá que contactar o próprio alojamento utilizando os contactos fornecidos pelo site.
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('destino')">
          Como posso avaliar um pacote de destino?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="destino">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Após ter desfrutado do seu alojamento de destino poderá avaliar a sua experiência, acedendo à sua Área Pessoal → Histórico de Reservas e clicar em "Avaliar" junto à reserva concluída. As avaliações ajudam outros viajantes e os anfitriões a melhorarem os seus serviços.                         
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('notificação')">
          Como funcionam as notificações por e-mail e no navegador?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="notificação">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Pode gerir as suas preferências de notificação na sua Área Pessoal → Definições de Notificações. 
            Pode optar por receber alertas por e-mail sobre promoções, reservas e actualizações e notificações no navegador para lembrete de check-in, avaliações pendentes e ofertas especiais.                 
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('partilhar')">
          Como posso partilhar as minhas viagens nas redes sociais?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="partilhar">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Após aceder à sua Área Pessoal → As Minhas Viagens. Seleccione a viagem que deseja partilhar e clique no ícone da rede social pretendida (Facebook, Instagram, Twitter, etc.). Pode partilhar tanto reservas futuras como experiências já concluídas.       
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('desconto')">
          Há descontos para a primeira compra?   
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="desconto">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Sim! Ao criar uma conta e efectuar a sua primeira reserva, recebe automaticamente 10% de desconto.                         
            </div>
        </div>

        <div class="main-question rounded-t-lg p-4 cursor-pointer font-semibold text-[#565656] border-b border-gray-200 hover:bg-[#d5d5cd]" onclick="toggleSubquestions('problemas')">
          O que devo fazer se tiver problemas técnicos?
        </div>
        <div class="subquestions hidden border-b border-gray-200" id="problemas">
            <div class="subquestion p-4 bg-gray-50 text-gray-700">
            Caso encontre dificuldades, consulte o menu de suporte na barra de navegação. Se o problema persistir, contacte o nosso suporte através do e-mail: suporte@espacolusitano.pt. 
            </div>
        </div>
        
      </div>
    </div>

<script src="js/global.js" defer></script>

<?php include 'includes/footer.php'; ?>

</body>
</html>