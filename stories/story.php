<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /sitelendas/auth/login.php");
    exit;
}

include("../config/config.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /sitelendas/stories/create.php");
    exit;
}

$titulo = trim($_POST["titulo"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$data = $_POST["data"] ?? "";

if ($titulo === "" || $descricao === "" || $data === "") {
    echo "Preencha todos os campos.";
    exit;
}

if (strlen($descricao) > 200) {
    echo "Descrição deve ter no máximo 200 caracteres.";
    exit;
}


if (!isset($_FILES["imagem"]) || $_FILES["imagem"]["error"] !== 0) {
    echo "Erro no envio da imagem.";
    exit;
}

$tiposPermitidos = ["image/png", "image/jpeg"];
$tipoArquivo = $_FILES["imagem"]["type"];
$tamanho = $_FILES["imagem"]["size"];

if (!in_array($tipoArquivo, $tiposPermitidos)) {
    echo "Envie uma imagem PNG ou JPEG.";
    exit;
}

if ($tamanho > 2 * 1024 * 1024) {
    echo "Imagem muito grande. Máximo 2MB.";
    exit;
}

$extensao = ($tipoArquivo === "image/png") ? ".png" : ".jpeg";
$nomeFinal = uniqid("hist_", true) . $extensao;

$pastaUploads = __DIR__ . "/../uploads/";
if (!is_dir($pastaUploads)) {
    mkdir($pastaUploads, 0777, true);
}

$caminhoFinal = $pastaUploads . $nomeFinal;

if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoFinal)) {
    echo "Não foi possível salvar a imagem.";
    exit;
}

$imagemNoBanco = "uploads/" . $nomeFinal;


$usuarioId = $_SESSION["id"];

$stmt = $conexao->prepare(
    "INSERT INTO historias (usuario_id, titulo, descricao, data_memoria, imagem)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("issss", $usuarioId, $titulo, $descricao, $data, $imagemNoBanco);

if ($stmt->execute()) {
    header("Location: /sitelendas/stories/create.php?status=success");
    exit;
} else {
    header("Location: /sitelendas/stories/create.php?status=error");
    exit;
}
