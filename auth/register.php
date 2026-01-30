<?php
include("../config/config.php");

if (isset($_POST['submit'])) {

    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $data  = $_POST['data_nascimento'];
    $senha = md5($_POST['senha']);

    $query = "INSERT INTO usuarios (nome, email, data_nascimento, senha)
              VALUES ('$nome', '$email', '$data', '$senha')";

    $acaoINSERT = mysqli_query($conexao, $query);

    if ($acaoINSERT) {
        echo "<p>Cadastro realizado com sucesso!</p>";
        echo "<p><a href='register.php'>Cadastrar novo usuário</a></p>";
    } else {
        echo "ERRO!";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SITELENDAS/assets/css/style.css?v=1">
    <title>Criar</title>
</head>

<body>
    <section class="register-card">
        <a href="/SITELENDAS/index.php" class="btn-voltar">← Voltar</a>
        <h1>Crie sua conta</h1>

        <form class="register-form" action="register.php" method="POST">
            <label for="idnome">Nome</label>
            <input type="text" name="nome" id="idnome" required>

            <label for="idemail">E-mail</label>
            <input type="email" name="email" id="idemail" required>

            <label for="iddata_nascimento">Data de Nascimento</label>
            <input type="date" name="data_nascimento" id="iddata_nascimento">

            <label for="idsenha">Senha</label>
            <input type="password" name="senha" id="idsenha">

            <input type="submit" name="submit" value="Criar Conta">
        </form>
    </section>

</body>

</html>