<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /SITELENDAS/auth/login.php");
    exit;
}

include("../config/config.php"); 

$codigo = (int)($_POST["formCodigo"] ?? 0);
$senha  = $_POST["formSenha"] ?? "";
$conf   = $_POST["formConfirmeSenha"] ?? "";


if ($codigo !== (int)$_SESSION["id"]) {
    header("Location: /SITELENDAS/user/password.php?status=erro");
    exit;
}


if ($senha !== $conf) {
    header("Location: /SITELENDAS/user/password.php?status=naobate");
    exit;
}


$senhaMd5 = md5($senha);

$query = "UPDATE usuarios SET senha = '$senhaMd5' WHERE id = $codigo";
$acaoUPDATE = mysqli_query($conexao, $query);

if ($acaoUPDATE) {
    header("Location: /SITELENDAS/user/password.php?status=ok");
    exit;
} else {
    header("Location: /SITELENDAS/user/password.php?status=erro");
    exit;
}
