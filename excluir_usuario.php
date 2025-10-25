<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do usuário não fornecido.");
}

// Proteção para não excluir a si mesmo
if ($id == $_SESSION['usuario_id']) {
    die("Você não pode excluir sua própria conta.");
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: ver_usuarios.php");
exit;
