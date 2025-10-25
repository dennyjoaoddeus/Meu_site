<?php

require 'db_config.php';
session_start();

// Só gestores podem acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

// Buscar todos os usuários
$sql = "SELECT id, nome, email, tipo FROM usuarios";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Usuários Cadastrados</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['tipo']) ?></td>
            <td>
                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">Editar</a> |
                <a href="excluir_usuario.php?id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="painel.php">Voltar ao Painel</a>
