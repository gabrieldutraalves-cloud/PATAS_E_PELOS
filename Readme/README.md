# Patas & Pelos — versão de interface

## O que está pronto

- Identidade original em amarelo preservada, com pequenas correções no `index.html`.
- Loja demonstrativa com **12 produtos**, carrossel, busca, categoria, ordenação e lista local de produtos.
- Categorias do início abrem `Loja.html?categoria=cachorro` (ou outra espécie).
- Páginas internas alinhadas e responsivas, com hover nos botões e links.
- Unidades, serviços, agendamento, entregas e suporte com conteúdo informativo.
- Login/cadastro preparados para uso com banco; páginas e ações sem backend não simulam compras reais.

## Testar a parte visual

1. Abra `index.html` no navegador. Os links relativos funcionam sem precisar alterar caminhos.
2. Clique em uma categoria no início, confira o filtro aberto na loja e teste busca e preços.
3. Use os botões do carrossel e **Adicionar à lista**. A lista não finaliza pedidos.
4. Abra `Servicos.html`, escolha um serviço e observe o campo preenchido em `agendamento.html`.
5. **Atenção:** abrindo arquivos diretamente sem PHP o login não funciona. Esse comportamento é esperado.

## Configurar banco no WampServer ou InfinityFree

1. Mantenha o banco já existente. Verifique no phpMyAdmin o nome exato da tabela de usuários: **`clientes`** (como no código enviado) ou **`cliente`** (como mencionado na descrição).
2. Crie um arquivo `PHP/config.local.php` copiando `PHP/config.exemplo.php`, alterando `host`, `usuario`, `senha`, `banco` e `tabela_clientes` conforme o servidor.
3. Nunca envie esse arquivo com senhas reais para um repositório público. Se possível, mantenha-o fora do controle de versão.
4. Verifique se o campo `senha` da tabela existente comporta hashes: **VARCHAR(255)**; `nome` até 120 caracteres, `email` até 190. Idealmente, `email` deve ter índice `UNIQUE`.
5. Importe `SQL/estrutura_complementar.sql` no **banco selecionado**. O script cria tabelas novas, mas não altera a tabela existente.
6. Teste o cadastro e login em um servidor PHP (WampServer ou a hospedagem). O link **Login** mudará para **Área do usuário** por consulta à sessão PHP após entrar.
7. Na InfinityFree, os caminhos começam na pasta de publicação `htdocs` e o banco normalmente possui um nome com prefixo; use as credenciais fornecidas no painel da hospedagem.

## O que ainda não está integrado

- A loja usa **dados fictícios no JavaScript**; não pesquisa a tabela `produtos` ainda.
- A lista de produtos não cria pedidos, não cobra pagamento e se perde ao recarregar a página.
- O agendamento apenas mostra uma prévia: **não salva dados** e não verifica horários disponíveis.
- Entregas e suporte são páginas informativas sem rastreamento ou chat reais.
- O menu de login depende da sessão PHP, não de armazenamento inseguro no navegador.

## Arquivos principais

- `CSS/geral.css`: estilos comuns; `CSS/index-ajustes.css`: retoques mínimos da página inicial.
- `JS/loja.js`: produtos, filtros, carrossel e lista.
- `JS/sessao.js`: atualização do link da conta, quando há login PHP.
- `PHP/Cadastro.php`, `PHP/Login.php`, `PHP/AreaUsuario.php`, `PHP/Sair.php`: autenticação.
- `SQL/estrutura_complementar.sql`: novas tabelas para a próxima etapa.

### Atenção sobre cadastros antigos

O PHP original salvava senha em texto puro. A nova versão usa `password_hash()` para novos cadastros e tenta migrar automaticamente senhas antigas ao primeiro login bem-sucedido. Confira antes se os valores antigos cabem na coluna atual e use uma cópia do banco para testes.
