<?php
// teste_sessao.php
session_start();

echo "<h1>Teste de Sessão</h1>";

// Simular login
$_SESSION['logado'] = true;
$_SESSION['iduser'] = 123;
$_SESSION['utilizador'] = 'teste';

echo "<p>Sessão configurada. Agora <a href='paginainicial.php'>tente aceder à paginainicial.php</a></p>";
echo "<pre>SESSÃO: ";
print_r($_SESSION);
echo "</pre>";
?>