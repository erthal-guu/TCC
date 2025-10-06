<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$sql_professores = "SELECT COUNT(*) AS total FROM professores";
$result = $connection->query($sql_professores);
$totalProfessores = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $totalProfessores = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}

$sql_Unidades = "SELECT COUNT(*) AS total FROM unidades_curriculares";
$result = $connection->query($sql_Unidades);
$totalUnidades = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $totalUnidades = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}

$sql_turmas = "SELECT COUNT(*) AS total FROM turmas";
$result = $connection->query($sql_turmas);
$totalTurmas = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $totalTurmas = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}
if (isset($_SESSION['nome_usuario'])) {
    $nomeUsuario = $_SESSION['nome_usuario'];
} 

$connection->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistema de Agenda - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/menu.css" />
    <link rel="stylesheet" href="assets/css/home.css" />
</head>
<body>

<?php include 'menu.php'; ?>

<div class="top-header">
    <div class="logo-section">
        <h2><img src="assets/img/logo-senai-home.png" alt="Logo SENAI" /></h2>
    </div>
    <a href="../app/logout.php" class="logout-btn btn btn-danger">Sair</a>
</div>

<div class="content-area">
    <div class="welcome-section">
        <h1>Bem-vindo de volta! 👋 <?php echo htmlspecialchars($nomeUsuario); ?></h1>
        <p>Aqui está um resumo do seu sistema acadêmico</p>
    </div>

    <div class="cards-container">

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

        <div class="card card-unidades">
            <div class="card-content">
                <div class="card-icon">📚</div>
                <div class="card-title">Unidades Curriculares</div>
                <div class="card-description">Consulte todas as disciplinas/unidades curriculares disponíveis.</div>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalUnidades; ?></span>
                    <span class="card-stats-label">Unidades Disponíveis</span>
                </div>
                <button class="card-button" onclick="alert('Visualizar UCs')">Visualizar</button>
            </div>
        </div>

        <div class="card card-turmas">
            <div class="card-content">
                <div class="card-icon">👥</div>
                <div class="card-title">Turmas</div>
                <div class="card-description">Acompanhe as turmas cadastradas e seus respectivos turnos.</div>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalTurmas; ?></span>
                    <span class="card-stats-label">Turmas cadastradas</span>
                </div>
                <button class="card-button" onclick="alert('Visualizar Turmas')">Visualizar</button>
            </div>
        </div>
    </div>
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