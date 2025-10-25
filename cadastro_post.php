<?php 
require 'db_config.php';

$tipo = $_POST['tipo'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

   // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir no banco
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':tipo', $tipo);

    try {
        $stmt->execute();
        echo "Usuário cadastrado com sucesso!";
    } catch (PDOException $e) {
     
            echo "Erro: " . $e->getMessage();
        }
}
?>
<a href="painel.php">Fazer Login</a>