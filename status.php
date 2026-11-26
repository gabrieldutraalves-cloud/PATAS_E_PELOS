<?php
// Verificar se o cliente está logado.
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: Login.html');
    exit;
}
$cliente_id = (int) $_SESSION['cliente_id'];
$protocolo = filter_input(INPUT_GET, 'protocolo', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$agendamento = false;
$mensagem = '';

// Buscar somente o agendamento que pertence ao cliente logado.
if ($protocolo === false || $protocolo === null) {
    $mensagem = 'Informe um protocolo numérico válido.';
} else {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $patas_e_pelos = mysqli_connect('localhost', 'root', '', 'patas_e_pelos');
        mysqli_set_charset($patas_e_pelos, 'utf8mb4');
        $sql = 'SELECT unidade, servico, data_agendamento FROM agendamentos WHERE id = ? AND cliente_id = ?';
        $stmt = mysqli_prepare($patas_e_pelos, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $protocolo, $cliente_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $unidade, $servico, $data);
        $agendamento = mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($patas_e_pelos);
        if (!$agendamento) {
            $mensagem = 'Agendamento não encontrado na sua conta.';
        }
    } catch (mysqli_sql_exception $erro) {
        http_response_code(500);
        $mensagem = 'Não foi possível consultar o agendamento. Tente novamente.';
    }
}

$novo = $agendamento && (int) ($_SESSION['novo_agendamento'] ?? 0) === $protocolo;
if ($novo) {
    unset($_SESSION['novo_agendamento']);
}
$unidades = ['UnidadeCentro' => 'Unidade Centro', 'UnidadeSul' => 'Unidade Sul'];
$servicos = ['banho&tosaSimples' => 'Banho e tosa simples', 'banho&tosa_c/_hidratacao' => 'Banho e tosa com hidratação', 'banho&tosaPremium' => 'Banho e tosa premium'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status do agendamento | Patas &amp; Pelos</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Consulte o status do seu agendamento no Patas &amp; Pelos.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/status.css">
    <link rel="icon" type="image/webp" href="Images/favicon.webp">
</head>
<body>
    <header>
        <a href="index.php"><img src="Images/inicio.webp" alt="Página inicial" width="421" height="421"></a>
        <h1>Status do agendamento</h1>
    </header>
    <main>
        <?php if ($agendamento) { ?>
            <section class="card">
                <h2><?= $novo ? 'Agendamento realizado com sucesso!' : 'Seu agendamento' ?></h2>
                <div class="protocolo-destaque">
                    <p>Protocolo do atendimento</p>
                    <strong><?= $protocolo ?></strong>
                    <p>Guarde este número para consultar seu agendamento.</p>
                </div>
                <dl>
                    <dt>Serviço</dt><dd><?= htmlspecialchars($servicos[$servico] ?? $servico, ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt>Unidade</dt><dd><?= htmlspecialchars($unidades[$unidade] ?? $unidade, ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt>Data e horário</dt><dd><?= date('d/m/Y \à\s H:i', strtotime($data)) ?></dd>
                </dl>
                <p class="badge">Agendamento registrado</p>
            </section>
            <section class="card">
                <h2>Etapas do atendimento</h2>
                <p class="demo">Etapas demonstrativas. O andamento do atendimento ainda não é atualizado pelo sistema.</p>
                <ol>
                    <li class="feito"><span>✓</span>Agendamento registrado</li>
                    <li class="atual" aria-current="step"><span>2</span>Aguardando atendimento</li>
                    <li><span>3</span>Banho e tosa</li>
                    <li><span>4</span>Finalização</li>
                    <li><span>5</span>Pronto para retirada</li>
                </ol>
            </section>
            <a class="voltar" href="usuario.php">Ver meus agendamentos</a>
        <?php } else { ?>
            <section class="card"><h2>Consulta de agendamento</h2><p><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php } ?>
        <a class="voltar voltar-secundario" href="agendamento.html">Voltar ao agendamento</a>
    </main>
</body>
</html>
