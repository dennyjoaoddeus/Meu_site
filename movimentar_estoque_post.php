<?php

require 'db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = (int)$_POST['quantidade'];
    $usuario_id = $_SESSION['usuario_id'];

    // Inserir movimentação
    $sql = "INSERT INTO movimentos_estoque (produto_id, tipo, quantidade, data, usuario_id)
            VALUES (:produto_id, :tipo, :quantidade, NOW(), :usuario_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':produto_id', $produto_id);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':usuario_id', $usuario_id);

    try {
        $stmt->execute();
        echo "Movimentação registrada com sucesso!<br>";
        echo "<a href='movimentar_estoque.php'>Registrar outra</a><br>";
        echo "<a href='painel.php'>Voltar ao painel</a>";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
