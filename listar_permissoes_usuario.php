<?php
require 'db.php';
include 'includes/header.php';

// Buscar todos os usuários para o dropdown
$stmt_users = $pdo->prepare("SELECT id, name, email FROM users ORDER BY name");
$stmt_users->execute();
$users = $stmt_users->fetchAll();

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : null);
$permissions = [];
$user_info = null;

if ($user_id) {
    // Buscar informações do usuário
    $stmt_user = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user_info = $stmt_user->fetch();

    if ($user_info) {
        // Buscar todas as permissões do usuário (diretas e por perfil)
        $stmt = $pdo->prepare("
            SELECT DISTINCT 
                p.name as permission_name,
                CASE 
                    WHEN up.permission_id IS NOT NULL THEN 'Direta'
                    ELSE 'Por Perfil'
                END as source_type,
                CASE 
                    WHEN up.permission_id IS NOT NULL THEN 'Permissão direta'
                    ELSE GROUP_CONCAT(DISTINCT pr.name SEPARATOR ', ')
                END as source_name
            FROM permissions p
            LEFT JOIN user_permissions up ON p.id = up.permission_id AND up.user_id = ?
            LEFT JOIN profile_permissions pp ON p.id = pp.permission_id
            LEFT JOIN user_profiles upf ON pp.profile_id = upf.profile_id AND upf.user_id = ?
            LEFT JOIN profiles pr ON pp.profile_id = pr.id
            WHERE up.permission_id IS NOT NULL OR (pp.permission_id IS NOT NULL AND upf.user_id IS NOT NULL)
            GROUP BY p.id, p.name, up.permission_id
            ORDER BY p.name
        ");

        $stmt->execute([$user_id, $user_id]);
        $permissions = $stmt->fetchAll();
    }
}
?>

<h3>Listar Permissões do Usuário</h3>

<form method="post">
    <div>
        <label for="user_id">Selecione o Usuário:</label><br>
        <select name="user_id" id="user_id" required>
            <option value="">Selecione um usuário</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>" <?= ($user_id == $user['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    <input type="submit" value="Listar Permissões">
</form>

<?php if ($user_id && $user_info): ?>
    <h4>Permissões de: <?= htmlspecialchars($user_info['name']) ?> (<?= htmlspecialchars($user_info['email']) ?>)</h4>
    
    <?php if ($permissions): ?>
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>Permissão</th>
                <th>Tipo</th>
                <th>Origem</th>
            </tr>
            <?php foreach ($permissions as $perm): ?>
                <tr>
                    <td><?= htmlspecialchars($perm['permission_name']) ?></td>
                    <td><?= htmlspecialchars($perm['source_type']) ?></td>
                    <td><?= htmlspecialchars($perm['source_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Este usuário não possui permissões atribuídas.</p>
    <?php endif; ?>

<?php elseif ($user_id): ?>
    <p style="color: red;">Usuário não encontrado.</p>
<?php endif; ?>

</body>
</html>