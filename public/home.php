<?php
include("../app/conexao.php");
$sql = "SELECT COUNT(*) AS total FROM professores";
$result = $connection->query($sql);
$totalProfessores = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $totalProfessores = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agenda - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="top-header">
    <div class="logo-section">
        <h2>🎓 Sistema Acadêmico SESI/SENAI</h2>
    </div>
    <div class="user-info" onclick="alert('Perfil do usuário')">
        <div class="user-avatar">AD</div>
        <div class="user-details">
            <div class="user-name">Admin Sistema</div>
            <div class="user-role">Administrador</div>
        </div>
    </div>
    <button class="logout-btn" onclick="alert('Logout realizado')">Sair</button>
</div>

<div class="content-area">
    <div class="welcome-section">
        <h1>Bem-vindo de volta! 👋</h1>
        <p>Aqui está um resumo do seu sistema acadêmico</p>
    </div>

    <div class="cards-container">

        <!-- Card de Professores -->
        <div class="card card-professores">
            <div class="card-content">
                <div class="card-icon">👨‍🏫</div>
                <div class="card-title">Professores</div>
                <div class="card-description">
                    Gerencie e visualize a lista de professores cadastrados no sistema.
                </div>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalProfessores; ?></span>
                    <span class="card-stats-label">Professores Cadastrados</span>
                </div>
                <button class="card-button" onclick="alert('Visualizar Professores')">Visualizar</button>
            </div>
        </div>

        <!-- Card de Unidades Curriculares -->
        <div class="card card-unidades">
            <div class="card-content">
                <div class="card-icon">📚</div>
                <div class="card-title">Unidades Curriculares</div>
                <div class="card-description">Consulte todas as disciplinas/unidades curriculares disponíveis.</div>
                <div class="card-stats">
                    <span class="card-stats-number">18</span>
                    <span class="card-stats-label">Unidades Disponíveis</span>
                </div>
                <button class="card-button" onclick="alert('Visualizar UCs')">Visualizar</button>
            </div>
        </div>

        <!-- Card de Turmas -->
        <div class="card card-turmas">
            <div class="card-content">
                <div class="card-icon">👥</div>
                <div class="card-title">Turmas</div>
                <div class="card-description">Acompanhe as turmas cadastradas e seus respectivos turnos.</div>
                <div class="card-stats">
                    <span class="card-stats-number">12</span>
                    <span class="card-stats-label">Turmas Ativas</span>
                </div>
                <button class="card-button" onclick="alert('Visualizar Turmas')">Visualizar</button>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="stats-section">
        <h2>📊 Estatísticas do Sistema</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">156</div>
                <div class="stat-label">Total de Aulas Agendadas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">8</div>
                <div class="stat-label">Aulas Hoje</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">92%</div>
                <div class="stat-label">Taxa de Ocupação</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">3</div>
                <div class="stat-label">Pendências</div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="quick-actions">
        <h2>⚡ Ações Rápidas</h2>
        <div class="actions-grid">
            <button class="action-btn" onclick="alert('Cadastrar Professor')">
                <span class="action-icon">➕</span>
                <span>Novo Professor</span>
            </button>
            <button class="action-btn" onclick="alert('Cadastrar UC')">
                <span class="action-icon">📖</span>
                <span>Nova Unidade Curricular</span>
            </button>
            <button class="action-btn" onclick="alert('Cadastrar Turma')">
                <span class="action-icon">🎯</span>
                <span>Nova Turma</span>
            </button>
            <button class="action-btn" onclick="alert('Agendar Aula')">
                <span class="action-icon">📅</span>
                <span>Agendar Aula</span>
            </button>
        </div>
    </div>
</div>

<script src="assets/js/menu.js"></script>
</body>
</html>
