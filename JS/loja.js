/* Início catálogo de demonstração */
// Os produtos são exemplos locais. Posteriormente virão do banco de dados.
const produtos = [
    { id: 1, nome: 'Ração para cães 1 kg', categoria: 'cachorro', descricao: 'Alimento seco para cães adultos.', preco: 39.90, icone: '🐶', destaque: true },
    { id: 2, nome: 'Bola de borracha', categoria: 'cachorro', descricao: 'Brinquedo para brincadeiras e atividades.', preco: 19.90, icone: '🎾', destaque: true },
    { id: 3, nome: 'Coleira ajustável', categoria: 'cachorro', descricao: 'Acessório com fecho ajustável.', preco: 32.50, icone: '🦮', destaque: false },
    { id: 4, nome: 'Ração para gatos 1 kg', categoria: 'gato', descricao: 'Alimento seco para gatos adultos.', preco: 45.90, icone: '🐱', destaque: true },
    { id: 5, nome: 'Arranhador compacto', categoria: 'gato', descricao: 'Ajuda a entreter gatos em casa.', preco: 79.90, icone: '🐾', destaque: true },
    { id: 6, nome: 'Varinha com brinquedo', categoria: 'gato', descricao: 'Acessório para brincadeiras supervisionadas.', preco: 18.00, icone: '🧶', destaque: false },
    { id: 7, nome: 'Ração para peixes', categoria: 'peixe', descricao: 'Alimento para peixes ornamentais.', preco: 24.90, icone: '🐟', destaque: true },
    { id: 8, nome: 'Decoração de aquário', categoria: 'peixe', descricao: 'Enfeite para personalizar o aquário.', preco: 29.90, icone: '🪸', destaque: false },
    { id: 9, nome: 'Comedouro para répteis', categoria: 'reptil', descricao: 'Recipiente para alimentação de répteis.', preco: 37.90, icone: '🦎', destaque: true },
    { id: 10, nome: 'Abrigo para terrário', categoria: 'reptil', descricao: 'Esconderijo decorativo para terrários.', preco: 64.90, icone: '🌿', destaque: false },
    { id: 11, nome: 'Mistura de sementes', categoria: 'passaro', descricao: 'Mistura para aves, conforme a espécie.', preco: 22.90, icone: '🐦', destaque: true },
    { id: 12, nome: 'Poleiro de madeira', categoria: 'passaro', descricao: 'Acessório para o ambiente das aves.', preco: 35.90, icone: '🪵', destaque: false }
];
const formatoPreco = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
const campoBusca = document.querySelector('#busca');
const campoCategoria = document.querySelector('#categoria');
const campoOrdenacao = document.querySelector('#ordenacao');
const secaoProdutos = document.querySelector('#produtos');
let lista = [];
/* Fim catálogo de demonstração */

/* Início criação dos cards */
function criarCard(produto) {
    const artigo = document.createElement('article');
    artigo.className = 'produto-card';
    const imagem = document.createElement('div');
    imagem.className = 'produto-imagem';
    imagem.textContent = produto.icone;
    imagem.setAttribute('aria-hidden', 'true');

    const info = document.createElement('div');
    info.className = 'produto-info';
    const nome = document.createElement('h3');
    nome.textContent = produto.nome;
    const descricao = document.createElement('p');
    descricao.textContent = produto.descricao;
    const preco = document.createElement('span');
    preco.className = 'produto-preco';
    preco.textContent = formatoPreco.format(produto.preco);
    const adicionar = document.createElement('button');
    adicionar.className = 'btn';
    adicionar.type = 'button';
    adicionar.textContent = 'Adicionar à lista';
    adicionar.addEventListener('click', () => adicionarLista(produto.id));
    info.append(nome, descricao, preco, adicionar);
    artigo.append(imagem, info);
    return artigo;
}
/* Fim criação dos cards */

