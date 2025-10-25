<?php

require 'db_config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar movimentações com join para produto e usuário
$sql = "SELECT m.*, p.nome AS nome_produto, u.nome AS nome_usuario
        FROM movimentos_estoque m
        JOIN produtos p ON m.produto_id = p.id
        JOIN usuarios u ON m.usuario_id = u.id
        ORDER BY m.data DESC";

$stmt = $pdo->query($sql);
$movs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Histórico de Movimentações</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Data</th>
        <th>Produto</th>
        <th>Tipo</th>
        <th>Quantidade</th>
        <th>Usuário</th>
    </tr>
    <?php foreach ($movs as $m): ?>
        <tr>
            <td><?= $m['data'] ?></td>
            <td><?= htmlspecialchars($m['nome_produto']) ?></td>
            <td><?= ucfirst($m['tipo']) ?></td>
            <td><?= $m['quantidade'] ?></td>
            <td><?= htmlspecialchars($m['nome_usuario']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="painel.php">Voltar ao Painel</a>
