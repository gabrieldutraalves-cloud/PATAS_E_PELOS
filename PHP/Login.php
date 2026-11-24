<?php
// Validar a conta no banco e iniciar a sessão do usuário.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Login.html');
    exit;
}
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
    http_response_code(400);
    exit('E-mail ou senha inválidos. <a href="../Login.html">Voltar</a>.');
}
try {
    require __DIR__ . '/conexao.php';
    $conexao = conectarBanco();
    $consulta = $conexao->prepare("SELECT nome, email, senha FROM `$tabelaClientes` WHERE email = ? LIMIT 1");
    $consulta->bind_param('s', $email);
    $consulta->execute();
    $usuario = $consulta->get_result()->fetch_assoc();
    $consulta->close();

    // Verificar o hash da senha. Cadastros antigos em texto puro podem migrar na primeira entrada.
    $senhaCorreta = $usuario && password_verify($senha, $usuario['senha']);
    if ($usuario && !$senhaCorreta && password_get_info($usuario['senha'])['algo'] === null
        && hash_equals($usuario['senha'], $senha)) {
        $senhaCorreta = true;
        $novoHash = password_hash($senha, PASSWORD_DEFAULT);
        $atualizacao = $conexao->prepare("UPDATE `$tabelaClientes` SET senha = ? WHERE email = ?");
        $atualizacao->bind_param('ss', $novoHash, $email);
        $atualizacao->execute();
        $atualizacao->close();
    }
    $conexao->close();
    if (!$senhaCorreta) {
        http_response_code(401);
        exit('E-mail ou senha inválidos. <a href="../Login.html">Tentar novamente</a>.');
    }

    require __DIR__ . '/iniciar_sessao.php';
    session_regenerate_id(true);
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    header('Location: AreaUsuario.php');
    exit;
} catch (Throwable $erro) {
    error_log('Erro no login: ' . $erro->getMessage());
    http_response_code(503);
    exit('Login indisponível: confira a configuração do banco. <a href="../Login.html">Voltar</a>.');
}
