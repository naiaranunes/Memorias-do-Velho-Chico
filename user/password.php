<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: /SITELENDAS/auth/login.php");
  exit;
}

include("../config/config.php"); 

$codigo = (int)$_SESSION['id'];
$msg = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $senhaAtual = $_POST["senhaAtual"] ?? "";
  $nova       = $_POST["formSenha"] ?? "";
  $conf       = $_POST["formConfirmeSenha"] ?? "";

  if ($senhaAtual === "" || $nova === "" || $conf === "") {
    $msg = "Preencha todos os campos.";
  } elseif ($nova !== $conf) {
    $msg = "As novas senhas não conferem.";
  } else {

   
    $q = "SELECT senha FROM usuarios WHERE id = $codigo";
    $r = mysqli_query($conexao, $q);

    if (!$r) {
      $msg = "Erro ao verificar senha: " . mysqli_error($conexao);
    } else {
      $row = mysqli_fetch_assoc($r);
      $senhaAtualMd5 = md5($senhaAtual);

      if (!$row || $senhaAtualMd5 !== $row["senha"]) {
        $msg = "Senha atual incorreta.";
      } else {

        
        $novaMd5 = md5($nova);
        $query = "UPDATE usuarios SET senha = '$novaMd5' WHERE id = $codigo";
        $ok = mysqli_query($conexao, $query);

        if ($ok) {
          echo "<script>
            alert('Senha alterada com sucesso! Você será redirecionado.');
            setTimeout(function(){
              window.location.href = '/SITELENDAS/index.php';
            }, 1500);
          </script>";
          exit;
        } else {
          $msg = 'Erro ao alterar senha: ' . mysqli_error($conexao);
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
  <title>Alterar Senha</title>
  <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
</head>

<body>

  <?php include("../includes/header_nav.php"); ?>

  <main class="content">
    <section class="register-card">
      <h1>Alterar Senha</h1>

      <?php if ($msg): ?>
        <p style="font-weight:700; color:#0f3f3d; margin-bottom:10px;">
          <?= htmlspecialchars($msg) ?>
        </p>
      <?php endif; ?>

      <form class="register-form" method="POST" action="">
        <label for="idsenhaAtual">Senha atual</label>
        <input type="password" name="senhaAtual" id="idsenhaAtual" required>

        <label for="idsenha">Nova senha</label>
        <input type="password" name="formSenha" id="idsenha" required>

        <label for="idconf">Confirme a nova senha</label>
        <input type="password" name="formConfirmeSenha" id="idconf" required>

        <button type="submit">Alterar</button>
      </form>
    </section>
  </main>

  <script src="/SITELENDAS/assets/js/menu.js"></script>
</body>

</html>