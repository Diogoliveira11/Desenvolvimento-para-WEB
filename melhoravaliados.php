<?php 
session_start();
require 'dbconnection.php'; 

$pageTitle = 'Alojamento melhor avaliados';
$pageSubtitle = 'Alojamentos com pontuação de 9.0 ou superior';

// 1. Consulta SQL para buscar TODOS os alojamentos com avaliação >= 9.0
$query_avaliados = "
    SELECT 
        A.id_alojamento, 
        A.nome, 
        A.localizacao,
        A.ponto_forte, 
        A.preco_base, 
        A.preco_final, 
        -- CALCULA A MÉDIA EM TEMPO REAL
        AVG(T.avaliacao) AS avaliacao_media,
        -- Obtém a imagem principal usando o JOIN com a tabela I
        I.caminho_ficheiro AS imagem_principal
    FROM 
        alojamento A
    -- Junta com avaliações para calcular a média
    LEFT JOIN 
        avaliacoes T ON A.id_alojamento = T.id_alojamento
    -- Junta com imagens para obter a imagem principal
    INNER JOIN 
        imagens I ON A.id_alojamento = I.id_alojamento
    WHERE
        -- Filtra a imagem principal ANTES do GROUP BY
        I.imagem_principal = 1 
        AND a.estado = 1
    GROUP BY
        A.id_alojamento
    -- FILTRA o resultado da média calculada
    HAVING 
        avaliacao_media >= 9.0
    ORDER BY 
        avaliacao_media DESC, A.preco_final ASC
";
$result_avaliados = mysqli_query($link, $query_avaliados);

if ($result_avaliados) {
    $alojamentos_avaliados = mysqli_fetch_all($result_avaliados, MYSQLI_ASSOC);
} else {
    $alojamentos_avaliados = []; 
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MELHOR AVALIADOS | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?>

    <nav class="h-2 bg-[#c8c8b2]"></nav>

    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-6 flex-grow mb-14">
        <div class="mx-auto">   
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <?php if (empty($alojamentos_avaliados)): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 col-span-full" role="alert">
                        <p class="font-bold">Aviso:</p>
                        <p>De momento, não existem alojamentos com avaliação igual ou superior a 9.0.</p>
                    </div>
                <?php else: ?>

                    <?php 
                    foreach ($alojamentos_avaliados as $alojamento): 
                        
                        $id_alojamento = $alojamento['id_alojamento'];
                        $nome = htmlspecialchars($alojamento['nome']);
                        $localizacao = htmlspecialchars($alojamento['localizacao']);
                        $avaliacao = number_format($alojamento['avaliacao_media'], 1);
                        
                        // Lógica de Preço
                        $preco_base = $alojamento['preco_base'];
                        $preco_final = $alojamento['preco_final'];
                        
                        $tem_desconto = $preco_final !== null && $preco_final < $preco_base;
                        $preco_exibicao = $preco_final ?? $preco_base; // Usa o final, ou o base se o final for nulo
                        
                        // Imagem trazida pela subconsulta
                        $imagem = $alojamento['imagem_principal'] ?? 'imagens/placeholder.png'; 
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
                                        
                                        <?php if ($tem_desconto): ?> 
                                            <p class="text-red-500 text-xs sm:text-sm md:text-md line-through"><?php echo number_format($preco_base, 0); ?>€</p>
                                        <?php endif; ?>
                                        
                                        <p class="text-black font-bold text-base sm:text-lg md:text-xl"><?php echo number_format($preco_exibicao, 0); ?>€ por noite</p>
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