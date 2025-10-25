<?php 

require 'db_config.php';

session_start();

// Só gestores podem acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

// Buscar todos os produtos
$sql = "SELECT id, nome, descricao, preco, quantidade, criado_em FROM produtos";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
</head>
<body>
    <h2>Produtos Cadastrados</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= $produto['quantidade'] ?></td>
                <td><?= $produto['criado_em'] ?></td>
                <td>
                    <a href="editar_produto.php?id=<?= $produto['id'] ?>">Editar</a> |
                    <a href="excluir_produto.php?id=<?= $produto['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="painel.php">Voltar ao Painel</a>
</body>
</html>
