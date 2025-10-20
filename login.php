<?php
session_start(); // ✅ ADICIONAR
include 'dbconnection.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilizador = trim($_POST['utilizador']);
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    
    // Verifica se já existe
    $check_sql = "SELECT * FROM utilizadores WHERE utilizador = '$utilizador' OR email = '$email'";
    $check_result = mysqli_query($link, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $erro = "Utilizador ou email já existem!";
    } else {
        // Encriptar a password
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        
        // Insere novo utilizador
        $insert_sql = "INSERT INTO utilizadores (utilizador, email, pass) VALUES ('$utilizador', '$email', '$pass_hash')";
        
        if (mysqli_query($link, $insert_sql)) {
            // ✅ OPÇÃO: Login automático após registo
            $id_novo_user = mysqli_insert_id($link); // Pega o ID do novo utilizador
            
            $_SESSION['iduser'] = $id_novo_user;
            $_SESSION['utilizador'] = $utilizador;
            $_SESSION['logado'] = true;
            
            header("Location: paginainicial.php");
            exit();
        } else {
            $erro = "Erro ao criar conta: " . mysqli_error($link);
        }
    }
}
?>

<h2>Login</h2>
<form method="POST" action="autentica.php" onsubmit="return avaliar(this)">
    <input type="text" name="utilizador" placeholder="Utilizador" required>
    <input type="password" name="pass" placeholder="Password" required>
    <button type="submit">Entrar</button>
</form>
<p><a href="registo.php">Registar</a></p>