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


$sql_aulas = "SELECT COUNT(*) AS total FROM aulas";
$result_aulas = $connection->query($sql_aulas);
$totalAulas = 0;

if ($result_aulas) {
    $row = $result_aulas->fetch_assoc();
    $totalAulas = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}


$sql_Unidades = "SELECT COUNT(*) AS total FROM uc";
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

$sql_aulas_hoje = "SELECT COUNT(*) AS total FROM aulas WHERE DATE(data_aula) = CURDATE()";
$result_hoje = $connection->query($sql_aulas_hoje);
$aulasHoje = 0;

if ($result_hoje) {
    $row = $result_hoje->fetch_assoc();
    $aulasHoje = $row['total'];
}


$sql_aulas_mes = "SELECT COUNT(*) AS total FROM aulas WHERE MONTH(data_aula) = MONTH(CURDATE()) AND YEAR(data_aula) = YEAR(CURDATE())";
$result_mes = $connection->query($sql_aulas_mes);
$aulasMes = 0;

if ($result_mes) {
    $row = $result_mes->fetch_assoc();
    $aulasMes = $row['total'];
}

$sql_usuarios = "SELECT COUNT(*) AS total FROM usuarios";
$result_usuarios = $connection->query($sql_usuarios);
$totalUsuarios = 0;

if ($result_usuarios) {
    $row = $result_usuarios->fetch_assoc();
    $totalUsuarios = $row['total'];
} else {
    echo "Erro na consulta: " . $connection->error;
}

$sql_aulas_futuras = "
    SELECT COUNT(*) AS total
    FROM aulas
    WHERE (data_aula > CURDATE())
       OR (data_aula = CURDATE() AND horario_fim > CURTIME())
";
$result_futuras = $connection->query($sql_aulas_futuras);
$aulasFuturas = 0;

if ($result_futuras) {
    $row = $result_futuras->fetch_assoc();
    $aulasFuturas = $row['total'];
}


if (isset($_SESSION['nome_usuario'])) {
    $nomeUsuario = $_SESSION['nome_usuario'];
} else {
    $nomeUsuario = 'Usuário';
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
    </div>

    <div class="cards-container">

        <div class="card card-professores">
            <div class="card-content">
                <div class="card-icon">👨‍🏫</div>
                <div class="card-title">Professores</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalProfessores; ?></span>
                    <span class="card-stats-label">Professores Cadastrados</span>
                </div>
                <button class="card-button"><a href="../app/Crud_professores.php">Visualizar</a></button>
            </div>
        </div>

        <div class="card card-usuarios">
            <div class="card-content">
                <div class="card-icon">🙋‍♂️</div>
                <div class="card-title">Usuários</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalUsuarios; ?></span>
                    <span class="card-stats-label">Usuários Cadastrados</span>
                </div>
                <button class="card-button"><a href="../app/Crud_Usuarios.php">Visualizar</a></button>
            </div>
        </div>
        

        <div class="card card-unidades">
            <div class="card-content">
                <div class="card-icon">📚</div>
                <div class="card-title">Unidades Curriculares</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalUnidades; ?></span>
                    <span class="card-stats-label">Unidades Disponíveis</span>
                </div>
                <button class="card-button"><a href="../app/Lista_uc.php">Visualizar</a></button>
            </div>
        </div>

        <div class="card card-turmas">
            <div class="card-content">
                <div class="card-icon">👥</div>
                <div class="card-title">Turmas</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalTurmas; ?></span>
                    <span class="card-stats-label">Turmas Cadastradas</span>
                </div>
                <button class="card-button"><a href="../app/Lista_Turmas.php">Visualizar</a></button>
            </div>
        </div>

        <div class="card card-aulas">
            <div class="card-content">
                <div class="card-icon">📅</div>
                <div class="card-title">Aulas Agendadas</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalAulas; ?></span>
                    <span class="card-stats-label">Aulas Cadastradas</span>
                </div>
                <button class="card-button"><a href="../app/Calendario_admin.php">Visualizar</a></button>
            </div>
        </div>
    </div>

    <div class="stats-section">
        <h2>📊 Estatísticas do Sistema</h2>
        <div class="stats-grid">
    
            <div class="stat-item">
                <div class="stat-number"><?php echo $aulasHoje; ?></div>
                <div class="stat-label">Aulas Hoje</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $aulasMes; ?></div>
                <div class="stat-label">Aulas Este Mês</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $aulasFuturas; ?></div>
                <div class="stat-label">Aulas Futuras</div>
            </div>
        </div>
    </div>
    
    <div class="quick-actions">
        <h2>⚡ Ações Rápidas</h2>
        <div class="actions-grid">
            <button class="action-btn">
                <a href="cadastro_professores.php">
                    <span class="action-icon">➕</span>
                    <span>Novo Professor</span>
                </a>
            </button>
            
            <button class="action-btn">
                <a href="cadastro_uc.php">
                    <span class="action-icon">📖</span>
                    <span>Nova Unidade Curricular</span>
                </a>
            </button>
            <button class="action-btn">
                <a href="cadastro_turmas.php">
                    <span class="action-icon">🎯</span>
                    <span>Nova Turma</span>
                </a>
            </button>
            <button class="action-btn">
                <a href="cadastro_agendamento.php">
                    <span class="action-icon">📅</span>
                    <span>Agendar Aula</span>
                </a>
            </button>
        </div>
    </div>
</div>

<script src="assets/js/menu.js"></script>
</body>
</html>