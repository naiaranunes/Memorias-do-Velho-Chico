<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: /SITELENDAS/auth/login.php");
  exit;
}

include("../config/config.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Meus registros</title>
  <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

  <?php include("../includes/header_nav.php"); ?>

  <main class="content">
    <h2>Meus registros</h2>
    <p>Aqui aparecem apenas as histórias que você publicou.</p>

    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
      <div class="msg-sucesso">
        <?= $_SESSION['mensagem_sucesso']; ?>
      </div>
      <?php unset($_SESSION['mensagem_sucesso']); ?>
    <?php endif; ?>

    <section class="stories-grid">
      <?php
      $usuarioId = (int)$_SESSION['id'];
      $busca = $_GET['q'] ?? '';

      $sql = "
        SELECT id, titulo, descricao, data_memoria, imagem
        FROM historias
        WHERE usuario_id = $usuarioId
      ";

      if ($busca !== '') {
        $busca = mysqli_real_escape_string($conexao, $busca);
        $sql .= " AND (titulo LIKE '%$busca%' OR descricao LIKE '%$busca%') ";
      }

      $sql .= " ORDER BY id DESC";

      $result = mysqli_query($conexao, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $titulo = htmlspecialchars($row['titulo']);
          $descricao = htmlspecialchars($row['descricao']);
          $data = htmlspecialchars($row['data_memoria']);
          $img = "/SITELENDAS/" . ltrim($row['imagem'], "/"); 
      ?>
          <article class="story-card">
            <img class="story-img" src="<?= $img ?>" alt="Imagem da história">
            <h3><?= $titulo ?></h3>
            <p><?= $descricao ?></p>
            <small><?= $data ?></small>

            <div class="story-actions">
              <a href="/SITELENDAS/stories/edit.php?id=<?= (int)$row['id'] ?>" class="btn-edit">Editar</a>

              <a href="/SITELENDAS/stories/delete.php?id=<?= (int)$row['id'] ?>"
                class="btn-delete"
                onclick="return confirm('Tem certeza que deseja excluir esta história?');">
                Excluir
              </a>
            </div>
          </article>
      <?php
        }
      } else {
        echo "<p>Você ainda não publicou nenhuma história.</p>";
      }
      ?>
    </section>
  </main>

  <script src="/SITELENDAS/assets/js/menu.js"></script>
</body>

</html>