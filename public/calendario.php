<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();


$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');


if ($mes < 1) { $mes = 12; $ano--; }
if ($mes > 12) { $mes = 1; $ano++; }


$sql_agendamentos = "
    SELECT 
        a.id,
        a.data_aula,
        a.horario_inicio,
        a.horario_fim,
        a.sala,
        a.status,
        p.nome as professor_nome,
        u.nome as uc_nome,
        t.nome as turma_nome,
        t.turno
    FROM agendamentos a
    INNER JOIN professores p ON a.professor_id = p.id
    INNER JOIN uc u ON a.uc_id = u.id
    INNER JOIN turmas t ON a.turma_id = t.id
    WHERE MONTH(a.data_aula) = ? AND YEAR(a.data_aula) = ?
    ORDER BY a.data_aula, a.horario_inicio
";

$stmt = $connection->prepare($sql_agendamentos);
$stmt->bind_param("ii", $mes, $ano);
$stmt->execute();
$result_agendamentos = $stmt->get_result();


$agendamentos_por_data = [];
while ($row = $result_agendamentos->fetch_assoc()) {
    $data = $row['data_aula'];
    if (!isset($agendamentos_por_data[$data])) {
        $agendamentos_por_data[$data] = [];
    }
    $agendamentos_por_data[$data][] = $row;
}


$primeiro_dia = mktime(0, 0, 0, $mes, 1, $ano);
$dias_no_mes = date('t', $primeiro_dia);
$dia_semana_inicio = date('w', $primeiro_dia);
$nome_mes = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

$mes_anterior = $mes - 1;
$ano_anterior = $ano;
if ($mes_anterior < 1) { $mes_anterior = 12; $ano_anterior--; }

$mes_proximo = $mes + 1;
$ano_proximo = $ano;
if ($mes_proximo > 12) { $mes_proximo = 1; $ano_proximo++; }

$connection->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Aulas - Sistema de Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="assets/css/calendario.css">
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
    <div class="calendario-container">
        <div class="calendario-header">
            <h1>📅 Calendário de Aulas</h1>
            <button class="btn-nova-aula" onclick="window.location.href='cadastro_agendamento.php'">
                ➕ Nova Aula
            </button>
        </div>

        <div class="calendario-navegacao">
            <a href="?mes=<?php echo $mes_anterior; ?>&ano=<?php echo $ano_anterior; ?>" class="btn-nav">
                ← Anterior
            </a>
            <h2 class="mes-ano"><?php echo $nome_mes[$mes] . ' ' . $ano; ?></h2>
            <a href="?mes=<?php echo $mes_proximo; ?>&ano=<?php echo $ano_proximo; ?>" class="btn-nav">
                Próximo →
            </a>
        </div>

        <div class="calendario-legenda">
            <div class="legenda-item">
                <span class="legenda-cor" style="background: #e3f2fd;"></span>
                <span>Agendada</span>
            </div>
            <div class="legenda-item">
                <span class="legenda-cor" style="background: #e8f5e9;"></span>
                <span>Realizada</span>
            </div>
            <div class="legenda-item">
                <span class="legenda-cor" style="background: #ffebee;"></span>
                <span>Cancelada</span>
            </div>
        </div>

        <div class="calendario-grid">
            <div class="dia-semana">Domingo</div>
            <div class="dia-semana">Segunda</div>
            <div class="dia-semana">Terça</div>
            <div class="dia-semana">Quarta</div>
            <div class="dia-semana">Quinta</div>
            <div class="dia-semana">Sexta</div>
            <div class="dia-semana">Sábado</div>

            <?php
            for ($i = 0; $i < $dia_semana_inicio; $i++) {
                echo '<div class="dia-celula vazia"></div>';
            }

            // Dias do mês
            for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
                $data_atual = sprintf("%04d-%02d-%02d", $ano, $mes, $dia);
                $hoje = date('Y-m-d');
                $classe_hoje = ($data_atual == $hoje) ? 'hoje' : '';
                
                echo '<div class="dia-celula ' . $classe_hoje . '">';
                echo '<div class="dia-numero">' . $dia . '</div>';
                
                if (isset($agendamentos_por_data[$data_atual])) {
                    echo '<div class="aulas-dia">';
                    foreach ($agendamentos_por_data[$data_atual] as $aula) {
                        $classe_status = '';
                        switch($aula['status']) {
                            case 'agendada': $classe_status = 'aula-agendada'; break;
                            case 'realizada': $classe_status = 'aula-realizada'; break;
                            case 'cancelada': $classe_status = 'aula-cancelada'; break;
                        }
                        
                        echo '<div class="aula-item ' . $classe_status . '" onclick="mostrarDetalhes(' . $aula['id'] . ')">';
                        echo '<div class="aula-horario">' . substr($aula['horario_inicio'], 0, 5) . '</div>';
                        echo '<div class="aula-professor">' . htmlspecialchars($aula['professor_nome']) . '</div>';
                        echo '<div class="aula-uc">' . htmlspecialchars($aula['uc_nome']) . '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <div class="aulas-lista">
        <h3>📋 Todas as Aulas do Mês</h3>
        <div class="lista-completa">
            <?php
            if (empty($agendamentos_por_data)) {
                echo '<p class="sem-aulas">Nenhuma aula agendada para este mês.</p>';
            } else {
                foreach ($agendamentos_por_data as $data => $aulas) {
                    $data_formatada = date('d/m/Y', strtotime($data));
                    $dia_semana_nome = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][date('w', strtotime($data))];
                    
                    echo '<div class="data-grupo">';
                    echo '<div class="data-titulo">' . $dia_semana_nome . ', ' . $data_formatada . '</div>';
                    
                    foreach ($aulas as $aula) {
                        $classe_status = '';
                        $icone_status = '';
                        switch($aula['status']) {
                            case 'agendada': 
                                $classe_status = 'aula-agendada'; 
                                $icone_status = '📅';
                                break;
                            case 'realizada': 
                                $classe_status = 'aula-realizada'; 
                                $icone_status = '✅';
                                break;
                            case 'cancelada': 
                                $classe_status = 'aula-cancelada'; 
                                $icone_status = '❌';
                                break;
                        }
                        
                        echo '<div class="aula-card ' . $classe_status . '">';
                        echo '<div class="aula-card-header">';
                        echo '<span class="aula-status-icone">' . $icone_status . '</span>';
                        echo '<span class="aula-horario-grande">' . substr($aula['horario_inicio'], 0, 5) . ' - ' . substr($aula['horario_fim'], 0, 5) . '</span>';
                        echo '</div>';
                        echo '<div class="aula-card-body">';
                        echo '<p><strong>👨‍🏫 Professor:</strong> ' . htmlspecialchars($aula['professor_nome']) . '</p>';
                        echo '<p><strong>📖 UC:</strong> ' . htmlspecialchars($aula['uc_nome']) . '</p>';
                        echo '<p><strong>👥 Turma:</strong> ' . htmlspecialchars($aula['turma_nome']) . ' - ' . htmlspecialchars($aula['turno']) . '</p>';
                        echo '<p><strong>🚪 Sala:</strong> ' . htmlspecialchars($aula['sala']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="assets/js/menu.js"></script>
</body>
</html>