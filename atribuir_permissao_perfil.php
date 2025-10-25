<?php
require 'db.php';

include 'includes/header.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $profile_id = $_POST['profile_id'];
    $permission_id = $_POST['permission_id'];

    $stmt = $pdo->prepare("INSERT IGNORE INTO profile_permissions (profile_id, permission_id) VALUES (?, ?)");
    $stmt->execute([$profile_id, $permission_id]);

    echo "Permissão atribuída ao perfil!";
}
?>
<form method="post">
    ID do Perfil: <input type="text" name="profile_id" required>
    ID da Permissão: <input type="text" name="permission_id" required>
    <input type="submit" value="Atribuir Permissão ao Perfil">
</form>

</body>
</html>
