// Mostrar orientações conforme a opção escolhida na página Suporte.
const orientacoes = {
    'nao-recebido': ['Não recebi meu produto', 'Confira o número do pedido e o prazo informado. O rastreamento estará disponível quando o sistema de pedidos for integrado.'],
    'pedido-incorreto': ['Produto diferente do pedido', 'Separe as informações do pedido e dos itens recebidos. O formulário de atendimento será disponibilizado posteriormente.'],
    'defeito': ['Produto com defeito', 'Identifique o produto e as informações da compra. Em breve haverá uma forma de registrar a solicitação pelo site.']
};
const assunto = new URLSearchParams(window.location.search).get('assunto');
if (Object.hasOwn(orientacoes, assunto)) {
    document.querySelector('#titulo-assunto').textContent = orientacoes[assunto][0];
    document.querySelector('#texto-assunto').textContent = orientacoes[assunto][1];
}
