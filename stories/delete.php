<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /sitelendas/auth/login.php");
    exit;
}

include("../config/config.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuarioId = (int)$_SESSION['id'];

if ($id <= 0) {
    header("Location: /sitelendas/principal.php");
    exit;
}


$stmt = $conexao->prepare("SELECT imagem FROM historias WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Você não tem permissão para excluir esta história (ou ela não existe).";
    exit;
}

$dados = $result->fetch_assoc();
$imagem = $dados['imagem'];


$stmt = $conexao->prepare("DELETE FROM historias WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuarioId);

if ($stmt->execute()) {

   
    $arquivo = __DIR__ . "/../" . $imagem;
    if ($imagem && file_exists($arquivo)) {
        @unlink($arquivo);
    }

    header("Location: /sitelendas/principal.php");
    exit;
} else {
    echo "Erro ao excluir.";
}
