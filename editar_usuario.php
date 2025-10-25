<?php

require 'db_config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do usuário não informado.");
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':tipo' => $tipo,
        ':id' => $id
    ]);

    header("Location: ver_usuarios.php");
    exit;
}

// Buscar usuário atual
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>
<body>
    <h2>Editar Usuário</h2>

    <form method="post">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>

        <label>Tipo:</label><br>
        <select name="tipo" required>
            <option value="gestor" <?= $usuario['tipo'] === 'gestor' ? 'selected' : '' ?>>Gestor
            <option value="vendedor" <?= $usuario['tipo'] === 'vendedor' ? 'selected' : '' ?>>vendedor

        </select><br><br>
        <input type="submit" value="Salvar">
    </form>       
