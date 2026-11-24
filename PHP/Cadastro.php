<?php
// Salvar cadastro com senha protegida, quando o banco estiver disponível.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Cadastro.html');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$confirmacao = $_POST['confirmasenha'] ?? '';

// Validar os dados recebidos antes de acessar o banco.
if ($nome === '' || (function_exists('mb_strlen') ? mb_strlen($nome) : strlen($nome)) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($senha) < 8 || $senha !== $confirmacao) {
    http_response_code(400);
    exit('Dados inválidos ou senhas diferentes. <a href="../Cadastro.html">Voltar ao cadastro</a>.');
}

try {
    require __DIR__ . '/conexao.php';
    $conexao = conectarBanco();
    // Verificar se o e-mail já foi cadastrado.
    $consulta = $conexao->prepare("SELECT email FROM clientes WHERE email = ? LIMIT 1");
    $consulta->bind_param('s', $email);
    $consulta->execute();
    if ($consulta->get_result()->fetch_assoc()) {
        http_response_code(409);
        exit('E-mail já cadastrado. <a href="../Login.html">Fazer login</a>.');
    }
    $consulta->close();

    // Salvar apenas o hash, nunca a senha em texto puro.
    $senhaProtegida = password_hash($senha, PASSWORD_DEFAULT);
    $insercao = $conexao->prepare("INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)");
    $insercao->bind_param('sss', $nome, $email, $senhaProtegida);
    $insercao->execute();
    $insercao->close();
    $conexao->close();
    header('Location: ../Login.html?cadastro=ok');
    exit;
} catch (Throwable $erro) {
    // Não revelar usuário, senha ou host do banco em mensagens públicas.
    error_log('Erro no cadastro: ' . $erro->getMessage());
    http_response_code(503);
    exit('Cadastro indisponível: confira a configuração do banco. <a href="../Cadastro.html">Voltar</a>.');
}
