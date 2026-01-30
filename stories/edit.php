<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /sitelendas/auth/login.php");
    exit;
}

include("../config/config.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuarioId = (int)$_SESSION['id'];

if ($id <= 0) {
    header("Location: /sitelendas/principal.php");
    exit;
}


$stmt = $conexao->prepare("SELECT id, titulo, descricao, data_memoria, imagem
                           FROM historias
                           WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Você não tem permissão para editar esta história (ou ela não existe).";
    exit;
}

$historia = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar história</title>
    <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

    <?php include("../includes/header_nav.php"); ?>

    <main class="content">
        <h2>Editar história</h2>

        <div class="form-container">
            <form action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int)$historia['id'] ?>">

                <label for="tituloid">Título</label>
                <input type="text" name="titulo" id="tituloid" required
                    value="<?= htmlspecialchars($historia['titulo']) ?>">

                <label for="iddescricao">Descrição</label>
                <textarea name="descricao" id="iddescricao" maxlength="200" rows="4" required><?= htmlspecialchars($historia['descricao']) ?></textarea>

                <small id="contador">0 / 200</small>

                <label for="dataid">Data</label>
                <input type="date" name="data" id="dataid" required
                    value="<?= htmlspecialchars($historia['data_memoria']) ?>">

                <p style="margin-top:10px;">
                    <strong>Imagem atual:</strong><br>
                    <img src="/sitelendas/<?= htmlspecialchars($historia['imagem']) ?>" alt="Imagem atual" style="max-width:220px;border-radius:8px;margin-top:8px;">
                </p>

                <label for="imagemid">Trocar imagem (opcional)</label>
                <input type="file" name="imagem" id="imagemid" accept="image/png, image/jpeg">

                <button type="submit">Salvar alterações</button>
            </form>
        </div>
    </main>

    <script src="/sitelendas/assets/js/menu.js"></script>

</body>

</html>