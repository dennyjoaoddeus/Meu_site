<?php
// ========== cadastrar_usuario.php MELHORADO ==========
?>
<?php
require 'db.php';
include 'includes/header.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        // Validações
        if (empty($name)) {
            throw new Exception("Nome é obrigatório.");
        }
        
        if (empty($email)) {
            throw new Exception("Email é obrigatório.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }
        
        if (strlen($password) < 6) {
            throw new Exception("Senha deve ter pelo menos 6 caracteres.");
        }
        
        if ($password !== $confirm_password) {
            throw new Exception("Senhas não coincidem.");
        }

        // Verificar se email já existe
        $stmt_check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetch()) {
            throw new Exception("Este email já está cadastrado.");
        }

        // Inserir usuário
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password_hash]);

        $message = "Usuário cadastrado com sucesso!";
        
        // Limpar campos após sucesso
        $_POST = [];
        
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

<h3>Cadastrar Novo Usuário</h3>

<form method="post" style="max-width: 400px;">
    <div style="margin-bottom: 15px;">
        <label for="name">Nome Completo:</label><br>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; margin-top: 5px;" 
               value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px;"
               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="password">Senha (mínimo 6 caracteres):</label><br>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;" minlength="6">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="confirm_password">Confirmar Senha:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 8px; margin-top: 5px;" minlength="6">
    </div>

    <input type="submit" value="Cadastrar Usuário" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer;">
</form>

<h4>Usuários Cadastrados:</h4>
<?php
$stmt_users = $pdo->prepare("SELECT name, email, created_at FROM users ORDER BY created_at DESC");
$stmt_users->execute();
$users = $stmt_users->fetchAll();
?>

<?php if ($users): ?>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Cadastro</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum usuário cadastrado ainda.</p>
<?php endif; ?>

<script>
// Validação de senha em tempo real
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword && confirmPassword.length > 0) {
        this.style.borderColor = 'red';
    } else {
        this.style.borderColor = '';
    }
});
</script>

</body>
</html>
