<?php

header('Content-Type: application/json');

require '../dbconnection.php'; 

// 1. Configurações de Colunas
$tabela_imagem = "imagens"; 
$coluna_id = "id_alojamento";
$coluna_id_imagem = "id_imagem";
$coluna_caminho_imagem = "caminho_ficheiro";
$coluna_principal = "imagem_principal"; 
$coluna_estado_imagem = "estado";

$alojamento_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$response = ['success' => false, 'message' => 'Erro desconhecido.'];

// 2. Verificação de ID e Conexão
if ($alojamento_id <= 0 || !isset($link) || !$link) {
    $response['message'] = 'ID de alojamento inválido ou conexão à base de dados falhou.';
    echo json_encode($response);
    exit;
}

// 3. Preparar e Executar a Query
$query = "SELECT $coluna_id_imagem, $coluna_caminho_imagem, $coluna_principal, $coluna_estado_imagem 
          FROM $tabela_imagem 
          WHERE $coluna_id = ?
          ORDER BY $coluna_principal DESC, $coluna_id_imagem ASC";

$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $alojamento_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $imagens_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        $response['success'] = true;
        $response['images'] = $imagens_data;
    } else {
        $response['message'] = 'Erro ao executar a query: ' . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
} else {
    $response['message'] = 'Erro ao preparar a query: ' . mysqli_error($link);
}

mysqli_close($link);

// 4. Devolver a resposta final em JSON
echo json_encode($response);
?>