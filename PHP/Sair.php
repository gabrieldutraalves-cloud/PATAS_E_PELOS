<?php
// Encerrar a sessão e retornar à página inicial.
require __DIR__ . '/iniciar_sessao.php';
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $parametros = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $parametros['path'], $parametros['domain'], $parametros['secure'], $parametros['httponly']);
}
session_destroy();
header('Location: ../index.html');
exit;
