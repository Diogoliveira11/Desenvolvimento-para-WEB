<?php
// Conecta com a base de dados
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
            // ✅ CORREÇÃO: Redireciona para o LOGIN, não para a página inicial
            header("Location: login.php?sucesso=1");
            exit();
        } else {
            $erro = "Erro ao criar conta: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registo</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { background: #007bff; color: white; padding: 10px; border: none; width: 100%; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h2>Registo</h2>
    <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="POST">
        <input type="text" name="utilizador" placeholder="Utilizador" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="pass" placeholder="Password" required>
        <button type="submit">Registar</button>
    </form>
    <p>Já tens conta? <a href="login.php">Faz login</a></p>
</body>
</html>