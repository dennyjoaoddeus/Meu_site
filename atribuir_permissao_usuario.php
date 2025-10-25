<?php
// ========== atribuir_permissao_usuario.php MELHORADO ==========
?>
<?php
require 'db.php';
include 'includes/header.php';

$message = '';
$error = '';

// Buscar usuários
$stmt_users = $pdo->prepare("SELECT id, name, email FROM users ORDER BY name");
$stmt_users->execute();
$users = $stmt_users->fetchAll();

// Buscar permissões
$stmt_permissions = $pdo->prepare("SELECT id, name FROM permissions ORDER BY name");
$stmt_permissions->execute();
$permissions = $stmt_permissions->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $permission_id = $_POST['permission_id'];

    try {
        // Verificações
        $stmt_check_user = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt_check_user->execute([$user_id]);
        if (!$stmt_check_user->fetch()) {
            throw new Exception("Usuário não encontrado.");
        }

        $stmt_check_permission = $pdo->prepare("SELECT id FROM permissions WHERE id = ?");
        $stmt_check_permission->execute([$permission_id]);
        if (!$stmt_check_permission->fetch()) {
            throw new Exception("Permissão não encontrada.");
        }

        // Verificar se já existe
        $stmt_check_existing = $pdo->prepare("SELECT id FROM user_permissions WHERE user_id = ? AND permission_id = ?");
        $stmt_check_existing->execute([$user_id, $permission_id]);
        if ($stmt_check_existing->fetch()) {
            throw new Exception("Esta permissão já está atribuída ao usuário.");
        }

        // Inserir
        $stmt = $pdo->prepare("INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $permission_id]);

        $message = "Permissão atribuída ao usuário com sucesso!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<?php if ($message): ?>
    <div style="color: green; margin: 10px 0; padding: 10px; border: 1px solid green; background-color: #e8f5e8;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="color: red; margin: 10px 0; padding: 10px; border: 1px solid red; background-color: #ffe8e8;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<h3>Atribuir Permissão Direta ao Usuário</h3>

<form method="post">
    <div style="margin-bottom: 15px;">
        <label for="user_id">Usuário:</label><br>
        <select name="user_id" id="user_id" required style="width: 100%; padding: 8px; margin-top: 5px;">
            <option value="">Selecione um usuário</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="permission_id">Permissão:</label><br>
        <select name="permission_id" id="permission_id" required style="width: 100%; padding: 8px; margin-top: 5px;">
            <option value="">Selecione uma permissão</option>
            <?php foreach ($permissions as $permission): ?>
                <option value="<?= $permission['id'] ?>"><?= htmlspecialchars($permission['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <input type="submit" value="Atribuir Permissão ao Usuário" style="background: #6f42c1; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer;">
</form>

<h4>Permissões Diretas Existentes:</h4>
<?php
$stmt_existing = $pdo->prepare("
    SELECT u.name as user_name, u.email, p.name as permission_name 
    FROM user_permissions up
    JOIN users u ON up.user_id = u.id
    JOIN permissions p ON up.permission_id = p.id
    ORDER BY u.name, p.name
");
$stmt_existing->execute();
$existing = $stmt_existing->fetchAll();
?>

<?php if ($existing): ?>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Usuário</th>
            <th>Email</th>
            <th>Permissão</th>
        </tr>
        <?php foreach ($existing as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['permission_name']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhuma permissão direta atribuída ainda.</p>
<?php endif; ?>

</body>
</html>