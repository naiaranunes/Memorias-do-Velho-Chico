<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: /SITELENDAS/auth/login.php");
  exit;
}

include("../config/config.php"); 

$usuarioId = (int)$_SESSION['id'];
$msg = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $confirmar = trim($_POST["confirmar"] ?? "");
  $senha     = $_POST["senha"] ?? "";

  if ($confirmar !== "DELETAR") {
    $msg = "Digite DELETAR para confirmar.";
  } else {

    
    $senhaMd5 = md5($senha);

    
    $q = "SELECT senha FROM usuarios WHERE id = $usuarioId";
    $r = mysqli_query($conexao, $q);

    if (!$r) {
      $msg = "Erro ao buscar senha: " . mysqli_error($conexao);
    } else {
      $row = mysqli_fetch_assoc($r);

      if (!$row || $senhaMd5 !== $row["senha"]) {
        $msg = "Senha incorreta.";
      } else {

       
        $delHistorias = "DELETE FROM historias WHERE usuario_id = $usuarioId";
        mysqli_query($conexao, $delHistorias);

    
        $delUser = "DELETE FROM usuarios WHERE id = $usuarioId";
        $ok = mysqli_query($conexao, $delUser);

        if ($ok) {
          session_destroy();
          header("Location: /SITELENDAS/index.php?status=conta_deletada");
          exit;
        } else {
          $msg = "Erro ao deletar conta: " . mysqli_error($conexao);
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Deletar conta</title>
  <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

  <?php include("../includes/header_nav.php"); ?>

  <main class="content">
    <section class="register-card">
      <h1>Deletar conta</h1>

      <p style="color:#b42318; font-weight:700;">
        Atenção: essa ação é permanente. Sua conta será removida.
      </p>

      <?php if ($msg): ?>
        <p style="color:#b42318; font-weight:700;"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>

      
      <form class="register-form" method="POST" action="">
        <label for="idsenha">Confirme sua senha</label>
        <input type="password" name="senha" id="idsenha" required>

        <label for="idconf">
          Para confirmar, digite <strong>DELETAR</strong>
        </label>
        <input type="text" name="confirmar" id="idconf" required>

        <button type="submit" class="btn-danger">Deletar minha conta</button>
      </form>
    </section>
  </main>

 
  <script src="/SITELENDAS/assets/js/menu.js"></script>

 
  <script>
    document.querySelector(".register-form").addEventListener("submit", function(e) {
      const ok = confirm("Tem certeza que deseja excluir sua conta? Essa ação é permanente.");
      if (!ok) {
        e.preventDefault();
      }
    });
  </script>

</body>

</html>