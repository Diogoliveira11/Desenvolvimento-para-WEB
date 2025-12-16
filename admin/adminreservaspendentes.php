    <?php

    session_start();
    require '../dbconnection.php'; 

    $homeLink = "admin.php"; 
    $adminLink = "admin.php"; 

    // 1. Consulta para obter todas as reservas Pendentes
    $query_pendentes = "
        SELECT 
            R.*, A.nome AS nome_alojamento, 
            COALESCE(U.email, 'Utilizador Não Registado') AS email_utilizador
        FROM 
            reservas R
        JOIN 
            alojamento A ON R.id_alojamento = A.id_alojamento
        LEFT JOIN
            utilizadores U ON R.id_utilizador = U.id_user
        WHERE 
            R.estado = 'Pendente' 
        ORDER BY 
            R.data_reserva ASC
    ";

    $result_pendentes = mysqli_query($link, $query_pendentes);
    $reservas = mysqli_fetch_all($result_pendentes, MYSQLI_ASSOC);
    mysqli_close($link); // Fecha a conexão após a consulta

    ?>
    <!DOCTYPE html>
    <html lang="pt-pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RESERVAS PENDENTES | ESPAÇO LUSITANO</title>
        <link rel="icon" type="image/png" href="../imagens/FAVICON.ico">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-brand-dark { background-color: #565656; }
            .text-brand-dark { color: #565656; }
            .bg-brand-light { background-color: #e5e5dd; }
            .border-brand-nav { border-color: #c8c8b2; }
            .hover-brand-dark:hover { background-color: #3a3a3a; }
            
            body { 
                display: flex; 
                flex-direction: column; 
                min-height: 100vh;
            }
            main {
                flex-grow: 1;
            }
        </style>
    </head>

    <header class="w-full py-3 relative bg-brand-light header-compact">
        <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">
            <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
                <a href="<?php echo $adminLink; ?>"> 
                    <img src="../imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
                </a>
                <div class="flex flex-col">
                    <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-brand-dark">
                        Gestão de Reservas
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <body class="bg-gray-50 font-sans flex flex-col min-h-screen">
        <nav class="h-2 bg-[#c8c8b2]"></nav>

    <main class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-10 mb-12">

        <div class="mx-auto max-w-5xl">
            <h2 class="text-3xl font-bold text-brand-dark mb-8 border-b-2 border-brand-nav pb-2 flex items-center">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                
                Reservas pendentes de confirmação (<span id="total-reservas-count"><?php echo count($reservas); ?></span>)
            </h2>
        </div>

        <div id="sucesso-popup" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="bg-brand-dark text-white p-4 rounded-lg shadow-xl flex items-center space-x-3">
                <svg class="w-6 h-6 flex-shrink-0 text-[#c8c8b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold text-lg"></span>
            </div>
        </div>
        
        <div id="reservas-lista" class="space-y-6 mx-auto max-w-5xl">
            
            <?php if (empty($reservas)): ?>
                <p class="text-lg text-gray-600 bg-white p-6 rounded-xl shadow-lg border-t-4 border-brand-dark">Não há reservas pendentes de pagamento no momento.</p>
            <?php else: ?>
                
                <?php foreach ($reservas as $reserva): ?>
                
                <div id="reserva-<?php echo $reserva['id_reserva']; ?>" 
                    class="bg-white p-6 rounded-xl shadow-2xl flex flex-col md:flex-row justify-between items-start md:items-center transition duration-300 hover:shadow-xl border-t-8 border-[#565656]">
                    
                        <div class="space-y-2 mb-4 md:mb-0">
                        <p class="font-black text-2xl text-brand-dark">Reserva #<?php echo $reserva['id_reserva']; ?></p>
                        <p class="text-brand-dark font-extrabold text-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <?php echo htmlspecialchars($reserva['nome_alojamento']); ?>
                        </p>

                        <p class="text-gray-700 text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Cliente: <?php echo htmlspecialchars($reserva['nome_cliente'] . ' ' . $reserva['apelido_cliente']); ?> (<?php echo htmlspecialchars($reserva['email_utilizador']); ?>)
                        </p>

                        <p class="text-gray-600 text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                            Período: <?php echo date('d/m/Y', strtotime($reserva['data_check_in'])) . ' a ' . date('d/m/Y', strtotime($reserva['data_check_out'])); ?>
                        </p>
                        
                        <div class="mt-3 p-3 bg-brand-light rounded-lg text-sm border border-brand-nav">
                            <p class="font-bold text-brand-dark">Detalhes do Pagamento:</p>
                            <p>Entidade: <span class="font-mono"><?php echo htmlspecialchars($reserva['entidade_pagamento']); ?></span></p>
                            <p>Referência: <span class="font-mono"><?php echo htmlspecialchars($reserva['referencia_pagamento']); ?></span></p>
                            <p>Valor: <span class="text-brand-dark"><?php echo number_format($reserva['preco_total'], 2, ',', ''); ?>€</span></p>
                        </div>
                        <?php 
                            $data_exp = new DateTime($reserva['data_reserva']);
                        ?>
                        <p class="text-xs text-gray-500 pt-1">Reservado em: <?php echo $data_exp->format('H:i:s, d-m-Y'); ?></p>
                    </div>
                    
                    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-3 md:ml-4 flex-shrink-0">
                        
                        <button onclick="cancelarReserva(<?php echo $reserva['id_reserva']; ?>, '<?php echo htmlspecialchars($reserva['nome_alojamento']); ?>')"
                                id="btn-cancel-<?php echo $reserva['id_reserva']; ?>"
                                class="btn-cancelar bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition duration-150 shadow-lg whitespace-nowrap">
                            Cancelar Reserva
                        </button>
                        
                        <button onclick="confirmarPagamento(<?php echo $reserva['id_reserva']; ?>)" 
                                id="btn-confirm-<?php echo $reserva['id_reserva']; ?>"
                                class="btn-confirmar bg-brand-dark hover-brand-dark text-white font-bold py-3 px-6 rounded-xl transition duration-150 shadow-lg whitespace-nowrap">
                            Confirmar Pagamento
                        </button>
                        
                    </div>
                </div>
                
                <?php endforeach; ?>
                
            <?php endif; ?>
            
        </div>
    </main>

    <footer class="mt-auto py-2 sm:py-4 lg:py-6 text-center text-brand-dark bg-brand-light">
        <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; <?php echo date('Y'); ?> - ADMINISTRAÇÃO | ESPAÇO LUSITANO</p>
    </footer>

    <script>
        // Função para exibir o popup de sucesso
        function showSuccessPopup(message) {
            const popup = document.getElementById('sucesso-popup');
            const span = popup.querySelector('span');
            
            span.textContent = message;
            popup.classList.remove('hidden', 'opacity-0');
            popup.classList.add('flex'); 

            requestAnimationFrame(() => {
                popup.classList.add('opacity-100');
            });

            setTimeout(() => {
                popup.classList.remove('opacity-100');
                setTimeout(() => {
                    popup.classList.add('hidden');
                    popup.classList.remove('flex');
                }, 300);
            }, 3000); 
        }  

        function updateListAfterAction(idReserva) {
            const row = document.getElementById(`reserva-${idReserva}`);
            if (row) row.remove();
            
            // Verifica se a lista ficou vazia
            const lista = document.getElementById('reservas-lista');
            const currentReservas = lista.querySelectorAll('div[id^="reserva-"]');

            if(currentReservas.length === 0) { 
                // Se não houver mais reservas, exibe a mensagem de vazio com o estilo novo
                lista.innerHTML = '<p class="text-lg text-gray-600 bg-white p-6 rounded-xl shadow-lg border-t-4 border-brand-dark">Não há reservas pendentes de pagamento no momento.</p>';
            }
        }

        function confirmarPagamento(idReserva) {
            const button = document.getElementById(`btn-confirm-${idReserva}`);

            const originalText = button.textContent;
            button.textContent = 'A confirmar...';
            button.disabled = true;

            fetch('adminprocessaconfirmacao.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_reserva=${idReserva}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha na comunicação HTTP: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessPopup('Reserva ' + idReserva + ' confirmada!'); 
                    updateListAfterAction(idReserva);
                } else {
                    alert('ERRO ao confirmar Reserva ' + idReserva + ': ' + (data.details || data.message));
                }
            })
            .catch(error => {
                console.error('Erro de rede ou servidor:', error);
                alert('Ocorreu um erro de comunicação ao confirmar. Verifique se adminprocessaconfirmacao.php existe.');
            })
            .finally(() => {
                if(document.getElementById(`reserva-${idReserva}`)) { 
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        }
        
        function cancelarReserva(idReserva, nomeAlojamento) {
            const button = document.getElementById(`btn-cancel-${idReserva}`);
            
            const originalText = button.textContent;
            button.textContent = 'A cancelar...';
            button.disabled = true;
            
            fetch('../cancelarreserva.php', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_reserva=${idReserva}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha na comunicação HTTP: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessPopup('Cancelamento efetuado! Disponibilidade reposta.'); 
                    updateListAfterAction(idReserva);
                } else {
                    alert('ERRO ao cancelar Reserva ' + idReserva + ': ' + (data.details || data.message));
                }
            })
            .catch(error => {
                console.error('Erro de rede ou servidor:', error);
                alert('Ocorreu um erro de comunicação ao cancelar.'); 
            })
            .finally(() => {
                if(document.getElementById(`reserva-${idReserva}`)) { 
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        }

    </script>
    </body>
    </html>