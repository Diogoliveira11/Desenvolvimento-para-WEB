<?php
session_start();
require '../dbconnection.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilizador = trim($_POST['utilizador']);
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];

    $stmt = mysqli_prepare($link, "SELECT id_user FROM utilizadores WHERE utilizador = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $utilizador, $email);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $erro = "Utilizador ou email já existem!";
    } else {
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $estado_ativo = 1;

        $stmt = mysqli_prepare($link, "INSERT INTO utilizadores (utilizador, email, pass, estado) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $utilizador, $email, $pass_hash, $estado_ativo);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php?sucesso=1");
            exit(); 
        } else {
            $erro = "Erro ao criar conta: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTO - ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="..\imagens/FAVICON.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gradient-to-br from-white from-10% via-[#f5f5f0] via-50% to-[#c8c8b2] to-90% min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-gradient-to-r from-[#d8d8c8] to-[#c8c8b2] p-8 text-center text-[#707070] relative">
            <a href="../index.php" class="absolute top-4 right-4 w-10 h-10 bg-gradient-to-br from-[#a8a892] to-[#989882] rounded-full flex items-center justify-center hover:from-[#989882] hover:to-[#a8a892] transition duration-300 shadow-lg text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
            </a>

            <div class="mt-1">
                <h2 class="text-3xl font-bold text-[#707070]">Criar Conta</h2>
                <p class="text-[#707070] opacity-80">Registe-se para começar</p>
            </div>
        </div>

        <div class="p-9">

            <?php if (!empty($erro)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span><?php echo $erro; ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-[#707070]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <input type="text" name="utilizador" placeholder="Nome de utilizador" required class="w-full pl-10 pr-4 py-3 border border-[#d8d8c8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300">
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-[#707070]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <input type="email" name="email" placeholder="Email" required class="w-full pl-10 pr-4 py-3 border border-[#d8d8c8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300">
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-[#707070]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <input type="password" name="pass" placeholder="Password" required class="w-full pl-10 pr-4 py-3 border border-[#d8d8c8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c8c8b2] focus:border-transparent transition-all duration-300">
            </div>

            <div class="flex justify-center">
                <button type="submit" class="w-3/5 bg-[#707070] text-white font-bold py-3 rounded-xl hover:bg-[#c8c8b2] hover:text-[#707070] transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl text-sm flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    CRIAR CONTA
                </button>
            </div>
            </form>

            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-[#d8d8c8]"></div>
                <span class="px-4 text-[#707070] text-sm">ou</span>
                <div class="flex-1 border-t border-[#d8d8c8]"></div>
            </div>

            <div class="flex justify-center">
                <a href="login.php" class="w-3/5 border-2 border-[#d8d8c8] text-[#707070] font-semibold py-2 rounded-xl hover:bg-[#c8c8b2] hover:text-[#707070] transition duration-300 text-sm text-center flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1"></path>
                    </svg>
                    JÁ TEM CONTA? ENTRE
                </a>
            </div>
        </div>

        <div class="bg-[#e8e8dc] p-4 text-center">
            <p class="text-[#707070] text-sm">
                &copy; 2025 Espaço Lusitano · Encontre a sua próxima estadia
            </p>
        </div>
    </div>
</body>
</html>