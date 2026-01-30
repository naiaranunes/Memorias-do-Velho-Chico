<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memórias do Vale</title>
    <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>
    <header class="header">
        <div class="header-logo">
            <a href="/sitelendas/index.php">
                <img class="logo" src="/sitelendas/assets/images/logoponte.png" alt="logo site">
                <h1>Memórias do Vale</h1>
            </a>
        </div>

        <div class="header-search">
            <form method="GET" action="/sitelendas/index.php">
                <input
                    type="text"
                    name="busca"
                    placeholder="Buscar histórias"
                    value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="header-right">
            <?php if (isset($_SESSION['id'])): ?>
                <a class="btn-story" href="/SITELENDAS/principal.php">Área do usuário</a>
                <span class="header-user">Olá, <?= htmlspecialchars($_SESSION['nome']) ?></span>
                <div class="header-menu">
                    <button id="menuToggle" class="menu-toggle" aria-label="Abrir menu">☰</button>
                </div>
            <?php else: ?>
                <a class="btn-story" href="/SITELENDAS/auth/login.php">Entrar</a>
                <a class="btn-story" href="/SITELENDAS/auth/register.php">Criar</a>
            <?php endif; ?>
        </div>
    </header>

    <?php if (isset($_SESSION['id'])): ?>
        <nav class="profile-menu" id="menu">
            <ul>
                <li><a href="/sitelendas/user/profile.php">Editar perfil</a></li>
                <li><a href="/sitelendas/stories/meusregistros.php">Meus Registros</a></li>
                <li><a href="/sitelendas/user/password.php">Alterar senha</a></li>
                <li><a href="/sitelendas/user/deleteconta.php">Deletar conta</a></li>
                <li><a href="/sitelendas/auth/logout.php">Sair</a></li>
            </ul>
        </nav>
    <?php endif; ?>


    <?php if (isset($_GET['status']) && $_GET['status'] === 'conta_deletada'): ?>
        <script>
            alert("Conta deletada com sucesso!");
        </script>
    <?php endif; ?>

    <main>
        <section class="apresentacao">
            <div class="carousel">
                <img src="/SITELENDAS/assets/images/slide1.jpg">
                <img src="/SITELENDAS/assets/images/slide2.jpg">
                <img src="/SITELENDAS/assets/images/slide3.jpg">

                <button type="button" class="carousel-btn prev">❮</button>
                <button type="button" class="carousel-btn next">❯</button>
            </div>

            <div class="overlay"></div>

            <div class="apresentacao-conteudo">
                <h1>Memórias do Vale</h1>
                <p>Um projeto para registrar histórias, paisagens e memórias.</p>
            </div>
        </section>


        <section class="stories-grid" id="resultados">
            <?php
            include("config/config.php");

            $busca = trim($_GET['busca'] ?? '');

            if ($busca !== '') {
                $stmt = mysqli_prepare($conexao, "
                SELECT h.id, h.titulo, h.descricao, h.data_memoria, h.imagem, u.nome AS autor
                FROM historias h
                JOIN usuarios u ON u.id = h.usuario_id
                WHERE h.titulo LIKE ? OR h.descricao LIKE ?
                ORDER BY h.id DESC
            ");

                $like = "%{$busca}%";
                mysqli_stmt_bind_param($stmt, "ss", $like, $like);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            } else {
                $sql = "
                SELECT h.id, h.titulo, h.descricao, h.data_memoria, h.imagem, u.nome AS autor
                FROM historias h
                JOIN usuarios u ON u.id = h.usuario_id
                ORDER BY h.id DESC
            ";
                $result = mysqli_query($conexao, $sql);
            }

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $titulo = htmlspecialchars($row['titulo']);
                    $descricao = htmlspecialchars($row['descricao']);
                    $autor = htmlspecialchars($row['autor']);
                    $data = htmlspecialchars($row['data_memoria']);
                    $img = "/SITELENDAS/" . ltrim($row['imagem'], "/");
            ?>
                    <article class="story-card"
                        data-titulo="<?= $titulo ?>"
                        data-descricao="<?= $descricao ?>"
                        data-autor="<?= $autor ?>"
                        data-data="<?= $data ?>"
                        data-img="<?= $img ?>">
                        <img class="story-img" src="<?= $img ?>" alt="Imagem da história">
                        <h3><?= $titulo ?></h3>
                        <p><?= $descricao ?></p>
                        <small>Por <?= $autor ?> • <?= $data ?></small>
                    </article>
            <?php
                }
            } else {
                if ($busca !== '') {
                    echo "<p>Nenhum resultado para: <strong>" . htmlspecialchars($busca) . "</strong></p>";
                } else {
                    echo "<p>Nenhuma história publicada ainda.</p>";
                }
            }
            ?>
        </section>
    </main>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.get('busca')) {
            document.getElementById('resultados')?.scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>

  
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImg" src="" alt="">
            <h2 id="modalTitulo"></h2>
            <p id="modalDescricao"></p>
            <small id="modalAutor"></small>
        </div>
    </div>

    <script>
        const modal = document.getElementById("modal");
        const modalImg = document.getElementById("modalImg");
        const modalTitulo = document.getElementById("modalTitulo");
        const modalDescricao = document.getElementById("modalDescricao");
        const modalAutor = document.getElementById("modalAutor");
        const close = document.querySelector(".close");

        document.querySelectorAll(".story-card").forEach(card => {
            card.addEventListener("click", () => {
                modal.style.display = "flex";
                modalImg.src = card.dataset.img;
                modalTitulo.textContent = card.dataset.titulo;
                modalDescricao.textContent = card.dataset.descricao;
                modalAutor.textContent = `Por ${card.dataset.autor} • ${card.dataset.data}`;
            });
        });

        close.onclick = () => modal.style.display = "none";
        modal.onclick = e => {
            if (e.target === modal) modal.style.display = "none";
        }
    </script>

    <footer class="rodape">
        <div class="rodape-conteudo">
            <div class="rodape-col">
                <p>© <?= date('Y') ?> Memórias do Vale — Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

   
    <script src="/SITELENDAS/assets/js/menu.js"></script>

    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const slides = document.querySelectorAll(".carousel img");
            const prev = document.querySelector(".carousel-btn.prev");
            const next = document.querySelector(".carousel-btn.next");
            if (!slides.length || !prev || !next) return;

            let index = 0;

            function showSlide(i) {
                slides.forEach(s => s.classList.remove("active"));
                slides[i].classList.add("active");
            }

            showSlide(index);

            let timer = setInterval(() => {
                index = (index + 1) % slides.length;
                showSlide(index);
            }, 5000);

            prev.addEventListener("click", (e) => {
                e.preventDefault();
                clearInterval(timer);
                index = (index - 1 + slides.length) % slides.length;
                showSlide(index);
            });

            next.addEventListener("click", (e) => {
                e.preventDefault();
                clearInterval(timer);
                index = (index + 1) % slides.length;
                showSlide(index);
            });
        });
    </script>

</body>

</html>