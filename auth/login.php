<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
  <title>login</title>
</head>

<body>
  <section class="register-card">
    <h1>Faça seu login</h1>

    <form class="register-form" action="process_login.php" method="POST">
      <label for="idemail">E-mail</label>
      <input type="email" name="email" id="idemail" required>

      <label for="idsenha">Senha</label>
      <input type="password" name="senha" id="idsenha" required>

      <input type="submit" value="Entrar">
    </form>
  </section>
</body>

</html>