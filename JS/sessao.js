// Alterar "Login" para "Área do usuário" após uma autenticação PHP válida.
// Se o PHP não estiver em execução, o link Login continua visível.
fetch('PHP/Sessao.php', { credentials: 'same-origin', cache: 'no-store' })
    .then(resposta => {
        if (!resposta.ok) throw new Error('Servidor de sessão indisponível');
        return resposta.json();
    })
    .then(sessao => {
        if (!sessao.autenticado) return;
        document.querySelectorAll('[data-link-conta]').forEach(link => {
            link.textContent = 'Área do usuário';
            link.href = 'PHP/AreaUsuario.php';
        });
    })
    .catch(() => { /* Visualização local sem PHP: manter o link Login. */ });
