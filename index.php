<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" as="image" href="Images/main.webp" fetchpriority="high">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="CSS/index.css">
    <title>Patas &amp; Pelos | Pet shop, banho e tosa</title>
    <meta name="description" content="Conheça o Patas &amp; Pelos: produtos para pets, serviços de banho e tosa, unidades e agendamento.">
    <!-- Ajustes de alinhamento e responsividade -->
    <link rel="stylesheet" href="CSS/ajustes-visuais.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap">
    <link rel="icon" type="image/webp" href="Images/favicon.webp">
</head>
<body>
    <a class="pular-conteudo" href="#conteudo-principal">Ir para o conteúdo principal</a>
<!--Início Cabeçalho-->
    <header class="header">
            <img class="header_img" src="Images/logo.webp" alt="Patas &amp; Pelos" width="384" height="387">
        <nav aria-label="Navegação principal">

            <!--Opções_de_navegação-->
            <ul class="nav_list">
                <li>
                    <a href="Unidades.html" class="Unidades">
                        Unidades
                    </a>
                </li>
                <li>
                    <a href="Loja.html" class="loja">
                        Loja
                    </a>
                </li>
                <li>
                    <a href="agendamento.html" class="agendamento">
                        Agendamento
                    </a>
                </li>
                <li>
                    <a href="servicos.html" class="Servicos">
                        Serviços
                    </a>
                </li>
                <?php if (isset($_SESSION['cliente_id'])) { ?>
                    <li>
                        <a href="usuario.php" class="Login">Minha conta</a>
                    </li>
                    <li>
                        <a href="PHP/logout.php" class="Login">Sair</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="Login.html" class="Login">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </header>
<!--Fim Cabeçalho-->

<!--Início Main-->
    
    <main id="conteudo-principal">
    <div class="about">

        <section>
            <div class="about-text">
                <h1>Seja bem-vindo à Patas & Pelos</h1>
                <p>Cuidando de quem você mais ama desde 2009, atendendo toda a região metropolitana sempre com máxima agilidade e proficiência.</p>
            </div>
        </section>
    </div>
<!--Fim Main-->

<!--Início Categorias-->
<section class="categories">

    <h2>Categorias</h2>

    <div class="categories-div">
        <a href="Loja.html">
            <article>
                <img src="Images/cachorrin.webp" alt="" width="492" height="492" decoding="async">
                <p>Cachorro</p>
            </article>
        </a>
        <a href="Loja.html">
            <article>
                <img src="Images/gatin.webp" alt="" width="492" height="492" decoding="async">
                <p>Gato</p>
            </article>
        </a>
        <a href="Loja.html">
            <article>
                <img src="Images/peixin.webp" alt="" width="492" height="492" decoding="async">
                <p>Peixe</p>
            </article>
        </a>
        <a href="Loja.html">
            <article>
                <img src="Images/lagartin.webp" alt="" width="492" height="492" decoding="async">
                <p>Réptil</p>
            </article>
        </a>
        <a href="Loja.html">
             <article>
                <img src="Images/pássarin.webp" alt="" width="492" height="492" decoding="async">
                <p>Pássaro</p>
            </article>
        </a>
    </div>

</section>
<!--Fim Categorias-->

<!--Início serviços-->

    <section class="services">

        <div class="services-titulo">
            <h2>Nossos serviços</h2>
        </div>

        <div class="services-div">
            <a href="servicos.html">
                <article >
                    <h3>Petshop tradicional</h3>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3hT6oXQYmKeeZh103HHNq0rYmfN24yVP9hpkmHRplcw&s=10" alt="banho e tosa" loading="lazy" decoding="async">
                    <p>Serviços de banho e tosa <br> A partir de R$120,00</p>
                </article>
            </a>
            <a href="agendamento.html">
                <article>
                    <h3>Hotel</h3>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTS_SLcuque61d5qCZQO3oLIKi5ey4rMcY_UPzHHSbssw&s=10" alt="hotelzinho" loading="lazy" decoding="async">
                    <p>Ideal para deixar seu pet o dia todo <br> A partir de R$800,00 mensais</p>
                </article>
            </a>
            <a href="Loja.html">
                <article>
                    <h3>
                        Nossa loja
                    </h3>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkVQMZGt9pc_kmNbR7Kw2spCwLC5WqgZ750sxLWJis3g&s=10" alt="nossa loja" loading="lazy" decoding="async">
                    <p> Para garantir o brinquedo do seu pequeno <br> Preços a partir de R$10,00 </p>
                </article>
            </a>
        </div>
            
    </section>
<!--Fim serviços-->



<!--Início Mapa-->
    <section class="location">
        <h2>Descubra a Patas & Pelos mais perto de você!</h2>
        <iframe class="map" title="Mapa de localização do Patas &amp; Pelos" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15001.770423372176!2d-44.029426949999994!3d-19.947880200000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa695ea5dd82e1d%3A0xca75cdd5bbcad1d6!2sSenac%20em%20Contagem!5e0!3m2!1sen!2sbr!4v1790613346175!5m2!1sen!2sbr" width="1000" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </section>
<!-- Fim Mapa -->
    </main>

<!-- Início Rodapé -->
    <footer class="footer">
        <ul class="footer-list">
            <li>© Patas & Pelos</li>
            <li><a href="https://accounts.google.com/v3/signin/accountchooser?continue=https://mail.google.com/mail/u/0/&emr=1&followup=https://mail.google.com/mail/u/0/&osid=1&passive=1209600&service=mail&flowName=GlifWebSignIn&flowEntry=ServiceLogin&dsh=S-703551595:1790707303161070">patas&pelos@contato.com</a></li>
            <li>WhatsApp/Telefone: <a href="https://web.whatsapp.com/">(31)99999-9999</a></li>
            <li><a href="Suporte.html">Suporte</a></li>
        </ul>
    </footer>
<!-- Fim Rodapé -->

</body>
</html>