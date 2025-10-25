<?php
require 'db.php';
include 'includes/header.php'; 

// Estatísticas básicas
$stmt_users = $pdo->prepare("SELECT COUNT(*) as total FROM users");
$stmt_users->execute();
$total_users = $stmt_users->fetch()['total'];

$stmt_profiles = $pdo->prepare("SELECT COUNT(*) as total FROM profiles");
$stmt_profiles->execute();
$total_profiles = $stmt_profiles->fetch()['total'];

$stmt_permissions = $pdo->prepare("SELECT COUNT(*) as total FROM permissions");
$stmt_permissions->execute();
$total_permissions = $stmt_permissions->fetch()['total'];

$stmt_user_profiles = $pdo->prepare("SELECT COUNT(*) as total FROM user_profiles");
$stmt_user_profiles->execute();
$total_user_profiles = $stmt_user_profiles->fetch()['total'];
?>

<h3>Painel de Administração - Gestão de Acessos</h3>

<div style="display: flex; gap: 20px; margin: 20px 0;">
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; text-align: center; min-width: 120px;">
        <h4>Usuários</h4>
        <p style="font-size: 24px; font-weight: bold; color: #007bff;"><?= $total_users ?></p>
    </div>
    
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; text-align: center; min-width: 120px;">
        <h4>Perfis</h4>
        <p style="font-size: 24px; font-weight: bold; color: #28a745;"><?= $total_profiles ?></p>
    </div>
    
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; text-align: center; min-width: 120px;">
        <h4>Permissões</h4>
        <p style="font-size: 24px; font-weight: bold; color: #ffc107;"><?= $total_permissions ?></p>
    </div>
    
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; text-align: center; min-width: 120px;">
        <h4>Atribuições</h4>
        <p style="font-size: 24px; font-weight: bold; color: #dc3545;"><?= $total_user_profiles ?></p>
    </div>
</div>

<h4>Ações Disponíveis:</h4>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 20px 0;">
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>👤 Gerenciar Usuários</h5>
        <p>Cadastre novos usuários no sistema</p>
        <a href="cadastrar_usuario.php" style="text-decoration: none; background: #007bff; color: white; padding: 8px 15px; border-radius: 3px;">Cadastrar Usuário</a>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>👥 Gerenciar Perfis</h5>
        <p>Crie e gerencie perfis de acesso</p>
        <a href="cadastrar_perfil.php" style="text-decoration: none; background: #28a745; color: white; padding: 8px 15px; border-radius: 3px;">Cadastrar Perfil</a>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>🔐 Gerenciar Permissões</h5>
        <p>Defina permissões específicas</p>
        <a href="cadastrar_permissao.php" style="text-decoration: none; background: #ffc107; color: black; padding: 8px 15px; border-radius: 3px;">Cadastrar Permissão</a>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>🔗 Atribuir Perfis</h5>
        <p>Associe perfis aos usuários</p>
        <a href="atribuir_perfil.php" style="text-decoration: none; background: #17a2b8; color: white; padding: 8px 15px; border-radius: 3px;">Atribuir Perfil</a>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>⚙️ Permissões Diretas</h5>
        <p>Atribua permissões diretamente aos usuários</p>
        <a href="atribuir_permissao_usuario.php" style="text-decoration: none; background: #6f42c1; color: white; padding: 8px 15px; border-radius: 3px;">Permissão ao Usuário</a>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h5>🎯 Permissões por Perfil</h5>
        <p>Configure permissões para perfis</p>
        <a href="atribuir_permissao_perfil.php" style="text-decoration: none; background: #fd7e14; color: white; padding: 8px 15px; border-radius: 3px;">Permissão ao Perfil</a>
    </div>
</div>

<h4>Últimas Atividades:</h4>

<?php
// Últimos usuários cadastrados
$stmt_recent_users = $pdo->prepare("SELECT name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$stmt_recent_users->execute();
$recent_users = $stmt_recent_users->fetchAll();
?>

<div style="margin: 20px 0;">
    <h5>Últimos Usuários Cadastrados:</h5>
    <?php if ($recent_users): ?>
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Data de Cadastro</th>
            </tr>
            <?php foreach ($recent_users as $user): ?>
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
</div>

<div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
    <h5>💡 Dicas de Uso:</h5>
    <ul>
        <li>Comece cadastrando as <strong>permissões</strong> básicas do sistema</li>
        <li>Crie <strong>perfis</strong> que agrupe permissões relacionadas</li>
        <li>Cadastre os <strong>usuários</strong> e atribua perfis apropriados</li>
        <li>Use <strong>permissões diretas</strong> apenas para casos específicos</li>
        <li>Consulte regularmente as permissões dos usuários para auditoria</li>
    </ul>
</div>

</body>
</html>