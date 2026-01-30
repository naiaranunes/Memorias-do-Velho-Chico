<?php
include("../config/config.php");
if (!isset($_POST['email']) || !isset($_POST['senha'])) {
    header("Location: login.php");
    exit;
}
$email = $_POST['email'];
$senha = md5($_POST['senha']);

$query = "SELECT id, nome, email
          FROM usuarios
          WHERE email = '$email' AND senha = '$senha'";

$acaoSELECT = mysqli_query($conexao, $query);

if ($acaoSELECT) {
    if (mysqli_num_rows($acaoSELECT) === 1) {
        $dados = mysqli_fetch_assoc($acaoSELECT);

        session_start();
        $_SESSION['id'] = $dados['id'];
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['email'] = $dados['email'];

        header("Location: /sitelendas/principal.php");
        exit;
    } else {
        echo "<script>alert('E-mail ou senha inválidos');</script>";
        echo "<meta http-equiv='refresh' content='2;url=login.php'>";
    }
} else {
    echo "Erro na consulta ao banco de dados";
}
