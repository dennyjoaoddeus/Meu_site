<?php
require 'db.php';
include 'includes/header.php';

$message = '';
$error = '';

// Buscar usuários
$stmt_users = $pdo->prepare("SELECT id, name, email FROM users ORDER BY name");
$stmt_users->execute();
$users = $stmt_users->fetchAll();

// Buscar perfis
$stmt_profiles = $pdo->prepare("SELECT id, name FROM profiles ORDER BY name");
$stmt_profiles->execute();
$profiles = $stmt_profiles->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $profile_id = $_POST['profile_id'];

    try {
        // Verificar se o usuário existe
        $stmt_check_user = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt_check_user->execute([$user_id]);
        if (!$stmt_check_user->fetch()) {
            throw new Exception("Usuário não encontrado.");
        }

        // Verificar se o perfil existe
        $stmt_check_profile = $pdo->prepare("SELECT id FROM profiles WHERE id = ?");
        $stmt_check_profile->execute([$profile_id]);
        if (!$stmt_check_profile->fetch()) {
            throw new Exception("Perfil não encontrado.");
        }

        // Verificar se a atribuição já existe
        $stmt_check = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id = ? AND profile_id = ?");
        $stmt_check->execute([$user_id, $profile_id]);
        if ($stmt_check->fetch()) {
            throw new Exception("Este perfil já está atribuído ao usuário.");
        }

        // Inserir a atribuição
        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, profile_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $profile_id]);

        $message = "Perfil atribuído com sucesso!";
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

<h3>Atribuir Perfil ao Usuário</h3>

<form method="post">
    <div>
        <label for="user_id">Usuário:</label><br>
        <select name="user_id" id="user_id" required>
            <option value="">Selecione um usuário</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    
    <div>
        <label for="profile_id">Perfil:</label><br>
        <select name="profile_id" id="profile_id" required>
            <option value="">Selecione um perfil</option>
            <?php foreach ($profiles as $profile): ?>
                <option value="<?= $profile['id'] ?>"><?= htmlspecialchars($profile['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    
    <input type="submit" value="Atribuir Perfil">
</form>

<h3>Atribuições Existentes</h3>
<?php
$stmt_existing = $pdo->prepare("
    SELECT u.name as user_name, u.email, p.name as profile_name 
    FROM user_profiles up
    JOIN users u ON up.user_id = u.id
    JOIN profiles p ON up.profile_id = p.id
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
            <th>Perfil</th>
        </tr>
        <?php foreach ($existing as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['profile_name']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhuma atribuição encontrada.</p>
<?php endif; ?>

</body>
</html>