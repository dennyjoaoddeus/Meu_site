<?php

require 'db_config.php';
session_start();

// Só usuários logados (gestor ou funcionário)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar produtos
$stmt = $pdo->query("SELECT id, nome FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Registrar Movimentação de Estoque</h2>

<form action="movimentar_estoque_post.php" method="post">
    <label for="produto_id">Produto:</label><br>
    <select name="produto_id" required>
        <option value="">Selecione</option>
        <?php foreach ($produtos as $produto): ?>
            <option value="<?= $produto['id'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="tipo">Tipo de Movimentação:</label><br>
    <select name="tipo" required>
        <option value="entrada">Entrada</option>
        <option value="saida">Saída</option>
    </select><br><br>

    <label for="quantidade">Quantidade:</label><br>
    <input type="number" name="quantidade" required min="1"><br><br>

    <input type="submit" value="Registrar">
</form>

<a href="painel.php">Voltar ao Painel</a>
