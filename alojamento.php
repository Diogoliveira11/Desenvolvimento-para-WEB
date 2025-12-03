<?php
session_start();
require 'dbconnection.php'; 

// --- 1. VARIÁVEIS DE SESSÃO E ID DO ALOJAMENTO ---
// Obtém o ID do utilizador logado para usar no formulário de avaliação
$user_id = $_SESSION['id_user'] ?? null; 

$alojamento_id = isset($_GET['id']) ? mysqli_real_escape_string($link, $_GET['id']) : die("ID de Alojamento não fornecido.");

$query_detalhes = "SELECT A.*, A.ponto_forte, A.check_in_out, A.contacto FROM alojamento AS A WHERE A.id_alojamento = $alojamento_id";
$result_detalhes = mysqli_query($link, $query_detalhes);
if (!$result_detalhes || mysqli_num_rows($result_detalhes) === 0) { die("Alojamento não encontrado."); }
$alojamento = mysqli_fetch_assoc($result_detalhes);

$query_imagens = "SELECT caminho_ficheiro, imagem_principal FROM imagens WHERE id_alojamento = $alojamento_id ORDER BY imagem_principal DESC, id_imagem ASC";
$result_imagens = mysqli_query($link, $query_imagens);
$imagens = mysqli_fetch_all($result_imagens, MYSQLI_ASSOC);

$imagem_principal_path = 'imagens/placeholder.png'; // Default placeholder
$galeria_imagens_sem_principal = [];

if (!empty($imagens)) {
    $imagem_principal = null;
    $outras_imagens = [];

    // Tenta encontrar a imagem principal
    foreach ($imagens as $img) {
        if ($img['imagem_principal'] == 1) {
            $imagem_principal = $img;
            break;
        }
    }

    // Se não encontrar imagem principal (com flag=1), usa a primeira
    if ($imagem_principal === null) {
        $imagem_principal = $imagens[0];
    }
    
    $imagem_principal_path = $imagem_principal['caminho_ficheiro'];

    // Filtra as restantes imagens para as miniaturas
    foreach ($imagens as $img) {
        // Inclui todas as imagens que não são a principal (comparando o caminho)
        if ($img['caminho_ficheiro'] !== $imagem_principal_path) {
            $galeria_imagens_sem_principal[] = $img;
        }
    }
}

$query_avaliacoes = "SELECT AVG(avaliacao) AS media, COUNT(avaliacao) AS total FROM avaliacoes WHERE id_alojamento = $alojamento_id";
$result_avaliacoes = mysqli_query($link, $query_avaliacoes);
$dados_avaliacoes = mysqli_fetch_assoc($result_avaliacoes);
$avaliacao_media = number_format($dados_avaliacoes['media'], 1) ?: 'N/A';
$total_avaliacoes = $dados_avaliacoes['total'];

$preco_base = $alojamento['preco_base'];
$preco_final = $alojamento['preco_final'] ?? $preco_base;
$tem_final = $preco_final !== null && $preco_final < $preco_base;
$preco_riscado = $preco_base;

