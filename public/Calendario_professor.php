<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$id_usuario = $_SESSION['id_usuario']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
}

date_default_timezone_set('America/Sao_Paulo');

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

$firstDayOfMonth = strtotime("$year-$month-01");
$daysInMonth = date('t', $firstDayOfMonth);
$startDayOfWeek = date('w', $firstDayOfMonth);

$sql = "
    SELECT a.data_aula, a.horario_inicio, a.horario_fim, a.sala,
           p.nome AS professor_nome, u.unidade_curricular, t.nome AS turma_nome
    FROM aulas a
    LEFT JOIN professores p ON a.professor_id = p.id
    LEFT JOIN uc u ON a.uc_id = u.id
    LEFT JOIN turmas t ON a.turma_id = t.id
    WHERE YEAR(a.data_aula) = ? AND MONTH(a.data_aula) = ? AND a.professor_id = ?
    ORDER BY a.data_aula, a.horario_inicio
";

$stmt = $connection->prepare($sql);
$stmt->bind_param('iii', $year, $month, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $day = date('j', strtotime($row['data_aula']));
    $events[$day][] = $row;
}

$stmt->close();
$connection->close();

$monthNames = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;    
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Aulas - Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/Calendario.css">
    <link rel="stylesheet" href="../public/assets/css/Menu.css">
</head>
<?php include("menu_professor.php"); ?>
<body>
    <div class="top-header">
        <div class="logo-section">
            <h2><img src="../public/assets/img/logo-senai-home.png" alt="Logo SENAI" /></h2>
        </div>
        <a href="../app/logout.php" class="btn btn-danger logout-btn">Sair</a>
    </div>

    <div class="content-area">
        <div class="calendar-header">
            <h1 class="calendar-title">📅 Meu Calendário de Aulas</h1>
            <div class="calendar-navigation">
                <a href="?year=<?php echo $prevYear; ?>&month=<?php echo $prevMonth; ?>" class="nav-btn">← Anterior</a>
                <span class="current-month"><?php echo $monthNames[$month] . " " . $year; ?></span>
                <a href="?year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>" class="nav-btn">Próximo →</a>
            </div>
        </div>

        <div class="calendar-container">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>DOM</th>
                        <th>SEG</th>
                        <th>TER</th>
                        <th>QUA</th>
                        <th>QUI</th>
                        <th>SEX</th>
                        <th>SÁB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $today = date('Y-m-d');
                    
                    echo "<tr>";
                    
                    for ($i = 0; $i < $startDayOfWeek; $i++) {
                        echo "<td class='empty-cell'></td>";
                    }

                    $currentDay = 1;
                    $currentWeekDay = $startDayOfWeek;

                    while ($currentDay <= $daysInMonth) {
                        if ($currentWeekDay == 7) {
                            echo "</tr><tr>";
                            $currentWeekDay = 0;
                        }

                        $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $currentDay);
                        $isToday = ($currentDate == $today) ? 'today' : '';

                        echo "<td class='$isToday'>";
                        echo "<span class='day-number'>$currentDay</span>";
                        if (isset($events[$currentDay])) {
                            $count = 0;
                            foreach ($events[$currentDay] as $event) {
                                if ($count < 2) {
                                    echo "<div class='event'>";
                                    echo "<strong>" . htmlspecialchars($event['unidade_curricular']) . "</strong>";
                                    echo " 🏫 " . htmlspecialchars($event['turma_nome']) . "<br>";
                                    echo "📍 " . htmlspecialchars($event['sala']);
                                    echo "<span class='event-time' style='color: white;'>⏰ " . substr($event['horario_inicio'], 0, 5) . " - " . substr($event['horario_fim'], 0, 5) . "</span>";
                                    echo "</div>";
                                    $count++;
                                } else {
                                    echo "<div class='event' style='background: #6c757d;'>+ " . (count($events[$currentDay]) - 2) . " mais</div>";
                                    break;
                                }
                            }
                        }

                        echo "</td>";

                        $currentDay++;
                        $currentWeekDay++;
                    }

                    while ($currentWeekDay < 7) {
                        echo "<td class='empty-cell'></td>";
                        $currentWeekDay++;
                    }

                    echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="assets/js/menu.js"></script>
</body>
</html>
