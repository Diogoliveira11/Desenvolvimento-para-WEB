<?php
session_start();
require 'dbconnection.php'; 

// Obtém o ID do utilizador logado para usar no formulário de avaliação
$user_id = $_SESSION['id_user'] ?? null;

$alojamento_id = isset($_GET['id']) ? mysqli_real_escape_string($link, $_GET['id']) : die("ID de Alojamento não fornecido.");

$query_detalhes = "SELECT A.*, A.ponto_forte, A.check_in_out, A.contacto, A.regiao FROM alojamento AS A WHERE A.id_alojamento = $alojamento_id";
$result_detalhes = mysqli_query($link, $query_detalhes);
if (!$result_detalhes || mysqli_num_rows($result_detalhes) === 0) { die("Alojamento não encontrado."); }
$alojamento = mysqli_fetch_assoc($result_detalhes);

// Captura a região do alojamento atual
$regiao_atual = $alojamento['regiao'] ?? '';

$query_imagens = "SELECT caminho_ficheiro, imagem_principal FROM imagens WHERE id_alojamento = $alojamento_id AND estado = 1 ORDER BY imagem_principal DESC, id_imagem ASC";
$result_imagens = mysqli_query($link, $query_imagens);
$imagens = mysqli_fetch_all($result_imagens, MYSQLI_ASSOC);

$imagem_principal_path = 'imagens/placeholder.png'; 
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

    // Se não encontrar imagem principal, usa a primeira
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

// DADOS DE AVALIAÇÃO PRINCIPAIS
$query_avaliacoes = "SELECT AVG(avaliacao) AS media, COUNT(avaliacao) AS total FROM avaliacoes WHERE id_alojamento = $alojamento_id";
$result_avaliacoes = mysqli_query($link, $query_avaliacoes);
$dados_avaliacoes = mysqli_fetch_assoc($result_avaliacoes);
$avaliacao_media = number_format($dados_avaliacoes['media'], 1) ?: 'N/A';
$total_avaliacoes = $dados_avaliacoes['total'];

// Busca todas as avaliações para o modal, incluindo o nome do utilizador
$query_detalhes_avaliacoes = "
    SELECT 
        A.avaliacao, A.comentario, A.data_avaliacao, U.utilizador 
    FROM 
        avaliacoes AS A
    JOIN 
        utilizadores AS U ON A.id_user = U.id_user
    WHERE 
        A.id_alojamento = $alojamento_id
    ORDER BY 
        A.data_avaliacao DESC
";
$result_detalhes_avaliacoes = mysqli_query($link, $query_detalhes_avaliacoes);
$detalhes_avaliacoes = mysqli_fetch_all($result_detalhes_avaliacoes, MYSQLI_ASSOC);
$result_detalhes_avaliacoes = mysqli_query($link, $query_detalhes_avaliacoes);
$detalhes_avaliacoes = mysqli_fetch_all($result_detalhes_avaliacoes, MYSQLI_ASSOC);

// ALOJAMENTOS RELACIONADOS
$regiao_sql = mysqli_real_escape_string($link, $regiao_atual);

// Consulta para buscar 4 alojamentos na mesma região e ordena aleatoriamente para variedade
$query_relacionados = "
    SELECT 
        id_alojamento, nome, localizacao, preco_final, preco_base, ponto_forte 
    FROM 
        alojamento 
    WHERE 
        id_alojamento != $alojamento_id AND regiao = '$regiao_sql'
    ORDER BY 
        RAND() 
    LIMIT 
        4
";
$result_relacionados = mysqli_query($link, $query_relacionados);
$alojamentos_relacionados = mysqli_fetch_all($result_relacionados, MYSQLI_ASSOC);

// Para cada alojamento relacionado, busca a imagem principal e a avaliação média
foreach ($alojamentos_relacionados as $key => $rel) {
    $rel_id = $rel['id_alojamento'];
    
    $query_img_rel = "SELECT caminho_ficheiro FROM imagens WHERE id_alojamento = $rel_id AND estado = 1 ORDER BY imagem_principal DESC LIMIT 1";
    $result_img_rel = mysqli_query($link, $query_img_rel);
    $img_rel = mysqli_fetch_assoc($result_img_rel);
    $alojamentos_relacionados[$key]['imagem'] = $img_rel['caminho_ficheiro'] ?? 'imagens/placeholder.png';
    
    $query_avg_rel = "SELECT AVG(avaliacao) AS media FROM avaliacoes WHERE id_alojamento = $rel_id";
    $result_avg_rel = mysqli_query($link, $query_avg_rel);
    $avg_rel = mysqli_fetch_assoc($result_avg_rel);
    $alojamentos_relacionados[$key]['avaliacao_media'] = $avg_rel['media'];
}

