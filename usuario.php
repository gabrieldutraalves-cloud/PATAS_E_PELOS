<?php
// Recuperar a sessão e impedir acesso sem login.
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: Login.html');
    exit;
}
$cliente_id = (int) $_SESSION['cliente_id'];
$agendamentos = [];
$mensagem = '';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $patas_e_pelos = mysqli_connect('localhost', 'root', '', 'patas_e_pelos');
    mysqli_set_charset($patas_e_pelos, 'utf8mb4');

    // Buscar somente nome e e-mail do cliente autenticado, sem consultar a senha.
    $sql = 'SELECT nome, email FROM clientes WHERE id = ?';
    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $cliente_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nome, $email);
    $encontrado = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if (!$encontrado) {
        header('Location: PHP/logout.php');
        exit;
    }

    // Buscar os agendamentos do mesmo cliente para mostrar seus protocolos.
    $sql = 'SELECT id, unidade, servico, data_agendamento FROM agendamentos WHERE cliente_id = ? ORDER BY data_agendamento DESC, id DESC';
    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $cliente_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $unidade, $servico, $data);
    while (mysqli_stmt_fetch($stmt)) {
        $agendamentos[] = ['id' => $id, 'unidade' => $unidade, 'servico' => $servico, 'data' => $data];
    }
    mysqli_stmt_close($stmt);
    mysqli_close($patas_e_pelos);
} catch (mysqli_sql_exception $erro) {
    http_response_code(500);
    $mensagem = 'Não foi possível carregar os dados da conta. Tente novamente.';
}
$unidades = ['UnidadeCentro' => 'Unidade Centro', 'UnidadeSul' => 'Unidade Sul'];
$servicos = ['banho&tosaSimples' => 'Banho e tosa simples', 'banho&tosa_c/_hidratacao' => 'Banho e tosa com hidratação', 'banho&tosaPremium' => 'Banho e tosa premium'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha conta | Patas &amp; Pelos</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Área individual do cliente do Patas &amp; Pelos.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="CSS/usuario.css">
    <link rel="stylesheet" href="CSS/ajustes-visuais.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap">
    <link rel="icon" type="image/webp" href="Images/favicon.webp">
</head>
<body class="pagina-usuario">
    <header class="header">
        <a href="index.php"><img class="header_img" src="Images/inicio.webp" alt="Página inicial" width="421" height="421"></a>
        <nav aria-label="Navegação principal" class="nav"><ul><li>Minha conta</li><li><a href="PHP/logout.php">Sair</a></li></ul></nav>
    </header>
    <main class="conta-conteudo">
        <?php if ($mensagem !== '') { ?>
            <section class="conta-card"><p><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p><a class="btn-unidade" href="index.php">Voltar ao início</a></section>
        <?php } else { ?>
            <h1>Olá, <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>!</h1>
            <p class="conta-intro">Consulte suas informações e acompanhe seus agendamentos.</p>
            <div class="conta-dados">
                <section class="conta-card">
                    <h2>Dados pessoais</h2>
                    <dl>
                        <dt>Nome</dt><dd><?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></dd>
                        <dt>E-mail</dt><dd><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></dd>
                    </dl>
                </section>
                <section class="conta-card">
                    <h2>Dados de pagamento</h2>
                    <dl>
                        <dt>Cartão demonstrativo</dt><dd>•••• 0000 (fictício)</dd>
                        <dt>Outra forma de pagamento</dt><dd>Pix simulado</dd>
                    </dl>
                    <p class="conta-aviso">Apenas para demonstração. Nenhum dado financeiro é solicitado ou armazenado.</p>
                </section>
            </div>
            <section class="conta-card">
                <h2>Meus agendamentos</h2>
                <?php if (!$agendamentos) { ?>
                    <p>Você ainda não possui agendamentos.</p>
                <?php } else { ?>
                    <div class="conta-lista">
                        <?php foreach ($agendamentos as $item) { ?>
                            <article>
                                <div>
                                    <h3><?= htmlspecialchars($servicos[$item['servico']] ?? $item['servico'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p><?= htmlspecialchars($unidades[$item['unidade']] ?? $item['unidade'], ENT_QUOTES, 'UTF-8') ?> · <?= date('d/m/Y \à\s H:i', strtotime($item['data'])) ?></p>
                                    <p><strong>Protocolo:</strong> <?= (int) $item['id'] ?></p>
                                </div>
                                <a class="btn-unidade" href="status.php?protocolo=<?= (int) $item['id'] ?>">Ver status</a>
                            </article>
                        <?php } ?>
                    </div>
                <?php } ?>
                <a class="btn-unidade" href="agendamento.html">Novo agendamento</a>
            </section>
        <?php } ?>
    </main>
</body>
</html>
