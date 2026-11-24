<?php
// Proteger os dados do usuário: exigir sessão antes de exibir a página.
require __DIR__ . '/iniciar_sessao.php';
if (!isset($_SESSION['usuario_email'])) {
    header('Location: ../Login.html');
    exit;
}
$nome = htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['usuario_email'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do usuário | Patas & Pelos</title>
    <link rel="stylesheet" href="../CSS/geral.css">
</head>
<body>
<!-- Início Cabeçalho -->
<header class="header">
    <a href="../index.html" aria-label="Voltar ao início"><img class="header_img" src="../Images/inicio.png" alt="Patas & Pelos"></a>
    <nav class="nav"><a href="../Loja.html">Loja</a><a href="../agendamento.html">Agendamento</a><a href="../Entregas.html">Entregas</a><a href="Sair.php">Sair</a></nav>
</header>
<!-- Fim Cabeçalho -->
<main class="container">
    <h1 class="page-title">Minha conta</h1>
    <section class="card" style="max-width:660px; margin:30px auto">
        <h2>Dados do usuário</h2>
        <p><strong>Nome:</strong> <?= $nome ?></p>
        <p><strong>E-mail:</strong> <?= $email ?></p>
        <p class="note">A edição de dados, os pedidos e os agendamentos serão integrados ao banco em outra etapa.</p>
        <a class="btn" href="../Loja.html">Explorar loja</a>
        <a class="btn btn-outline" href="Sair.php">Sair da conta</a>
    </section>
</main>
<!-- Início Rodapé -->
<footer class="footer"><ul class="footer-list"><li>© Patas & Pelos</li><li><a href="../Suporte.html">Suporte</a></li></ul></footer>
<!-- Fim Rodapé -->
</body>
</html>
