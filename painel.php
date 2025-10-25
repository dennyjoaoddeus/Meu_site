<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

echo "<h2>Bem-vindo, " . htmlspecialchars($_SESSION['nome']) . "</h2>";

if ($_SESSION['tipo'] === 'gestor') {
    echo "<p>Área do Gestor</p>";
    echo "<a href='ver_usuarios.php'>Gerenciar Usuários</a><br>";
    echo "<a href='cadastrar_produto.php'>Cadastrar Produto</a><br>";
    echo "<a href='cadastro.php'>Cadastrar Utilizador</a><br>";
    echo "<a href='movimentar_estoque.php'>Registrar Movimentação</a><br>";
    echo "<a href='historico_movimentacoes.php'>Histórico de Movimentações</a><br>";
    echo "<a href='estoque_atual.php'>Estoque Atual</a><br>";
    echo "<a href='ver_produto.php'>Gerenciar Produtos</a><br>";

} else {
    echo "<p>Área do Funcionário</p>";
    echo "<a href='cadastrar_produto.php'>Cadastrar Produto</a><br>";
    echo "<a href='movimentar_estoque.php'>Registrar Movimentação</a><br>";
    echo "<a href='historico_movimentacoes.php'>Histórico de Movimentações</a><br>";
    
}

echo "<br><a href='logout.php'>Sair</a>";
?>
