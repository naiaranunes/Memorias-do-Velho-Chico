<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: /sitelendas/auth/login.php");
  exit;
}

include("../config/config.php"); 

$codigo = (int)$_SESSION['id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  
  $nome  = trim($_POST["formNome"] ?? "");
  $email = trim($_POST["formEmail"] ?? "");
  $data  = $_POST["formData"] ?? null;

  if ($nome === "" || $email === "") {
    $msg = "Preencha nome e e-mail.";
  } else {
    $nomeEsc  = mysqli_real_escape_string($conexao, $nome);
    $emailEsc = mysqli_real_escape_string($conexao, $email);
    $dataEsc  = $data ? ("'" . mysqli_real_escape_string($conexao, $data) . "'") : "NULL";

    $queryUpdate = "
      UPDATE usuarios
      SET nome = '$nomeEsc',
          email = '$emailEsc',
          data_nascimento = $dataEsc
      WHERE id = $codigo
    ";

    $acaoUPDATE = mysqli_query($conexao, $queryUpdate);

    if ($acaoUPDATE) {
      $_SESSION['nome'] = $nome;
      $msg = "Dados atualizados com sucesso!";
    } else {
      $msg = "Erro ao atualizar: " . mysqli_error($conexao);
    }
  }
}


$query = "SELECT nome, email, data_nascimento FROM usuarios WHERE id = $codigo";
$acaoSELECT = mysqli_query($conexao, $query);

if ($acaoSELECT) {
  $dados = mysqli_fetch_array($acaoSELECT);
} else {
  echo "ERRO AO BUSCAR DADOS: " . mysqli_error($conexao);
  exit;
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Editar Usuário</title>
  <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
  <style>
    body {
      outline: 5px solid red;
    }
  </style>
</head>

<body>
  <?php
  include("../includes/header_nav.php");
  ?>
  <main class="content">
    <section class="register-card">
      <h1>Editar Usuário</h1>

      <?php if ($msg): ?>
        <p style="font-weight:600; color:#0f3f3d; margin-bottom:10px;">
          <?= htmlspecialchars($msg) ?>
        </p>
      <?php endif; ?>

      <form class="register-form" method="POST" action="/sitelendas/user/profile.php">
        <label for="idnome">Nome</label>
        <input type="text" name="formNome" id="idnome" required
          value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">

        <label for="idemail">E-mail</label>
        <input type="email" name="formEmail" id="idemail" required
          value="<?= htmlspecialchars($dados['email'] ?? '') ?>">

        <label for="iddata">Data de Nascimento</label>
        <input type="date" name="formData" id="iddata"
          value="<?= htmlspecialchars($dados['data_nascimento'] ?? '') ?>">

        <button type="submit">Salvar</button>
      </form>
    </section>
  </main>

  <script src="/sitelendas/assets/js/menu.js"></script>
</body>

</html>