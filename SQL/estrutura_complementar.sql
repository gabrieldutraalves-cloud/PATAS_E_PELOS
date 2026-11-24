-- Patas & Pelos: tabelas futuras, sem alterar a tabela de clientes existente.
-- Importe dentro do banco correto (patas_e_pelos no WampServer).
-- Na InfinityFree o nome do banco pode incluir um prefixo fornecido pela hospedagem.

-- Início tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    categoria VARCHAR(30) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Início tabela de agendamentos
CREATE TABLE IF NOT EXISTS agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_email VARCHAR(190) NOT NULL,
    nome_pet VARCHAR(100) NOT NULL,
    unidade VARCHAR(30) NOT NULL,
    servico VARCHAR(60) NOT NULL,
    data_agendamento DATE NOT NULL,
    horario TIME NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'solicitado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Início tabela de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_email VARCHAR(190) NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(30) NOT NULL DEFAULT 'recebido',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Início itens dos pedidos
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_itens_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    CONSTRAINT fk_itens_produto FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Início solicitações de suporte
CREATE TABLE IF NOT EXISTS atendimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_email VARCHAR(190),
    assunto VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'aberto',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- OBSERVAÇÃO: cliente_email é uma referência provisória.
-- Após confirmar se a tabela existente se chama "cliente" ou "clientes"
-- e se possui um ID único, relacionar as tabelas pelo ID do cliente.
