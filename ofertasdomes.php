<?php 
session_start();
require 'dbconnection.php'; 

// 1. Configuração do Locale e Títulos
setlocale(LC_TIME, 'pt_PT.utf8', 'portuguese', 'pt_PT'); 
$mes_atual = strftime('%B');
$mes_formatado = ucwords($mes_atual);

$pageTitle = 'Ofertas do Mês';
$pageSubtitle = 'Poupe em estadias para este mês de ' . htmlspecialchars($mes_formatado);

// 2. Consulta SQL para buscar TODOS os alojamentos em OFERTA (com JOIN para a imagem)
$query_ofertas = "
    SELECT 
        A.id_alojamento, 
        A.nome, 
        A.localizacao, 
        A.ponto_forte,
        A.preco_base, 
        A.preco_final, 
        -- CALCULA A MÉDIA DE AVALIAÇÃO EM TEMPO REAL
        AVG(T.avaliacao) AS avaliacao_media,
        I.caminho_ficheiro AS imagem_principal 
    FROM 
        alojamento A
    LEFT JOIN 
        imagens I ON A.id_alojamento = I.id_alojamento AND I.imagem_principal = 1
    -- JUNÇÃO NECESSÁRIA PARA O CÁLCULO DA MÉDIA
    LEFT JOIN 
        avaliacoes T ON A.id_alojamento = T.id_alojamento
    WHERE 
        A.preco_final IS NOT NULL 
        AND A.preco_final < A.preco_base
    GROUP BY
        A.id_alojamento
    ORDER BY 
        (A.preco_base - A.preco_final) DESC
";

$result_ofertas = mysqli_query($link, $query_ofertas);

if ($result_ofertas) {
    $alojamentos_ofertas = mysqli_fetch_all($result_ofertas, MYSQLI_ASSOC);
} else {
    $alojamentos_ofertas = []; 
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFERTAS DO MÊS - ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">

    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?>

    <nav class="h-2 bg-[#c8c8b2]"></nav>

    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-6 flex-grow mb-14">
        <div class="mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <?php if (empty($alojamentos_ofertas)): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 col-span-full" role="alert">
                        <p class="font-bold">Aviso:</p>
                        <p>De momento, não existem ofertas disponíveis para este mês.</p>
                    </div>
                <?php else: ?>

                    <?php 
                    foreach ($alojamentos_ofertas as $alojamento): 
                        
                        $id_alojamento = $alojamento['id_alojamento'];
                        $nome = htmlspecialchars($alojamento['nome']);
                        $localizacao = htmlspecialchars($alojamento['localizacao']);

                        // Verifica se AVG retornou um valor NULL (sem avaliações)
                        $avaliacao_raw = $alojamento['avaliacao_media'];
                        $avaliacao = $avaliacao_raw !== null && $avaliacao_raw > 0 
                            ? number_format($avaliacao_raw, 1) 
                            : 'N/A'; // Exibe N/A se for NULL ou 0

                        $preco_base = $alojamento['preco_base'];
                        $preco_final = $alojamento['preco_final'];
                        
                        // Se a imagem falhar, usamos um placeholder, mas a query deve trazer a imagem principal
                        $imagem = $alojamento['imagem_principal'] ?? 'imagens/placeholder.png'; 
                        
                        $tem_desconto = true; 
                    ?>

                    <a href="alojamento.php?id=<?php echo $id_alojamento; ?>" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
                        
                        <img src="<?php echo $imagem; ?>" alt="<?php echo $nome; ?>" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        
                        <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
                            <div class="flex-shrink-0">
                                <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight"><?php echo $nome; ?></h5>
                                <p class="text-gray-600 text-sm sm:text-base md:text-lg"><?php echo $localizacao; ?></p>
                                
                                <p class="text-gray-400 text-xs sm:text-sm md:text-base">
                                     <?php echo htmlspecialchars($alojamento['ponto_forte']); ?>
                                </p>
                            </div>
                            <div class="flex-1"></div>
                            <div class="flex-shrink-0 mt-4">
                                <div class="flex justify-between items-center">
                                    <div class="bg-[#c8c8b2] text-[#565656] font-bold rounded-lg px-2 py-1 text-xs sm:text-sm flex items-center gap-1">
                                        <span><?php echo $avaliacao; ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill w-3 h-3" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                    </div>
                                    <div class="flex items-baseline gap-1 sm:gap-2">
                                        
                                        <p class="text-red-500 text-xs sm:text-sm md:text-md line-through"><?php echo number_format($preco_base, 0); ?>€</p>
                                        
                                        <p class="text-black font-bold text-base sm:text-lg md:text-xl"><?php echo number_format($preco_final, 0); ?>€ por noite</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <script src="js/global.js" defer></script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>