<?php 
session_start();
require 'dbconnection.php'; 

function getAlojamentosPorRegiao($link, $regiao) {
    $regiao_segura = mysqli_real_escape_string($link, $regiao);

    $query = "
        SELECT 
            A.id_alojamento, A.nome, A.regiao, A.localizacao, A.preco_base, A.preco_final, A.ponto_forte,
            I.caminho_ficheiro,
            -- CALCULA A MÉDIA DE AVALIAÇÃO EM TEMPO REAL
            AVG(T.avaliacao) AS avaliacao_media
        FROM 
            alojamento AS A
        INNER JOIN 
            imagens AS I ON A.id_alojamento = I.id_alojamento
        LEFT JOIN
            avaliacoes AS T ON A.id_alojamento = T.id_alojamento
        WHERE
            I.imagem_principal = 1 AND A.regiao = '$regiao_segura' AND a.estado = 1
        GROUP BY
            A.id_alojamento
        ORDER BY 
            -- CRITÉRIO ÚNICO: ORDENAÇÃO ALFABÉTICA PELO NOME
            A.nome ASC
    ";
    
    $result = mysqli_query($link, $query);
    
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

// 1. LÓGICA DE TRATAMENTO DA REGIÃO E MAPAS DE PREPOSIÇÕES
// 1.1 Receber o nome da região da URL. Usamos a versão limpa (escaped) para a DB e a original para o mapeamento.
$regiao_selecionada = isset($_GET['regiao']) ? mysqli_real_escape_string($link, $_GET['regiao']) : 'Portugal';
$regiao_para_mapa = isset($_GET['regiao']) ? $_GET['regiao'] : 'Portugal'; // Não escapada para corresponder às chaves do array


// 1.2 Mapeamento de Prefixos (na, no, nos, em)
$prefixos_regiao = [
    'Açores' => 'nos',
    'Aveiro' => 'em',
    'Braga' => 'em',
    'Coimbra' => 'em',
    'Évora' => 'em',
    'Faro' => 'em',
    'Lisboa' => 'em',
    'Madeira' => 'na',
    'Porto' => 'no',
    'Setúbal' => 'em',
    'Portugal' => 'em'
];

// 1.3 Determinar prefixo e construir TÍTULO
$prefixo_titulo = $prefixos_regiao[$regiao_para_mapa] ?? 'em'; 

$pageTitle = 'Alojamentos ' . htmlspecialchars($prefixo_titulo) . ' ' . htmlspecialchars($regiao_selecionada);
$pageSubtitle = 'Descubra as melhores estadias que temos para si!';

// 2. CHAMADA DA FUNÇÃO PARA OBTER DADOS
$alojamentos_regiao = getAlojamentosPorRegiao($link, $regiao_selecionada);
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALOJAMENTO POR REGIÃO | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">

    <meta name="description" content="<?php echo htmlspecialchars($pageSubtitle); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?> 

    <nav class="h-2 bg-[#c8c8b2]"></nav>

    <section class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-6 flex-grow mb-14">
        <div class="mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <?php if (empty($alojamentos_regiao)): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 col-span-full" role="alert">
                        <p class="font-bold">Aviso:</p>
                        <p>Não foram encontrados alojamentos para a região de <?php echo htmlspecialchars($regiao_selecionada); ?>.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($alojamentos_regiao as $alojamento): ?>
                        <?php 
                            // Lógica de Preços e Avaliação
                            $preco_final = $alojamento['preco_final'] ?? $alojamento['preco_base'];
                            $tem_desconto = $alojamento['preco_final'] !== null && $alojamento['preco_final'] < $alojamento['preco_base'];
                            $avaliacao_raw = $alojamento['avaliacao_media'];
                            $avaliacao = $avaliacao_raw !== null && $avaliacao_raw > 0 
                                ? number_format($avaliacao_raw, 1) 
                                : 'N/A'; // Exibe N/A se for NULL ou 0
                        ?>
                        <a href="alojamento.php?id=<?php echo $alojamento['id_alojamento']; ?>" class="w-full transition-transform duration-300 hover:translate-y-[-5px] bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer flex flex-col">
                            <img src="<?php echo htmlspecialchars($alojamento['caminho_ficheiro']); ?>" alt="<?php echo htmlspecialchars($alojamento['nome']); ?>" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                            <div class="p-3 sm:p-4 bg-[#f5f5f2] flex flex-col flex-1"> 
                                <div class="flex-shrink-0">
                                    <h5 class="font-bold text-base sm:text-lg md:text-xl leading-tight"><?php echo htmlspecialchars($alojamento['nome']); ?></h5>
                                    <p class="text-gray-600 text-sm sm:text-base md:text-lg"><?php echo htmlspecialchars($alojamento['localizacao']); ?></p>
                                    
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
                                                <p class="text-red-500 text-xs sm:text-sm md:text-md line-through"><?php echo number_format($alojamento['preco_base'], 0); ?>€</p>
                                            <?php endif; ?>
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