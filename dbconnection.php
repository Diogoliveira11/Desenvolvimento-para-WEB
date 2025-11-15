<?php

$host = 'localhost';
$user = 'web2'; 
$pass = 'web2'; 
$dbname = 'web2';

// 1. Tentar a ligação usando MySQLi
$link = mysqli_connect($host, $user, $pass, $dbname);

// 2. Verificar se a ligação falhou
if (!$link) {
    // Se falhar, mostra o erro e para o script
    die("Erro de ligação à Base de Dados: " . mysqli_connect_error());
}

// 3. Definir o charset para utf8 (boa prática para acentos, etc.)
mysqli_set_charset($link, "utf8");
?>