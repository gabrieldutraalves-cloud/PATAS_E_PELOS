/* Carrinho de demonstração: não realiza compras nem cobranças reais. */
const painelCarrinho = document.getElementById('carrinho');
const itensCarrinho = document.getElementById('itens-carrinho');
const botaoPagamento = document.getElementById('ir-pagamento');
const etapaCarrinho = document.getElementById('etapa-carrinho');
const etapaPagamento = document.getElementById('etapa-pagamento');
const etapaConcluida = document.getElementById('etapa-concluida');
let carrinho = [];

// Os preços são guardados em centavos para evitar erros de arredondamento.
function moeda(centavos) {
    return (centavos / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}
function totalCarrinho() {
    return carrinho.reduce((total, produto) => total + produto.preco * produto.quantidade, 0);
}
function mostrarEtapa(etapa) {
    etapaCarrinho.hidden = etapa !== 'carrinho';
    etapaPagamento.hidden = etapa !== 'pagamento';
    etapaConcluida.hidden = etapa !== 'concluida';
}
function abrirCarrinho() {
    mostrarEtapa('carrinho');
    if (!painelCarrinho.open) painelCarrinho.showModal();
}

// Adicionar apenas o produto escolhido ao carrinho.
document.querySelectorAll('.adicionar-produto').forEach(botao => {
    botao.addEventListener('click', () => {
        const card = botao.closest('article');
        const id = botao.dataset.produto;
        const existente = carrinho.find(produto => produto.id === id);
        if (existente) {
            existente.quantidade++;
        } else {
            carrinho.push({ id: id, nome: card.querySelector('h3').textContent, imagem: card.querySelector('img').getAttribute('src'), preco: Number(botao.dataset.preco), quantidade: 1 });
        }
        atualizarCarrinho();
        abrirCarrinho();
    });
});

// Exibir os produtos adicionados, suas quantidades e o total.
function atualizarCarrinho() {
    itensCarrinho.replaceChildren();
    carrinho.forEach(produto => {
        const item = document.createElement('article');
        item.className = 'carrinho-item';
        const imagem = document.createElement('img');
        imagem.src = produto.imagem;
        imagem.alt = produto.nome;
        const dados = document.createElement('div');
        const nome = document.createElement('h4');
        nome.textContent = produto.nome;
        const valor = document.createElement('p');
        valor.textContent = `${produto.quantidade} × ${moeda(produto.preco)} = ${moeda(produto.preco * produto.quantidade)}`;
        const diminuir = document.createElement('button');
        diminuir.type = 'button';
        diminuir.textContent = '−';
        diminuir.setAttribute('aria-label', 'Diminuir quantidade de ' + produto.nome);
        diminuir.addEventListener('click', () => {
            produto.quantidade--;
            if (produto.quantidade === 0) carrinho = carrinho.filter(p => p.id !== produto.id);
            atualizarCarrinho();
        });
        const aumentar = document.createElement('button');
        aumentar.type = 'button';
        aumentar.textContent = '+';
        aumentar.setAttribute('aria-label', 'Aumentar quantidade de ' + produto.nome);
        aumentar.addEventListener('click', () => {
            produto.quantidade++;
            atualizarCarrinho();
        });
        dados.append(nome, valor, diminuir, aumentar);
        item.append(imagem, dados);
        itensCarrinho.appendChild(item);
    });
    const quantidade = carrinho.reduce((total, produto) => total + produto.quantidade, 0);
    document.getElementById('quantidade-carrinho').textContent = quantidade;
    document.getElementById('total-carrinho').textContent = moeda(totalCarrinho());
    document.getElementById('carrinho-vazio').hidden = quantidade !== 0;
    botaoPagamento.disabled = quantidade === 0;
}

document.getElementById('abrir-carrinho').addEventListener('click', abrirCarrinho);
document.getElementById('fechar-carrinho').addEventListener('click', () => painelCarrinho.close());
document.getElementById('voltar-carrinho').addEventListener('click', () => mostrarEtapa('carrinho'));

// Confirmação fictícia de pagamento, sem enviar dados a um servidor.
botaoPagamento.addEventListener('click', () => {
    if (!carrinho.length) return;
    document.getElementById('total-pagamento').textContent = moeda(totalCarrinho());
    mostrarEtapa('pagamento');
});
document.getElementById('confirmar-pagamento').addEventListener('click', () => {
    if (!carrinho.length) return;
    const metodo = document.querySelector('input[name="pagamento"]:checked').value;
    const descricao = metodo === 'cartao' ? 'Cartão de teste •••• 0000' : 'Pix simulado';
    document.getElementById('resumo-pagamento').textContent = `${moeda(totalCarrinho())} — ${descricao}.`;
    carrinho = [];
    atualizarCarrinho();
    mostrarEtapa('concluida');
});
document.getElementById('voltar-loja').addEventListener('click', () => {
    painelCarrinho.close();
    mostrarEtapa('carrinho');
});
atualizarCarrinho();
