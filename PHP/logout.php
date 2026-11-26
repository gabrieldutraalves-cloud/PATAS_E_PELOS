<?php
session_start();

// Limpar as variáveis da sessão
session_unset();

// Encerrar a sessão
session_destroy();

// Redirecionar para a página inicial
header("Location: ../index.php");
exit;
?>