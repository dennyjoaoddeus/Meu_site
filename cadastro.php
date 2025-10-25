<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Cadastro de Utilizador</h2>
     <form action="cadastro_post.php" method="post">

        <label for="tipo">Utilizador:</label><br>
        <select name="tipo" required>
        <option value="gestor">Gestor</option>
        <option value="vendedor">vendedor</option>
        </select><br><br>

        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <input type="submit"  value="Cadastrar">
    </form>
</body>
</html>