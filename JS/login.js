// Avisar quando o cadastro tiver sido salvo pelo PHP.
if (new URLSearchParams(window.location.search).get('cadastro') === 'ok') {
    const aviso = document.querySelector('#mensagem-cadastro');
    if (aviso) aviso.hidden = false;
}
