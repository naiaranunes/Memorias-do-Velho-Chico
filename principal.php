<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: auth/login.php");
    exit;
}

include("includes/header_nav.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Área do Usuário</title>
    <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

    <main class="content">
        <h2>Veja os registros da nossa plataforma</h2>

        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <div class="msg-sucesso">
                <?= $_SESSION['mensagem_sucesso']; ?>
            </div>
            <?php unset($_SESSION['mensagem_sucesso']); ?>
        <?php endif; ?>

        <section class="stories-grid">
            <?php
            include("config/config.php");

            $busca = $_GET['q'] ?? '';

            $sql = "
      SELECT h.id, h.titulo, h.descricao, h.data_memoria, h.imagem, h.usuario_id, u.nome AS autor
      FROM historias h
      JOIN usuarios u ON u.id = h.usuario_id
    ";

            if ($busca !== '') {
                $busca = mysqli_real_escape_string($conexao, $busca);
                $sql .= " WHERE h.titulo LIKE '%$busca%' OR h.descricao LIKE '%$busca%' ";
            }

            $sql .= " ORDER BY h.id DESC";

            $result = mysqli_query($conexao, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $titulo = htmlspecialchars($row['titulo']);
                    $descricao = htmlspecialchars($row['descricao']);
                    $autor = htmlspecialchars($row['autor']);
                    $data = htmlspecialchars($row['data_memoria']);
                    $img = "/sitelendas/" . ltrim($row['imagem'], "/");
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

                        <?php if ($_SESSION['id'] == $row['usuario_id']): ?>
                            <div class="story-actions">
                                <a href="/sitelendas/stories/edit.php?id=<?= $row['id'] ?>" class="btn-edit">Editar</a>
                                <a href="/sitelendas/stories/delete.php?id=<?= $row['id'] ?>"
                                    class="btn-delete"
                                    onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir esta história?');">
                                    Excluir
                                </a>
                            </div>
                        <?php endif; ?>
                    </article>
            <?php
                }
            } else {
                echo "<p>Nenhuma história publicada ainda.</p>";
            }
            ?>
        </section>
    </main>

    <!-- MODAL -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImg" src="" alt="">
            <h2 id="modalTitulo"></h2>
            <p id="modalDescricao"></p>
            <small id="modalAutor"></small>
        </div>
    </div>

    <script src="/sitelendas/assets/js/menu.js"></script>

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

</body>

</html>