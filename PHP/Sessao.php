<?php
// Informar ao JavaScript apenas se há um usuário autenticado.
require __DIR__ . '/iniciar_sessao.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode([
    'autenticado' => isset($_SESSION['usuario_email']),
    'nome' => $_SESSION['usuario_nome'] ?? null
], JSON_UNESCAPED_UNICODE);