$pageTitle = htmlspecialchars($alojamento['nome']);
$pageSubtitle = htmlspecialchars($alojamento['localizacao']);
$total_fotos_na_galeria = count($imagens); // Total de todas as fotos
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
    <link href="css/nav.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?>
    
    <nav class="bg-[#c8c8b2] h-2"></nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-grow mb-14">

        <div class="grid grid-cols-2 md:grid-cols-4 md:grid-rows-2 gap-2 my-8 rounded-xl overflow-hidden shadow-xl">
            
            <div class="col-span-2 row-span-2 grid-item-ratio">
                <img src="<?php echo htmlspecialchars($imagem_principal_path); ?>" 
                    alt="Vista principal do <?php echo $pageTitle; ?>" 
                    class="rounded-xl">
            </div>
            
            <?php 
                $num_miniaturas_exibir = 4; // Número de miniaturas a mostrar (as 4 caixas à direita)
                $total_remaining_images = count($galeria_imagens_sem_principal);
                
                // Itera pelas miniaturas, limitando-se a 4 ou ao total disponível
                for ($i = 0; $i < min($num_miniaturas_exibir, $total_remaining_images); $i++):
                    $img = $galeria_imagens_sem_principal[$i];
                    
                    $is_last_thumbnail_slot = ($i === ($num_miniaturas_exibir - 1));
                    $remaining_for_button = $total_remaining_images - ($i + 1);
            ?>
            <div class="relative grid-item-ratio rounded-xl shadow-lg">
                <img src="<?php echo htmlspecialchars($img['caminho_ficheiro']); ?>" 
                    alt="Detalhe <?php echo $pageTitle . ' ' . ($i + 1); ?>" 
                    class="rounded-xl <?php echo ($is_last_thumbnail_slot && $remaining_for_button > 0) ? 'brightness-75' : ''; ?>">
                
                <?php if ($is_last_thumbnail_slot && $remaining_for_button > 0): ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <button id="open-gallery-btn" class="bg-white text-gray-900 font-bold py-2 px-5 rounded-lg hover:bg-gray-200 transition-all shadow-lg">
                            Ver Mais (<?php echo $remaining_for_button; ?>)
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php 
                endfor; 
            ?>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="w-full lg:w-2/3">
                <h1 class="text-4xl font-bold text-gray-900"><?php echo $pageTitle; ?></h1>
                <div class="text-lg text-gray-600 mt-1"><?php echo $pageSubtitle; ?></div>
                
                <?php
                    $nota = (float)$avaliacao_media;
                    $classificacao_texto = 'Sem Avaliações';
                    $contagem_texto = $total_avaliacoes == 1 ? 'avaliação' : 'avaliações';

                    if ($total_avaliacoes > 0) {
                        if ($nota >= 9.0) {
                            $classificacao_texto = 'Fabuloso';
                        } elseif ($nota >= 8.0) {
                            $classificacao_texto = 'Excelente';
                        } elseif ($nota >= 7.0) {
                            $classificacao_texto = 'Bom';
                        } elseif ($nota >= 5.0) {
                            $classificacao_texto = 'Razoável';
                        } else {
                            $classificacao_texto = 'Péssimo';
                        }
                    }
                ?>

                <div class="flex items-center gap-3 my-4">
                    
                    <?php if ($total_avaliacoes > 0): ?>
                    
                    <div class="flex items-center gap-3">
                        <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-3 py-2 text-xl flex items-center gap-1">
                            <span><?php echo $avaliacao_media; ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-star-fill w-4 h-4" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        </div>
                        
                        <div class="flex flex-col items-start">                         
                            <span class="text-base font-semibold text-[#565656]">
                                <?php echo $classificacao_texto; ?>
                            </span>                                
                            <span class="text-gray-600 text-sm">
                                Baseado em <?php echo $total_avaliacoes; ?> <?php echo $contagem_texto; ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    
                    <span class="text-gray-600 text-base font-semibold italic p-2 rounded-lg bg-gray-100">
                        Ainda não há avaliações. Seja o primeiro a avaliar!
                    </span>
                    
                    <?php endif; ?>
                </div>
                            
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p><?php echo nl2br(htmlspecialchars($alojamento['descricao'])); ?></p>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Comodidades do alojamento</h3>
                    
                    <div class="flex flex-wrap justify-start gap-x-4 gap-y-3 w-full"> 
                        
                        <?php 
                            $comodidades_array = explode(',', $alojamento['comodidades']);
                            foreach ($comodidades_array as $comodidade): 
                                $comodidade = trim($comodidade);
                                if (!empty($comodidade)):
                        ?>
                            <div class="flex items-center gap-2 text-gray-700 w-full sm:w-[calc(33.333%-10px)]"> 
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <span><?php echo htmlspecialchars($comodidade); ?></span>
                            </div>
                        <?php 
                                endif;
                            endforeach; 
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-1/3">
                
                <form action="checkout.php" method="GET" class="bg-gray-100 p-6 rounded-lg shadow-lg lg:sticky top-8">
                    
                    <input type="hidden" name="id" value="<?php echo $alojamento_id; ?>">

                    <div class="flex flex-col items-start mb-4">
                        <?php if ($tem_final): ?>
                            <div class="text-2xl text-red-500 line-through"><?php echo number_format($preco_riscado, 0); ?>€</div>
                        <?php endif; ?>
                        <div class="flex items-baseline gap-1">
                            <div class="text-4xl font-bold text-gray-900"><?php echo number_format($preco_final, 0); ?>€</div>
                            <div class="text-gray-700 text-lg">por noite</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#c8c8b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Desconto Exclusivo
                        </h3>
                        
                        <?php if (isset($_SESSION['logado'])): ?>
                            <div class="font-semibold bg-[#e5e5dd] text-[#565656] p-3 rounded-lg border-gray-900">
                                Tem direito a um desconto de 10% por ser cliente registado. O mesmo será aplicado no checkout.
                            </div>
                        <?php else: ?>
                            <p class="text-gray-700 space-y-2">
                                Ao criar e iniciar sessão na sua conta, terá acesso imediato a um desconto exclusivo de 10% no preço final.
                            </p>
                            <div class="mt-2">
                                <a href="signin.php" class="inline-block text-sm font-semibold text-[#565656] bg-[#c8c8b2] font-bold hover:bg-[#565656] hover:text-white transition-colors py-2 px-4 rounded-lg">
                                    Registe-se Agora
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php 
                        $ci_co_raw = $alojamento['check_in_out'] ?? 'N/D';
                        $contacto_raw = $alojamento['contacto'] ?? 'N/D';
                        $ci_co_formatado = str_replace('/', ' / ', htmlspecialchars($ci_co_raw));
                        $contacto_formatado = is_numeric($contacto_raw) ? number_format($contacto_raw, 0, '', ' ') : htmlspecialchars($contacto_raw);
                    ?>
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Informações úteis</h3>
                        
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-4 bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                <div class="p-2 rounded-full bg-[#e5e5dd] text-[#565656] flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Check-in / Check-out</p>
                                    <p class="text-base font-bold text-gray-900"><?php echo $ci_co_formatado; ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                <div class="p-2 rounded-full bg-[#e5e5dd] text-[#565656] flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Contacto</p>
                                    <p class="text-base font-bold text-gray-900"><?php echo $contacto_formatado; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 border-y py-4 border-gray-300">
                        <div>
                            <label for="check_in" class="block text-sm font-semibold text-gray-700">Check-in</label>
                            <input type="date" id="check_in" name="check_in" required min="<?php echo date('Y-m-d'); ?>"
                                onchange="setMinCheckoutDate(this.value)"
                                class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-semibold text-gray-700">Check-out</label>
                            <input type="date" id="check_out" name="check_out" required
                                class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label for="hospedes" class="block text-sm font-semibold text-gray-700">Hóspedes (Máx. 4)</label>
                            <select id="hospedes" name="hospedes" required
                                    class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                                <option value="1">1 Hóspede</option>
                                <option value="2" selected>2 Hóspedes (Preço Base)</option>
                                <option value="3">3 Hóspedes (+ Suplemento)</option>
                                <option value="4">4 Hóspedes (+ Suplemento)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                            <button type="submit" 
                                    class="w-full py-3 bg-[#c8c8b2] text-[#565656] font-bold rounded-lg shadow-lg hover:bg-[#565656] hover:text-white transition-colors text-xl">
                                VERIFICAR PREÇO E RESERVAR
                            </button>
                        <?php else: ?>
                            <a href="login.php?redirect=checkout" 
                               class="w-full py-3 bg-[#c8c8b2] text-[#565656] font-bold rounded-lg shadow-lg hover:bg-[#565656] hover:text-white transition-colors text-xl text-center inline-block">
                                INICIAR SESSÃO PARA RESERVAR
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

        </div>
    </main>
    <div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-4 rounded-lg shadow-xl max-w-6xl w-11/12 max-h-[90vh] overflow-y-auto relative">
            
            <button id="close-gallery-btn" class="absolute top-2 right-4 text-gray-500 hover:text-gray-900 text-3xl font-bold z-10">&times;</button>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Galeria de Fotos</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($imagens as $img): ?>
                    
                    <div class="relative overflow-hidden rounded-lg shadow-md grid-item-ratio"> 
                        
                        <img src="<?php echo htmlspecialchars($img['caminho_ficheiro']); ?>" 
                            alt="Galeria Imagem" 
                            class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    
                <?php endforeach; ?>
            </div>
        
        </div>
    </div>


