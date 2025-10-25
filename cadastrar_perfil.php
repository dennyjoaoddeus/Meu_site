<?php
require 'db.php';

include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO profiles (name) VALUES (?)");
    $stmt->execute([$name]);
    echo "Perfil cadastrado!";
}
?>
<form method="post">
    Nome do Perfil: <input type="text" name="name">
    <input type="submit" value="Cadastrar Perfil">
</form>

</body>
</html>