$preco_base = $alojamento['preco_base'];
$preco_final = $alojamento['preco_final'] ?? $preco_base;
$tem_final = $preco_final !== null && $preco_final < $preco_base;
$preco_riscado = $preco_base;

$pageTitle = htmlspecialchars($alojamento['nome']);
$pageSubtitle = htmlspecialchars($alojamento['localizacao']);
$total_fotos_na_galeria = count($imagens); 
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALOJAMENTO | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
    <link href="css/nav.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .grid-item-ratio {
            position: relative;
            padding-top: 75%;
            height: 0;
            overflow: hidden;
        }
        .grid-item-ratio img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
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
                $num_miniaturas_exibir = 4; 
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
                            <a href="#" onclick="abrirModalTodasAvaliacoes(event)" 
                               class="text-gray-600 text-sm font-semibold underline cursor-pointer hover:text-gray-800 transition-colors">
                                Baseado em <?php echo $total_avaliacoes; ?> <?php echo $contagem_texto; ?> (ver tudo)
                            </a>
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
                
                <form action="reserva.php" method="GET" class="bg-gray-100 p-6 rounded-lg shadow-lg lg:sticky top-8">
                    
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
                                Tem direito a um desconto de 10% por ser cliente registado (primeira reserva). O mesmo será aplicado na reserva.
                            </div>
                        <?php else: ?>
                            <p class="text-gray-700 space-y-2">
                                Ao criar e iniciar sessão na sua conta, terá acesso imediato a um desconto exclusivo de 10% no preço final.
                            </p>
                            <div class="mt-2">
                                <a href="auth/registo.php" class="inline-block text-sm font-semibold text-[#565656] bg-[#c8c8b2] font-bold hover:bg-[#565656] hover:text-white transition-colors py-2 px-4 rounded-lg">
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
                            <input type="date" id="check_in" name="data_check_in" required min="<?php echo date('Y-m-d'); ?>"
                                onchange="setMinCheckoutDate(this.value)"
                                class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-semibold text-gray-700">Check-out</label>
                            <input type="date" id="check_out" name="data_check_out" required class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label for="quartos" class="block text-sm font-semibold text-gray-700">Quartos</label>
                                <select id="quartos_selecionados" name="quartos_selecionados" required 
                                    onchange="updatePriceDisplay()"
                                    class="mt-1 block w-full p-2 border border-[#c8c8b2] rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900">
                                      <option value="1">1 Quarto</option>
                                      <option value="2">2 Quartos</option>
                                      <option value="3">3 Quartos</option>
                                      <option value="4">4 Quartos</option>
                                      <option value="5">5 Quartos</option>
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
                            <a href="auth\login.php?redirect=checkout" 
                            class="w-full py-3 bg-[#c8c8b2] text-[#565656] font-bold rounded-lg shadow-lg hover:bg-[#565656] hover:text-white transition-colors text-xl text-center inline-block">
                                INICIAR SESSÃO PARA RESERVAR
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

        </div>
    </main>
    
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-2">Alojamentos relacionados com <?php echo htmlspecialchars($alojamento['nome']); ?></h2>
        
        <?php if (!empty($alojamentos_relacionados)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <?php foreach ($alojamentos_relacionados as $rel): 
                $rel_nome = htmlspecialchars($rel['nome']);
                $rel_localizacao = htmlspecialchars($rel['localizacao']);
                $rel_ponto_forte = htmlspecialchars($rel['ponto_forte']);
                
                // Lógica de preço e desconto
                $rel_preco_base = $rel['preco_base'];
                $rel_preco_final = $rel['preco_final'] ?? $rel_preco_base;
                $rel_tem_final = $rel_preco_final !== null && $rel_preco_final < $rel_preco_base;
                
                // Lógica de avaliação
                $rel_avaliacao_raw = $rel['avaliacao_media'];
                $rel_avaliacao = $rel_avaliacao_raw !== null && $rel_avaliacao_raw > 0 
                    ? number_format($rel_avaliacao_raw, 1) 
                    : 'N/A';
            ?>
            
            <a href="alojamento.php?id=<?php echo $rel['id_alojamento']; ?>" class="w-full md:max-w-none md:flex-shrink bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-[-5px] group">
                
                <img src="<?php echo htmlspecialchars($rel['imagem']); ?>" alt="<?php echo $rel_nome; ?>" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                
                <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1 justify-between"> 
                    <div class="flex-shrink-0">
                        <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight"><?php echo $rel_nome; ?></h5>
                        <p class="text-gray-600 text-sm sm:text-base md:text-lg"><?php echo $rel_localizacao; ?></p>
                        
                        <p class="text-gray-400 text-xs sm:text-sm md:text-base">
                            <?php echo $rel_ponto_forte; ?>
                        </p>
                    </div>
                    <div class="flex-shrink-0 mt-4">
                        <div class="flex justify-between items-center">
                            <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                                <span><?php echo $rel_avaliacao; ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline gap-1 sm:gap-2">
                                <?php if ($rel_tem_final): ?> 
                                    <p class="text-red-500 text-xs sm:text-sm md:text-md line-through"><?php echo number_format($rel_preco_base, 0); ?>€</p>
                                <?php endif; ?>
                                <p class="text-black font-bold text-base sm:text-lg md:text-xl"><?php echo number_format($rel_preco_final, 0); ?>€ por noite</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-600 italic">Não foram encontrados outros alojamentos relacionados na região de <?php echo htmlspecialchars($regiao_atual); ?>.</p>
        <?php endif; ?>
    </section>
    
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
    
    <div id="todas-avaliacoes-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
            
            <button onclick="fecharModalTodasAvaliacoes()" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-2">Avaliações de hóspedes - <?php echo $pageTitle; ?></h2>

            <?php if (!empty($detalhes_avaliacoes)): ?>
                <div class="space-y-6">
                    
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <h3 class="text-xl font-semibold mb-3">Resumo da Classificação</h3>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#c8c8b2] text-[#565656] font-extrabold rounded-lg px-4 py-2 text-2xl">
                                <?php echo $avaliacao_media; ?>
                            </div>
                            <span class="text-lg font-semibold text-gray-700">
                                (<?php echo $total_avaliacoes; ?> Avaliações)
                            </span>
                        </div>
                    </div>
                    
                <?php foreach ($detalhes_avaliacoes as $avaliacao): ?>
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex items-center justify-between mb-2">
                            
                            <span class="text-lg font-bold text-[#565656] bg-[#e5e5dd] px-3 py-1 rounded-full">
                                <?php echo htmlspecialchars($avaliacao['avaliacao']); ?>
                            </span>
                            
                            <span class="text-sm text-gray-500">
                                <?php 
                                    setlocale(LC_TIME, 'pt_PT.utf8', 'portuguese'); 
                                    $data_formatada = strftime('%d de %B de %Y', strtotime($avaliacao['data_avaliacao']));   
                                    echo $data_formatada; 
                                ?>
                            </span>
                        </div>
                        
                        <p class="text-base font-semibold text-gray-800 mb-1">
                            <?php echo htmlspecialchars($avaliacao['utilizador']); ?>
                        </p>
                        
                        <p class="text-gray-700 italic">
                            <?php echo nl2br(strip_tags($avaliacao['comentario'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
                    
                </div>
            <?php else: ?>
                <p class="text-gray-600 italic">Este alojamento ainda não tem avaliações detalhadas.</p>
            <?php endif; ?>

        </div>
    </div>
    
    <script>
        // Função para garantir que o check-out é pelo menos um dia depois do check-in
        function setMinCheckoutDate(checkInDate) {
            const checkoutInput = document.getElementById('check_out');
            if (checkInDate) {
                const date = new Date(checkInDate);
                date.setDate(date.getDate() + 1); // Adiciona um dia
                const minDate = date.toISOString().split('T')[0];
                checkoutInput.min = minDate;
                
                // Limpa o valor do check-out se for anterior ao novo mínimo
                if (checkoutInput.value && checkoutInput.value < minDate) {
                    checkoutInput.value = '';
                }
            }
        }
        
        // Controlo da Galeria de Fotos
        const galleryModal = document.getElementById('gallery-modal');
        const openGalleryBtn = document.getElementById('open-gallery-btn');
        const closeGalleryBtn = document.getElementById('close-gallery-btn');

        if (openGalleryBtn) {
            openGalleryBtn.addEventListener('click', () => {
                galleryModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        }
        if (closeGalleryBtn) {
            closeGalleryBtn.addEventListener('click', () => {
                galleryModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        }

        // Controlo do Modal de Todas as Avaliações
        function abrirModalTodasAvaliacoes(event) {
            event.preventDefault();
            const modal = document.getElementById('todas-avaliacoes-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function fecharModalTodasAvaliacoes() {
            const modal = document.getElementById('todas-avaliacoes-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        
        // Controlo do Modal de Formulário de Avaliação
        function fecharModalAvaliacao() {
            const modal = document.getElementById('avaliacao-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        
        // Fechar modais com a tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fecharModalTodasAvaliacoes();
                fecharModalAvaliacao();
                if (!galleryModal.classList.contains('hidden')) {
                     galleryModal.classList.add('hidden');
                     document.body.classList.remove('overflow-hidden');
                }
            }
        });
    </script>

    <script src="js/global.js"></script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>