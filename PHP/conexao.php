<?php
// Carregar a configuração local sem publicar senhas no projeto.
$arquivoConfiguracao = __DIR__ . '/config.local.php';
$configuracao = require file_exists($arquivoConfiguracao)
    ? $arquivoConfiguracao
    : __DIR__ . '/config.exemplo.php';

// Usar somente nomes conhecidos para evitar SQL inseguro.
$tabelaClientes = $configuracao['tabela_clientes'] ?? 'clientes';
if (!in_array($tabelaClientes, ['cliente', 'clientes'], true)) {
    throw new RuntimeException('Tabela de clientes inválida.');
}

// Criar conexão quando uma operação realmente precisar do banco.
function conectarBanco(): mysqli
{
    global $configuracao;
    $conexao = new mysqli(
        $configuracao['host'],
        $configuracao['usuario'],
        $configuracao['senha'],
        $configuracao['banco']
    );
    $conexao->set_charset('utf8mb4');
    return $conexao;
}
