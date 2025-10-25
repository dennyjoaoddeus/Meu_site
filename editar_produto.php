<?php

require 'db_config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do produto não informado.");
}

// Processa atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "UPDATE produtos 
            SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':quantidade' => $quantidade,
        ':id' => $id
    ]);

    header("Location: ver_produtos.php");
    exit;
}

// Busca dados do produto
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>
<body>
    <h2>Editar Produto</h2>

    <form method="post">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required><br><br>

        <label>Descrição:</label><br>
        <input type="text" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required><br><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required><br><br>

        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" required><br><br>

        <input type="submit" value="Salvar Alterações">
    </form>

    <br><a href="ver_produto.php">Voltar</a>
</body>
</html>
