<?php
session_start();
include("../app/conexao.php");

// if (!isset($_SESSION['id_usuario'])) {
//     header("Location: login.php");
//     exit;
// }

$id_professor = $_SESSION['id_usuario'];
$nomeProfessor = $_SESSION['nome'] ?? 'Professor';

$sql_aulas = "SELECT COUNT(*) AS total FROM aulas WHERE professor_id = ?";
$stmt = $connection->prepare($sql_aulas);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$totalAulas = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;
$stmt->close();

$sql_aulas_hoje = "SELECT COUNT(*) AS total FROM aulas WHERE professor_id = ? AND DATE(data_aula) = CURDATE()";
$stmt = $connection->prepare($sql_aulas_hoje);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$aulasHoje = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;
$stmt->close();

$sql_aulas_mes = "SELECT COUNT(*) AS total FROM aulas 
                  WHERE professor_id = ? 
                  AND MONTH(data_aula) = MONTH(CURDATE()) 
                  AND YEAR(data_aula) = YEAR(CURDATE())";
$stmt = $connection->prepare($sql_aulas_mes);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$aulasMes = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;
$stmt->close();

$sql_aulas_futuras = "SELECT COUNT(*) AS total FROM aulas 
                      WHERE professor_id = ? 
                      AND ((data_aula > CURDATE()) 
                      OR (data_aula = CURDATE() AND horario_fim > CURTIME()))";
$stmt = $connection->prepare($sql_aulas_futuras);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$aulasFuturas = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;
$stmt->close();


$sql_proximas = "SELECT a.*, 
                        t.nome AS nome_turma, 
                        uc.unidade_curricular AS nome_uc
                 FROM aulas a
                 LEFT JOIN turmas t ON a.turma_id = t.id
                 LEFT JOIN uc ON a.uc_id = uc.id
                 WHERE a.professor_id = ? 
                 AND ((a.data_aula > CURDATE()) 
                 OR (a.data_aula = CURDATE() AND a.horario_fim > CURTIME()))
                 ORDER BY a.data_aula ASC, a.horario_inicio ASC
                 LIMIT 5";
$stmt = $connection->prepare($sql_proximas);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result_proximas = $stmt->get_result();
$proximasAulas = [];
while ($row = $result_proximas->fetch_assoc()) {
    $proximasAulas[] = $row;
}
$stmt->close();

// Total de turmas distintas do professor
$sql_turmas = "SELECT COUNT(DISTINCT turma_id) AS total FROM aulas WHERE professor_id = ?";
$stmt = $connection->prepare($sql_turmas);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();
$totalTurmas = ($result && $row = $result->fetch_assoc()) ? $row['total'] : 0;
$stmt->close();

$connection->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistema de Agenda - Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/menu.css" />
    <link rel="stylesheet" href="assets/css/home.css" />
</head>
<body>

<?php include ("menu_profesor.php"); ?>

<div class="top-header">
    <div class="logo-section">
        <h2><img src="assets/img/logo-senai-home.png" alt="Logo SENAI" /></h2>
    </div>
    <a href="../app/logout.php" class="logout-btn btn btn-danger">Sair</a>
</div>

<div class="content-area">
    <div class="welcome-section">
        <h1>Bem-vindo de volta! 👋 <?php echo htmlspecialchars($nomeProfessor); ?></h1>
    </div>

    <div class="cards-container">
        <div class="card card-aulas">
            <div class="card-content">
                <div class="card-icon">📅</div>
                <div class="card-title">Total de Aulas</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalAulas; ?></span>
                    <span class="card-stats-label">Aulas Cadastradas</span>
                </div>
                <button class="card-button"><a href="../app/Calendario_professor.php">Ver Calendário</a></button>
            </div>
        </div>

        <div class="card card-turmas">
            <div class="card-content">
                <div class="card-icon">👥</div>
                <div class="card-title">Minhas Turmas</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $totalTurmas; ?></span>
                    <span class="card-stats-label">Turmas Ativas</span>
                </div>
                <button class="card-button"><a href="../app/Minhas_turmas.php">Visualizar</a></button>
            </div>
        </div>

        <div class="card card-professores">
            <div class="card-content">
                <div class="card-icon">⏰</div>
                <div class="card-title">Próximas Aulas</div><br>
                <div class="card-stats">
                    <span class="card-stats-number"><?php echo $aulasFuturas; ?></span>
                    <span class="card-stats-label">Aulas Agendadas</span>
                </div>
                <button class="card-button"><a href="../app/Calendario_professor.php">Ver Agenda</a></button>
            </div>
        </div>
    </div>

    <div class="stats-section">
        <h2>📊 Estatísticas das Minhas Aulas</h2>
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

    <div class="proximas-aulas">
        <h2>📌 Próximas Aulas Agendadas</h2>
        
        <?php if (count($proximasAulas) > 0): ?>
            <?php foreach ($proximasAulas as $aula): ?>
                <div class="aula-item">
                    <div class="aula-data">
                        📅 <?php echo date('d/m/Y', strtotime($aula['data_aula'])); ?>
                    </div>
                    <div class="aula-horario">
                        🕐 <?php echo date('H:i', strtotime($aula['horario_inicio'])); ?> - 
                        <?php echo date('H:i', strtotime($aula['horario_fim'])); ?>
                    </div>
                    <div class="aula-detalhes">
                        <span class="badge-turma">
                            👥 <?php echo htmlspecialchars($aula['nome_turma'] ?? 'Sem turma'); ?>
                        </span>
                        <span class="badge-uc">
                            📚 <?php echo htmlspecialchars($aula['nome_uc'] ?? 'Sem UC'); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sem-aulas">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p>Nenhuma aula agendada no momento</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="quick-actions">
        <h2>⚡ Ações Rápidas</h2>
        <div class="actions-grid">
            <button class="action-btn">
                <a href="../app/Calendario_professor.php">
                    <span class="action-icon">📅</span>
                    <span>Ver Calendário Completo</span>
                </a>
            </button>
            
            <button class="action-btn">
                <a href="../app/Minhas_aulas.php">
                    <span class="action-icon">📝</span>
                    <span>Minhas Aulas</span>
                </a>
            </button>
            
            <button class="action-btn">
                <a href="../app/Minhas_turmas.php">
                    <span class="action-icon">👥</span>
                    <span>Minhas Turmas</span>
                </a>
            </button>
        </div>
    </div>
</div>

<script src="assets/js/menu.js"></script>
</body>
</html>