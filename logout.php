<?php
session_start(); 

    // Apaga todas as variáveis da sessão
    $_SESSION = array();

    // Destrói a sessão
    session_unset();
    session_destroy();

    // Redireciona para o index
    header("Location: index.php");
    exit();
?>