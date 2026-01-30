<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /sitelendas/auth/login.php");
    exit;
}

include("../config/config.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /sitelendas/principal.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$usuarioId = (int)$_SESSION['id'];

$titulo = trim($_POST["titulo"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$data = $_POST["data"] ?? "";

if ($id <= 0 || $titulo === "" || $descricao === "" || $data === "") {
    echo "Dados inválidos.";
    exit;
}

if (strlen($descricao) > 200) {
    echo "Descrição deve ter no máximo 200 caracteres.";
    exit;
}


$stmt = $conexao->prepare("SELECT imagem FROM historias WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Sem permissão para atualizar.";
    exit;
}

$dados = $result->fetch_assoc();
$imagemAntiga = $dados['imagem'];
$imagemNoBanco = $imagemAntiga;


if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === 0) {
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

    $extensao = ($tipoArquivo === "image/png") ? ".png" : ".jpg";
    $nomeFinal = uniqid("hist_", true) . $extensao;

    $pastaUploads = __DIR__ . "/../uploads/";
    if (!is_dir($pastaUploads)) {
        mkdir($pastaUploads, 0777, true);
    }

    $caminhoFinal = $pastaUploads . $nomeFinal;

    if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoFinal)) {
        echo "Não foi possível salvar a nova imagem.";
        exit;
    }

    $imagemNoBanco = "uploads/" . $nomeFinal;

    
    $arquivoAntigo = __DIR__ . "/../" . $imagemAntiga;
    if ($imagemAntiga && file_exists($arquivoAntigo)) {
        @unlink($arquivoAntigo);
    }
}

/* UPDATE */
$stmt = $conexao->prepare("UPDATE historias
                           SET titulo = ?, descricao = ?, data_memoria = ?, imagem = ?
                           WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ssssii", $titulo, $descricao, $data, $imagemNoBanco, $id, $usuarioId);

if ($stmt->execute()) {
    header("Location: /sitelendas/principal.php");
    exit;
} else {
    echo "Erro ao atualizar.";
}
