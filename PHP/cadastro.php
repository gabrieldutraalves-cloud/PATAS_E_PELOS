<?php
// Receber apenas dados enviados pelo formulário.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Cadastro.html');
    exit;
}

// Pegar os dados que vieram do HTML.
$nome = trim(is_string($_POST['nome'] ?? null) ? $_POST['nome'] : '');
$email = trim(is_string($_POST['email'] ?? null) ? $_POST['email'] : '');
$senha = $_POST['senha'] ?? '';
$confirmasenha = $_POST['confirmasenha'] ?? '';

// Validar os dados antes de salvar.
if ($nome === '' || strlen($nome) > 120 || strlen($email) > 254 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($senha) ||
    strlen($senha) < 8 || strlen($senha) > 72 || strpos($senha, "\0") !== false ||
    $senha !== $confirmasenha) {
    http_response_code(400);
    exit('Dados inválidos. Confira os campos e a confirmação da senha. <a href="../Cadastro.html">Voltar</a>');
}

// Configuração do Banco de Dados.
$servidor = "localhost";
$usuario = "root";
$senha_bd = "";
$banco = "patas_e_pelos";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    // Criar a conexão.
    $patas_e_pelos = mysqli_connect($servidor, $usuario, $senha_bd, $banco);
    mysqli_set_charset($patas_e_pelos, 'utf8mb4');

    // Verificar se o e-mail já está cadastrado.
    $sql = "SELECT email FROM clientes WHERE email = ?";
    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        http_response_code(409);
        exit('E-mail já cadastrado. <a href="../Login.html">Entrar</a>');
    }
    mysqli_stmt_close($stmt);

    // Proteger a senha antes de armazenar.
    $senha = password_hash($senha, PASSWORD_DEFAULT);

    // Montar o comando SQL para inserir os dados com segurança.
    $sql = "INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $nome, $email, $senha);
    mysqli_stmt_execute($stmt);

    // Fechar a conexão com o banco.
    mysqli_stmt_close($stmt);
    mysqli_close($patas_e_pelos);

    // Cadastro realizado: ir para o login.
    header('Location: ../Login.html', true, 303);
    exit;
} catch (mysqli_sql_exception $erro) {
    if ($erro->getCode() === 1062) {
        http_response_code(409);
        exit('E-mail já cadastrado. <a href="../Login.html">Entrar</a>');
    }
    http_response_code(500);
    exit('Não foi possível realizar o cadastro. <a href="../Cadastro.html">Voltar</a>');
}
?>
