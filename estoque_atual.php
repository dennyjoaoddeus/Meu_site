<?php

require 'db_config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar saldo atual por produto
$sql = "SELECT 
            p.nome, 
            COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE -m.quantidade END), 0) AS saldo
        FROM produtos p
        LEFT JOIN movimentos_estoque m ON p.id = m.produto_id
        GROUP BY p.id";

$stmt = $pdo->query($sql);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Estoque Atual</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Produto</th>
        <th>Quantidade em Estoque</th>
    </tr>
    <?php foreach ($estoque as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['nome']) ?></td>
            <td><?= $e['saldo'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="painel.php">Voltar ao Painel</a>
