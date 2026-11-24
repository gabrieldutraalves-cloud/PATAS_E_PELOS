// Preencher campos de acordo com o link clicado em Serviços ou Unidades.
const parametrosAgendamento = new URLSearchParams(window.location.search);
for (const campo of ['unidade', 'servico']) {
    const valor = parametrosAgendamento.get(campo);
    const select = document.querySelector('#' + campo);
    if (select && [...select.options].some(opcao => opcao.value === valor)) select.value = valor;
}
// Não permitir datas anteriores à data de hoje.
const campoData = document.querySelector('#data');
const hoje = new Date();
const ano = hoje.getFullYear();
const mes = String(hoje.getMonth() + 1).padStart(2, '0');
const dia = String(hoje.getDate()).padStart(2, '0');
campoData.min = `${ano}-${mes}-${dia}`;

// Mostrar um resumo sem simular salvamento no banco de dados.
document.querySelector('#form-agendamento').addEventListener('submit', (evento) => {
    evento.preventDefault();
    const unidade = document.querySelector('#unidade').selectedOptions[0].textContent;
    const servico = document.querySelector('#servico').selectedOptions[0].textContent;
    const pet = document.querySelector('#pet').value.trim();
    const data = document.querySelector('#data').value.split('-').reverse().join('/');
    const horario = document.querySelector('#horario').value;
    const resumo = document.querySelector('#resumo-agendamento');
    resumo.textContent = `Prévia: ${servico}, para ${pet}, na unidade ${unidade}, em ${data} às ${horario}. Solicitação não enviada. Em breve, será possível confirmar pela conta.`;
    resumo.hidden = false;
});
