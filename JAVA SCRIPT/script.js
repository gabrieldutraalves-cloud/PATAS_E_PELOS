/* Seleciona os elementos do HTML */
const listaImagens = document.querySelector(".lista-imagens");
const imagens = document.querySelectorAll(".imagem");

const botaoAnterior = document.querySelector(".botao-anterior");
const botaoProximo = document.querySelector(".botao-proximo");

const areaIndicadores = document.querySelector(".indicadores");

/* Guarda a posição da imagem atual */
let imagemAtual = 0;

/* Conta quantas imagens existem */
const totalImagens = imagens.length;

/* Cria os indicadores automaticamente */
imagens.forEach((imagem, indice) => {
    const indicador = document.createElement("button");

    indicador.classList.add("indicador");
    indicador.type = "button";

    indicador.setAttribute(
        "aria-label",
        `Ir para a imagem ${indice + 1}`
    );

    /* Mostra a imagem ao clicar no indicador */
    indicador.addEventListener("click", () => {
        imagemAtual = indice;
        atualizarCarrossel();
    });

    /* Adiciona o indicador à página */
    areaIndicadores.appendChild(indicador);
});

/* Atualiza a imagem e os indicadores */
function atualizarCarrossel() {

    /* Move a lista para mostrar a imagem atual */
    listaImagens.style.transform =
        `translateX(-${imagemAtual * 100}%)`;

    imagens.forEach((imagem, indice) => {
        imagem.setAttribute("aria-hidden", String(indice !== imagemAtual));
    });

    /* Seleciona todos os indicadores */
    const todosIndicadores =
        document.querySelectorAll(".indicador");

    /* Destaca o indicador da imagem atual */
    todosIndicadores.forEach((indicador, indice) => {
        indicador.setAttribute("aria-current", indice === imagemAtual ? "true" : "false");
        indicador.classList.toggle(
            "ativo",
            indice === imagemAtual
        );
    });
}

/* Avança para a próxima imagem */
botaoProximo.addEventListener("click", () => {

    imagemAtual++;

    /* Se chegar ao final, volta para a primeira */
    if (imagemAtual >= totalImagens) {
        imagemAtual = 0;
    }

    atualizarCarrossel();
});

/* Volta para a imagem anterior */
botaoAnterior.addEventListener("click", () => {

    imagemAtual--;

    /* Se estiver na primeira, vai para a última */
    if (imagemAtual < 0) {
        imagemAtual = totalImagens - 1;
    }

    atualizarCarrossel();
});

/* Exibe a primeira imagem ao iniciar */
atualizarCarrossel();