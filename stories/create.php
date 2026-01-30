<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /sitelendas/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>registro memoria</title>
    <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

    <?php include("../includes/header_nav.php"); ?>

    <main class="content">
        <div class="form-container">
            <h2 class="titulo-form">Registrar uma memória</h2>
            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="msg-erro">
                    <?= $_SESSION['mensagem_erro']; ?>
                </div>
                <?php unset($_SESSION['mensagem_erro']); ?>
            <?php endif; ?>
            <form action="story.php" method="POST" enctype="multipart/form-data">
                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="tituloid">

                <label for="descrição">Descrição</label>
                <textarea id="iddescricao" name="descricao" maxlength="200" rows="4" placeholder="Máximo de 200 caracteres" required>
        </textarea>
                <small id="contador">0 / 200</small>

                <label for="data">data</label>
                <input type="date" name="data" id="dataid" required>

                <label for="imagemid">Imagem</label>
                <input type="file" name="imagem" id="imagemid" accept="image/png, image/jpeg" required>

                <button type="submit" class="botao-registro">Registrar</button>
            </form>
        </div>
    </main>

    <script>
        const botao = document.getElementById("menuToggle");
        const menu = document.getElementById("menu");
        botao.addEventListener("click", () => menu.classList.toggle("active"));

        const textarea = document.getElementById("iddescricao");
        const contador = document.getElementById("contador");
        const limite = 200;

        textarea.addEventListener("input", () => {
            const tamanho = textarea.value.length;
            contador.textContent = `${tamanho} / ${limite}`;
        });

        <?php if (isset($_GET['status'])): ?>

            <?php if ($_GET['status'] === 'success'): ?>
                alert("História registrada com sucesso!");
            <?php elseif ($_GET['status'] === 'error'): ?>
                alert("Erro ao registrar a história.");
            <?php endif; ?>

        <?php endif; ?>
    </script>

</body>

</html>