/* Início filtros de produtos */
function mostrarProdutos() {
    const busca = campoBusca.value.trim().toLocaleLowerCase('pt-BR');
    const categoria = campoCategoria.value;
    const ordenacao = campoOrdenacao.value;
    const filtrados = produtos.filter(produto => {
        const correspondeCategoria = categoria === 'todos' || categoria === produto.categoria;
        const correspondeBusca = (produto.nome + ' ' + produto.descricao).toLocaleLowerCase('pt-BR').includes(busca);
        return correspondeCategoria && correspondeBusca;
    });
    if (ordenacao === 'menor') filtrados.sort((a, b) => a.preco - b.preco);
    if (ordenacao === 'maior') filtrados.sort((a, b) => b.preco - a.preco);
    if (ordenacao === 'nome') filtrados.sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR'));

    secaoProdutos.replaceChildren(...filtrados.map(criarCard));
    document.querySelector('#resultado-contagem').textContent = `${filtrados.length} produto(s) encontrado(s).`;
    if (filtrados.length === 0) {
        const mensagem = document.createElement('p');
        mensagem.className = 'sem-resultados';
        mensagem.textContent = 'Nenhum produto encontrado. Experimente limpar os filtros.';
        secaoProdutos.append(mensagem);
    }
}
// Aplicar a categoria recebida dos links da página inicial.
const categoriaDoLink = new URLSearchParams(window.location.search).get('categoria');
if ([...campoCategoria.options].some(opcao => opcao.value === categoriaDoLink)) campoCategoria.value = categoriaDoLink;
for (const campo of [campoBusca, campoCategoria, campoOrdenacao]) {
    campo.addEventListener('input', mostrarProdutos);
}
document.querySelector('#filtros').addEventListener('reset', () => {
    // Após resetar os campos, remover o filtro da URL também.
    window.history.replaceState(null, '', window.location.pathname);
    requestAnimationFrame(mostrarProdutos);
});
mostrarProdutos();
/* Fim filtros de produtos */

/* Início carrossel */
const carrossel = document.querySelector('#carrossel');
carrossel.append(...produtos.filter(produto => produto.destaque).map(criarCard));
document.querySelector('#anterior').addEventListener('click', () => carrossel.scrollBy({ left: -270, behavior: 'smooth' }));
document.querySelector('#proximo').addEventListener('click', () => carrossel.scrollBy({ left: 270, behavior: 'smooth' }));
/* Fim carrossel */

/* Início lista de produtos */
// Lista temporária apenas no navegador; não é um carrinho com pagamento.
function adicionarLista(idProduto) {
    lista.push(idProduto);
    atualizarLista();
}
function atualizarLista() {
    const itens = document.querySelector('#itens-selecionados');
    itens.replaceChildren();
    let total = 0;
    lista.forEach((id, indice) => {
        const produto = produtos.find(item => item.id === id);
        total += produto.preco;
        const item = document.createElement('li');
        item.append(document.createTextNode(`${produto.nome} — ${formatoPreco.format(produto.preco)}`));
        const retirar = document.createElement('button');
        retirar.type = 'button';
        retirar.textContent = 'Remover';
        retirar.setAttribute('aria-label', `Remover ${produto.nome} da lista`);
        retirar.addEventListener('click', () => { lista.splice(indice, 1); atualizarLista(); });
        item.append(retirar);
        itens.append(item);
    });
    document.querySelector('#quantidade-lista').textContent = lista.length;
    document.querySelector('#total-lista').textContent = formatoPreco.format(total);
    if (lista.length > 0) {
        document.querySelector('#lista-selecionados').hidden = false;
        document.querySelector('#abrir-lista').setAttribute('aria-expanded', 'true');
    }
}
document.querySelector('#abrir-lista').addEventListener('click', () => {
    const painel = document.querySelector('#lista-selecionados');
    painel.hidden = !painel.hidden;
    document.querySelector('#abrir-lista').setAttribute('aria-expanded', String(!painel.hidden));
});
document.querySelector('#limpar-lista').addEventListener('click', () => { lista = []; atualizarLista(); });
/* Fim lista de produtos */