<div id="avaliacao-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl p-6 shadow-2xl w-full max-w-lg relative">
            
            <button onclick="fecharModalAvaliacao()" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Avaliar Alojamento</h3>
            <h4 class="text-xl text-gray-600 mb-6"><?php echo $pageTitle; ?></h4>

            <form action="processa_avaliacao.php" method="POST" class="space-y-6">
                
                <input type="hidden" name="id_alojamento" value="<?php echo $alojamento_id; ?>">
                <input type="hidden" name="id_user" value="<?php echo $user_id; ?>"> 

                <div>
                    <label for="modal_avaliacao" class="block text-lg font-semibold text-gray-900 mb-2">
                        Nota de Satisfação (1 a 10):
                    </label>
                    <input type="number" name="avaliacao" id="modal_avaliacao" min="1" max="10" required
                           class="w-full p-3 border border-[#c8c8b2] rounded-lg shadow-sm transition-colors
                                  focus:outline-none focus:ring-2 focus:ring-[#565656] focus:border-[#565656]">
                    </div>

                <div>
                    <label for="modal_comentario" class="block text-lg font-semibold text-gray-900 mb-2">
                        O seu Comentário (Obrigatório):
                    </label>
                    <textarea name="comentario" id="modal_comentario" rows="4" required
                              class="w-full p-3 border border-[#c8c8b2] rounded-lg shadow-sm transition-colors
                                     focus:outline-none focus:ring-2 focus:ring-[#565656] focus:border-[#565656]"
                              placeholder="Descreva a sua experiência..."></textarea>
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full py-3 px-4 text-base font-semibold rounded-lg shadow-md 
                                    text-[#565656] bg-[#c8c8b2] hover:bg-[#565656] hover:text-white transition-colors duration-150">
                            Enviar Avaliação
                        </button>
                    </div>
            </form>

        </div>
    </div>
</div>
    
    <script src="js/global.js" defer></script>
    
    <?php include 'includes/footer.php'; ?>

</body>
</html>