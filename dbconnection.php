<?php

$host = 'localhost';
$user = 'web2';
$pass = 'web2';
$dbname = 'web2';

$link = mysqli_connect($host, $user, $pass, $dbname);

if (!$link) {
    die("Erro de ligação à Base de Dados: " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");
?>