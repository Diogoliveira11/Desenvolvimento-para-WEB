<?php
session_start();
include 'dbconnection.php';

$utilizador = trim($_POST['utilizador']);
$pass = $_POST['pass'];

$sql = "SELECT iduser, utilizador, pass FROM utilizadores WHERE utilizador = '$utilizador'";
$result = mysqli_query($link, $sql);

if($row = mysqli_fetch_assoc($result)) {
    // VERIFICAÇÃO DA PASSWORD (importante!)
    if (password_verify($pass, $row['pass'])) {
        // ✅ DEFINIR A SESSÃO CORRETAMENTE
        $_SESSION['iduser'] = $row['iduser'];
        $_SESSION['utilizador'] = $row['utilizador'];
        $_SESSION['logado'] = true; // ← ESTA LINHA É ESSENCIAL!
        
        // Confirmar que a sessão foi guardada
        session_write_close();
        
        header("Location: paginainicial.php");
        exit();
    }
}

// Se falhou o login
header("Location: login.php?erro=1");
exit();
?>