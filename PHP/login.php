<?php
// Receber apenas dados enviados pelo formulário de login.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Login.html');
    exit;
}

// Pegar os dados que vieram do HTML.
$email = trim(is_string($_POST['email'] ?? null) ? $_POST['email'] : '');
$senha = $_POST['senha'] ?? '';

// Validar os campos.
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 ||
    !is_string($senha) || $senha === '' || strlen($senha) > 72 || strpos($senha, "\0") !== false) {
    http_response_code(400);
    exit('Informe um e-mail e uma senha válidos. <a href="../Login.html">Voltar</a>');
}

// Configuração do Banco de Dados (igual ao cadastro.php).
$servidor = "localhost";
$usuario = "root";
$senha_bd = "";
$banco = "patas_e_pelos";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    // Criar a conexão.
    $patas_e_pelos = mysqli_connect($servidor, $usuario, $senha_bd, $banco);
    mysqli_set_charset($patas_e_pelos, 'utf8mb4');

    // Buscar o cliente pelo e-mail.
    $sql = "SELECT id, senha FROM clientes WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cliente_id, $senha_bd_cliente);
    $encontrado = mysqli_stmt_fetch($stmt);
    $valido = $encontrado && password_verify($senha, $senha_bd_cliente);

    // Fechar a conexão com o banco.
    mysqli_stmt_close($stmt);
    mysqli_close($patas_e_pelos);

    if (!$valido) {
        http_response_code(401);
        exit('E-mail ou senha incorretos. <a href="../Login.html">Voltar</a>');
    }

    // Criar a sessão do cliente autenticado.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
    session_regenerate_id(true);
    $_SESSION['cliente_id'] = $cliente_id;

    // Login realizado: ir para a página inicial.
    header('Location: ../index.html', true, 303);
    exit;
} catch (mysqli_sql_exception $erro) {
    http_response_code(500);
    exit('Não foi possível realizar o login. <a href="../Login.html">Voltar</a>');
}
?>
