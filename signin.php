<?php
// Conecta com a base de dados
include 'dbconnection.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilizador = trim($_POST['utilizador']);
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    
    // ✅ CORREÇÃO: Usar iduser em vez de id
    $stmt = mysqli_prepare($link, "SELECT iduser FROM utilizadores WHERE utilizador = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $utilizador, $email);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $erro = "Utilizador ou email já existem!";
    } else {
        // Encriptar a password
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        
        // ✅ SEGURO - Insere novo utilizador com Prepared Statement
        $stmt = mysqli_prepare($link, "INSERT INTO utilizadores (utilizador, email, pass) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $utilizador, $email, $pass_hash);
        
        if (mysqli_stmt_execute($stmt)) {
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
    <title>SIGNIN</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { background: #007bff; color: white; padding: 10px; border: none; width: 100%; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h2>SIGNIN</h2>
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