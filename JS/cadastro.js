// Verificar se as duas senhas coincidem antes de enviar o cadastro.
const formularioCadastro = document.querySelector('#form-cadastro');
formularioCadastro?.addEventListener('submit', (evento) => {
    const senha = document.querySelector('#senha').value;
    const confirmacao = document.querySelector('#confirmasenha').value;
    const erro = document.querySelector('#erro-cadastro');
    if (senha !== confirmacao) {
        evento.preventDefault();
        erro.hidden = false;
    } else {
        erro.hidden = true;
    }
});
