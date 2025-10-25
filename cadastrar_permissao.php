<?php
require 'db.php';

include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO permissions (name) VALUES (?)");
    $stmt->execute([$name]);
    echo "Permissão cadastrada!";
}
?>
<form method="post">
    Nome da Permissão: <input type="text" name="name">
    <input type="submit" value="Cadastrar Permissão">
</form>

</body>
</html>
