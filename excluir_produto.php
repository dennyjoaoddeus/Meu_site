<?php

require 'db_config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do produto não fornecido.");
}

$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: ver_produtos.php");
exit;
