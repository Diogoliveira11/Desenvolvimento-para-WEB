<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php"); 
    exit();
}

$idUtilizador = $_SESSION['id_user'];
$nomeUtilizador = "";
$emailUtilizador = "";
$erro = "";

// 1. BUSCA DE DADOS BÁSICOS DO PERFIL (Utilizador e Email)
$stmt = mysqli_prepare($link, "SELECT utilizador, email FROM utilizadores WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $idUtilizador);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $nomeUtilizador = htmlspecialchars($user['utilizador']);
    $emailUtilizador = htmlspecialchars($user['email']);
} else {
    // Se o ID da sessão não corresponder a um utilizador na BD, destrói a sessão por segurança.
    $erro = "Não foi possível carregar os dados do perfil.";
    session_destroy();
    header("Location: login.php?erro=perfil");
    exit();
}
mysqli_stmt_close($stmt);

// 2. BUSCA DE RESERVAS DO UTILIZADOR
$query_reservas = "
    SELECT 
        R.id_reserva, R.id_alojamento, R.data_check_in, R.data_check_out, R.preco_total, R.num_hospedes, R.estado,
        A.nome AS nome_alojamento, A.localizacao
    FROM 
        reservas AS R
    INNER JOIN 
        alojamento AS A ON R.id_alojamento = A.id_alojamento
    WHERE 
        R.id_utilizador = $idUtilizador
    ORDER BY 
        R.data_check_out DESC, R.data_check_in DESC
";
$result_reservas = mysqli_query($link, $query_reservas);
$minhas_reservas = $result_reservas ? mysqli_fetch_all($result_reservas, MYSQLI_ASSOC) : [];

$pageTitle = 'O Meu Perfil';
$pageSubtitle = 'Consulte todas as suas informações!';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PERFIL - ESPAÇO LUSITANO</title>
  <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
  <?php include 'includes/header.php'; ?>

  <nav class="h-2 bg-[#c8c8b2]"></nav>

  <main class="max-w-4xl mx-auto w-full flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 mb-3"> 

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-8 py-12 w-full"> 

      <div>
        <h2 class="text-2xl font-bold text-[#565656] border-b border-gray-200 pb-2 mb-4">
          Dados Pessoais
        </h2>
        <form class="space-y-4">
          <div>
            <label for="utilizador" class="block text-sm font-medium text-gray-700">Nome de Utilizador</label>
            <input type="text" name="utilizador" id="utilizador" readonly 
                   class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" 
                   value="<?php echo $nomeUtilizador; ?>">
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" readonly 
                   class="mt-1 w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" 
                   value="<?php echo $emailUtilizador; ?>">
          </div>
        </form>
      </div>

      <div>
        <h2 class="text-2xl font-bold text-[#565656] border-b border-gray-200 pb-2 mb-4">
          Minhas Reservas
        </h2>
        
        <?php if (empty($minhas_reservas)): ?>
          <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm">De momento, não tem nenhuma reserva ativa.</p>
          </div>
        <?php else: ?>
          
          <div class="space-y-4">
            <?php foreach ($minhas_reservas as $reserva): 
                
                // LÓGICA DE PLURALIZAÇÃO E DATA (Mantida)
                $num_hospedes = $reserva['num_hospedes'];
                $check_in_dt = new DateTime($reserva['data_check_in']);
                $check_out_dt = new DateTime($reserva['data_check_out']);
                $num_noites = $check_in_dt->diff($check_out_dt)->days;

                $texto_hospedes = $num_hospedes == 1 ? 'hóspede' : 'hóspedes';
                $texto_noites = $num_noites == 1 ? 'noite' : 'noites';

                $check_in = $check_in_dt->format('d/m/Y');
                $check_out = $check_out_dt->format('d/m/Y');
                $preco = number_format($reserva['preco_total'], 2, ',', '.');
                
                // Lógica de Avaliação: Só pode avaliar se a data de check-out passou
                $hoje = new DateTime('1/1/2026');
                $pode_avaliar = $check_out_dt < $hoje; 
                
                $estado_cor = $reserva['estado'] == 'Confirmada' ? 'bg-green-100 text-green-800 border-green-400' : 'bg-gray-100 text-gray-800 border-gray-400';
            ?>
            <div class="border border-gray-200 p-4 rounded-lg shadow-sm bg-gray-50">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-gray-900">
                        <?php echo htmlspecialchars($reserva['nome_alojamento']); ?>
                    </h3>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo $estado_cor; ?>">
                        <?php echo htmlspecialchars($reserva['estado']); ?>
                    </span>
                </div>
                
                <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($reserva['localizacao']); ?></p>

                <div class="grid grid-cols-2 gap-y-1 text-sm">
                    <p><span class="font-medium">Check-in:</span> <?php echo $check_in; ?></p>
                    <p><span class="font-medium">Total:</span> €<?php echo $preco; ?></p>
                    <p><span class="font-medium">Check-out:</span> <?php echo $check_out; ?></p>
                    <p><span class="font-medium">Hóspedes:</span> <?php echo $num_hospedes; ?> <?php echo $texto_hospedes; ?></p>
                    <p><span class="font-medium">Noites:</span> <?php echo $num_noites; ?> <?php echo $texto_noites; ?></p>
                </div>
                
                <?php if ($pode_avaliar): ?>
                <div class="mt-3 pt-3 border-t">
                    <a href="avaliaralojamento.php?id_alojamento=<?php echo $reserva['id_alojamento']; ?>" 
                       class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-lg shadow-md 
                              text-[#565656] bg-[#c8c8b2] hover:bg-[#565656] hover:text-white transition-all duration-300">
                        Avaliar Estadia
                    </a>
                </div>
                <?php endif; ?>
                
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="alterarpass.php" class="w-full sm:w-auto text-center bg-[#c8c8b2] text-[#565656] font-bold py-2 px-5 rounded-lg hover:bg-[#565656] hover:text-white transition-all duration-300">
            Alterar Password
          </a>
          <a href="apagarconta.php" class="w-full sm:w-auto text-center bg-red-600 text-white font-bold py-2 px-5 rounded-lg hover:bg-red-700 transition-all duration-300">
            Apagar Conta
          </a>
          <a href="logout.php" class="w-full sm:w-auto text-center bg-gray-500 text-white font-bold py-2 px-5 rounded-lg hover:bg-gray-600 transition-all duration-300">
            LOGOUT
          </a>
        </div>
      </div>

    </div> 
  </main>

  <script src="js/global.js" defer></script>

  <?php include 'includes/footer.php'; ?>

</body>
</html>