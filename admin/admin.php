<?php
session_start();
require '../dbconnection.php'; 
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAINEL DE ADMINISTRAÇÃO | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="../imagens/FAVICON.ico">
    <script src="https://cdn.tailwindcss.com"></script> 
</head>

<header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">

      <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
        <a href="<?php echo $homeLink; ?>"> <img src="../imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
        </a>
        <div class="flex flex-col">
          <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-[#565656]">
            Painel de Administração
          </h1>
        </div>
      </div>
    </div>
 </header>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    <nav class="h-2 bg-[#c8c8b2]"></nav>

<main class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-10 flex-grow mb-12">
    <div class="mx-auto max-w-6xl">
        
        <h2 class="text-3xl font-bold text-[#565656] mb-8 border-b-2 border-[#c8c8b2] pb-2">Gestão do Sistema</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="bg-white p-8 rounded-xl shadow-2xl border-t-8 border-[#565656] hover:shadow-3xl transition duration-500 transform hover:-translate-y-1">
                <div class="flex items-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-[#565656] mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <h3 class="text-2xl font-extrabold text-[#565656]">Utilizadores</h3>
                </div>
                <p class="text-gray-600 mb-6 text-base">Gerir contas, dados e estado de bloqueio dos utilizadores.</p>    
                <a href="adminusers.php" class="block w-full text-center bg-[#c8c8b2] text-[#565656] font-bold text-lg rounded-xl px-4 py-3 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer">
                    Ver e gerir utilizadores
                </a>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-2xl border-t-8 border-[#565656] hover:shadow-3xl transition duration-500 transform hover:-translate-y-1">
                <div class="flex items-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-[#565656] mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>    
                    <h3 class="text-2xl font-extrabold text-[#565656]">Alojamentos</h3>
                </div>
                <p class="text-gray-600 mb-6 text-base">Inserir, editar e controlar a visibilidade dos alojamentos.</p>         
                <a href="adminalojamentos.php" class="block w-full text-center bg-[#c8c8b2] text-[#565656] font-bold text-lg rounded-xl px-4 py-3 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer">
                    Ver e gerir alojamentos
                </a>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-2xl border-t-8 border-[#565656] hover:shadow-3xl transition duration-500 transform hover:-translate-y-1">
                <div class="flex items-center mb-3">     
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-[#565656] mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="text-2xl font-extrabold text-[#565656]">Reservas Pendentes</h3>
                </div>
                <p class="text-gray-600 mb-6 text-base">Rever e aprovar/rejeitar reservas que aguardam confirmação.</p> 
                <a href="adminreservaspendentes.php" class="block w-full text-center bg-[#c8c8b2] text-[#565656] font-bold text-lg rounded-xl px-4 py-3 lg:px-3 lg:py-2 xl:px-4 text-sm lg:text-base xl:text-lg hover:bg-[#565656] hover:text-white hover:-translate-y-0.5 transition-transform duration-300 whitespace-nowrap cursor-pointer">
                    Ver e gerir reservas pendentes
                </a>
            </div>

        </div>
    </div>
</main>

<footer class="mt-2 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd]">
  <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; 2025 - ADMINISTRAÇÃO |  ESPAÇO LUSITANO</p>
</footer>

</body>
</html>