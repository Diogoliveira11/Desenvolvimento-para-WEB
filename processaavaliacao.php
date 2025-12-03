<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // É mais limpo redirecionar sem dar a entender que a avaliação foi submetida
    header('Location: login.php'); 
    exit;
}

// 2. PROCESSAMENTO SEGURO
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Recolha de dados (não precisamos de mysqli_real_escape_string)
    $id_alojamento = $_POST['id_alojamento'] ?? null;
    $id_user = $_SESSION['id_user'] ?? null; 
    $avaliacao = (int)($_POST['avaliacao'] ?? 0);
    $comentario = $_POST['comentario'] ?? '';
    
    // Validação mínima
    if (empty($id_alojamento) || empty($id_user) || $avaliacao < 1 || $avaliacao > 10 || empty($comentario)) {
        header("Location: alojamento.php?id=$id_alojamento&msg=erro_dados_invalidos");
        exit;
    }

    // 3. QUERY DE INSERÇÃO SEGURA (Prepared Statement)
    $query_insert = "INSERT INTO avaliacoes (id_alojamento, id_user, avaliacao, comentario, data_avaliacao) 
                     VALUES (?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($link, $query_insert);
    
    // Tipos de dados: i (int), d (double/decimal), s (string)
    // id_alojamento(i), id_user(i), avaliacao(d), comentario(s)
    mysqli_stmt_bind_param(
        $stmt, 
        "iids", 
        $id_alojamento, 
        $id_user, 
        $avaliacao, 
        $comentario
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        // Sucesso: Redireciona para a página do alojamento com mensagem
        header("Location: alojamento.php?id=$id_alojamento&msg=sucesso_avaliacao");
        exit;
    } else {
        mysqli_stmt_close($stmt);
        
        // Erro na base de dados
        header("Location: alojamento.php?id=$id_alojamento&msg=erro_bd");
        exit;
    }
    
} else {
    // Acesso direto, redireciona para a home
    header('Location: index.php');
    exit;
}
?>