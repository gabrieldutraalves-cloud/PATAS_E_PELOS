<?php
// Verificar se o cliente está logado.
session_start();

// Receber apenas dados enviados pelo método POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método de envio inválido.');
}

$cliente_id = filter_var($_SESSION['cliente_id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($cliente_id === false) {
    header('Location: ../Login.html', true, 303);
    exit;
}

// Pegar os dados que vieram do HTML.
$unidade = $_POST['Unidade'] ?? '';
$servico = $_POST['Servico'] ?? '';
$data = $_POST['data'] ?? '';

// Validar a unidade e o serviço selecionados.
$unidades = ['UnidadeCentro', 'UnidadeSul'];
$servicos = ['banho&tosaSimples', 'banho&tosa_c/_hidratacao', 'banho&tosaPremium'];

if (!in_array($unidade, $unidades, true) || !in_array($servico, $servicos, true)) {
    http_response_code(400);
    exit('Selecione uma unidade e um serviço válidos. <a href="../agendamento.html">Voltar</a>');
}

// Validar a data e o horário do agendamento.
date_default_timezone_set('America/Sao_Paulo');

if (!is_string($data) || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/D', $data)) {
    http_response_code(400);
    exit('Informe uma data e um horário válidos. <a href="../agendamento.html">Voltar</a>');
}

$dataAgendamento = DateTime::createFromFormat('!Y-m-d\TH:i', $data);

if (!$dataAgendamento || $dataAgendamento->format('Y-m-d\TH:i') !== $data || $dataAgendamento <= new DateTime()) {
    http_response_code(400);
    exit('Selecione uma data e um horário futuros e válidos. <a href="../agendamento.html">Voltar</a>');
}

// Converter a data para o formato DATETIME do MySQL.
$data = $dataAgendamento->format('Y-m-d H:i:s');

// Configuração do Banco de Dados (igual ao cadastro.php local).
$servidor = 'localhost';
$usuario = 'root';
$senha_bd = '';
$banco = 'patas_e_pelos';

// Criar a conexão e salvar o agendamento.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $patas_e_pelos = mysqli_connect($servidor, $usuario, $senha_bd, $banco);
    mysqli_set_charset($patas_e_pelos, 'utf8mb4');

    // Montar o comando SQL para inserir os dados com segurança.
    $sql = 'INSERT INTO agendamentos (cliente_id, unidade, servico, data_agendamento)
            VALUES (?, ?, ?, ?)';

    $stmt = mysqli_prepare($patas_e_pelos, $sql);
    mysqli_stmt_bind_param($stmt, 'isss', $cliente_id, $unidade, $servico, $data);
    mysqli_stmt_execute($stmt);

    // O ID gerado pelo MySQL será o protocolo único do agendamento.
    $protocolo = mysqli_insert_id($patas_e_pelos);

    // Fechar o comando e a conexão com o banco.
    mysqli_stmt_close($stmt);
    mysqli_close($patas_e_pelos);

    // Exibir o protocolo na página de status, sem reenviar o formulário.
    $_SESSION['novo_agendamento'] = $protocolo;
    header('Location: ../status.php?protocolo=' . $protocolo, true, 303);
    exit; 
} catch (mysqli_sql_exception $erro) {
    http_response_code(500);
    exit('Não foi possível realizar o agendamento. <a href="../agendamento.html">Voltar</a>');
}
?>