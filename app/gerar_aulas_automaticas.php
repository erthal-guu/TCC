<?php
// Desabilitar display de erros
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include("conexao.php");
include("protect.php");
protect();

ob_start();

function executarQuery($connection, $sql) {
    $result = $connection->query($sql);
    if (!$result) {
        error_log("Erro na query: " . $connection->error);
        return false;
    }
    return $result;
}

function prepararStatement($connection, $sql) {
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        error_log("Erro ao preparar statement: " . $connection->error);
        return false;
    }
    return $stmt;
}

function redirecionarComErro($mensagem) {
    $_SESSION['erro_geracao'] = $mensagem;
    header("Location: gerar_aulas_automaticas.php");
    exit;
}

function redirecionarComSucesso($aulas, $tipo) {
    $_SESSION['sucesso_geracao'] = $aulas;
    $_SESSION['tipo_geracao'] = $tipo;
    header("Location: gerar_aulas_automaticas.php");
    exit;
}

function gerarAulasAutomaticas($connection, $tipo, $params = []) {
    $aulas_geradas = 0;

    switch($tipo) {
        case 'padrao_semanal':
            $aulas_geradas = gerarPadraoSemanal($connection, $params);
            break;
        case 'completo_turno':
            $aulas_geradas = gerarCompletoTurno($connection, $params);
            break;
        case 'professor_fixo':
            $aulas_geradas = gerarProfessorFixo($connection, $params);
            break;
        case 'aleatorio_balanceado':
            $aulas_geradas = gerarAleatorioBalanceado($connection, $params);
            break;
    }

    return $aulas_geradas;
}

function gerarPadraoSemanal($connection, $params) {
    $aulas_geradas = 0;
    $semanas = isset($params['semanas']) ? (int)$params['semanas'] : 4;
    $data_inicio = isset($params['data_inicio']) ? $params['data_inicio'] : date('Y-m-d');

    $horarios = [
        'manha' => ['08:00-09:40', '10:00-11:40'],
        'tarde' => ['13:30-15:10', '15:30-17:10'],
        'noite' => ['19:00-20:40', '20:50-22:30']
    ];

    $salas = ['101', '102', '103', '201', '202', 'LAB-01', 'LAB-02'];

    $professores_result = executarQuery($connection, "SELECT id, nome, unidade_curricular FROM professores");
    $ucs_result = executarQuery($connection, "SELECT id, unidade_curricular FROM uc");
    $turmas_result = executarQuery($connection, "SELECT t.id, t.nome, tr.nome as turno FROM turmas t INNER JOIN turnos tr ON t.id_turno = tr.id");

    if (!$professores_result || !$ucs_result || !$turmas_result) {
        return 0;
    }

    $professores = [];
    while($row = $professores_result->fetch_assoc()) {
        $professores[] = $row;
    }

    $ucs = [];
    while($row = $ucs_result->fetch_assoc()) {
        $ucs[] = $row;
    }

    $turmas = [];
    while($row = $turmas_result->fetch_assoc()) {
        $turmas[] = $row;
    }

    if (empty($professores) || empty($ucs) || empty($turmas)) {
        return 0;
    }

    $data_atual = new DateTime($data_inicio);
    $data_fim = clone $data_atual;
    $data_fim->add(new DateInterval('P' . $semanas . 'W'));

    $stmt = prepararStatement($connection, "INSERT INTO aulas (professor_id, uc_id, turma_id, sala, data_aula, horario_inicio, horario_fim, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return 0;

    while($data_atual <= $data_fim) {
        $dia_semana = $data_atual->format('N');

        if($dia_semana <= 5) {
            $aulas_dia = rand(2, 4);

            for($i = 0; $i < $aulas_dia && $i < 4; $i++) {
                if (!empty($professores) && !empty($ucs) && !empty($turmas)) {
                    $professor = $professores[array_rand($professores)];
                    $uc = $ucs[array_rand($ucs)];
                    $turma = $turmas[array_rand($turmas)];
                    $sala = $salas[array_rand($salas)];

                    if(isset($turma['turno'])) {
                        $turno = strtolower($turma['turno']);
                        if(isset($horarios[$turno])) {
                            $horario = $horarios[$turno][array_rand($horarios[$turno])];
                            list($inicio, $fim) = explode('-', $horario);
                        } else {
                            $inicio = sprintf('%02d:00', 8 + $i * 2);
                            $fim = sprintf('%02d:40', 8 + $i * 2);
                        }
                    } else {
                        $inicio = sprintf('%02d:00', 8 + $i * 2);
                        $fim = sprintf('%02d:40', 8 + $i * 2);
                    }

                    $observacoes = "Aula gerada automaticamente - Padrão semanal";

                    $stmt->bind_param("iiisssss",
                        $professor['id'],
                        $uc['id'],
                        $turma['id'],
                        $sala,
                        $data_atual->format('Y-m-d'),
                        $inicio,
                        $fim,
                        $observacoes
                    );

                    if($stmt->execute()) {
                        $aulas_geradas++;
                    }
                }
            }
        }

        $data_atual->add(new DateInterval('P1D'));
    }

    return $aulas_geradas;
}

function gerarCompletoTurno($connection, $params) {
    $aulas_geradas = 0;
    $turno_id = isset($params['turno_id']) ? (int)$params['turno_id'] : 0;
    $semanas = isset($params['semanas']) ? (int)$params['semanas'] : 2;

    if($turno_id <= 0) return 0;

    $stmt_turmas = $connection->prepare("SELECT t.id, t.nome FROM turmas t WHERE t.id_turno = ?");
    if (!$stmt_turmas) return 0;

    $stmt_turmas->bind_param("i", $turno_id);
    $stmt_turmas->execute();
    $turmas_result = $stmt_turmas->get_result();

    $turmas = [];
    while($row = $turmas_result->fetch_assoc()) {
        $turmas[] = $row;
    }

    if(empty($turmas)) return 0;

    $professores_result = executarQuery($connection, "SELECT id FROM professores");
    $ucs_result = executarQuery($connection, "SELECT id FROM uc");

    if (!$professores_result || !$ucs_result) return 0;

    $professores = [];
    while($row = $professores_result->fetch_assoc()) {
        $professores[] = $row['id'];
    }

    $ucs = [];
    while($row = $ucs_result->fetch_assoc()) {
        $ucs[] = $row['id'];
    }

    if (empty($professores) || empty($ucs)) return 0;

    $horarios_turno = [
        4 => ['08:00-09:40', '10:00-11:40'],
        5 => ['13:30-15:10', '15:30-17:10'],
        6 => ['19:00-20:40', '20:50-22:30']
    ];

    $horarios = isset($horarios_turno[$turno_id]) ? $horarios_turno[$turno_id] : ['08:00-09:40'];
    $salas = ['101', '102', '201', 'LAB-01'];

    $data_atual = new DateTime();
    $data_fim = clone $data_atual;
    $data_fim->add(new DateInterval('P' . $semanas . 'W'));

    $stmt = prepararStatement($connection, "INSERT INTO aulas (professor_id, uc_id, turma_id, sala, data_aula, horario_inicio, horario_fim, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return 0;

    while($data_atual <= $data_fim) {
        $dia_semana = $data_atual->format('N');

        if($dia_semana <= 5) {
            foreach($turmas as $turma) {
                foreach($horarios as $horario) {
                    list($inicio, $fim) = explode('-', $horario);

                    if (!empty($professores) && !empty($ucs)) {
                        $stmt->bind_param("iiisssss",
                            $professores[array_rand($professores)],
                            $ucs[array_rand($ucs)],
                            $turma['id'],
                            $salas[array_rand($salas)],
                            $data_atual->format('Y-m-d'),
                            $inicio,
                            $fim,
                            "Aula gerada automaticamente - Turno completo"
                        );

                        if($stmt->execute()) {
                            $aulas_geradas++;
                        }
                    }
                }
            }
        }

        $data_atual->add(new DateInterval('P1D'));
    }

    return $aulas_geradas;
}

function gerarProfessorFixo($connection, $params) {
    $aulas_geradas = 0;
    $professor_id = isset($params['professor_id']) ? (int)$params['professor_id'] : 0;
    $uc_id = isset($params['uc_id']) ? (int)$params['uc_id'] : 0;
    $quantidade = isset($params['quantidade']) ? (int)$params['quantidade'] : 10;

    if($professor_id <= 0 || $uc_id <= 0) return 0;

    $turmas_result = executarQuery($connection, "SELECT id FROM turmas");
    if (!$turmas_result) return 0;

    $turmas = [];
    while($row = $turmas_result->fetch_assoc()) {
        $turmas[] = $row['id'];
    }

    if(empty($turmas)) return 0;

    $horarios = ['08:00-09:40', '10:00-11:40', '13:30-15:10', '15:30-17:10', '19:00-20:40', '20:50-22:30'];
    $salas = ['101', '102', '103', 'LAB-01', 'LAB-02'];

    $stmt = prepararStatement($connection, "INSERT INTO aulas (professor_id, uc_id, turma_id, sala, data_aula, horario_inicio, horario_fim, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return 0;

    $data_atual = new DateTime();
    $dias_gerados = 0;

    while($aulas_geradas < $quantidade && $dias_gerados < 30) {
        $dia_semana = $data_atual->format('N');

        if($dia_semana <= 5) {
            $horario = $horarios[array_rand($horarios)];
            list($inicio, $fim) = explode('-', $horario);

            if (!empty($turmas)) {
                $stmt->bind_param("iiisssss",
                    $professor_id,
                    $uc_id,
                    $turmas[array_rand($turmas)],
                    $salas[array_rand($salas)],
                    $data_atual->format('Y-m-d'),
                    $inicio,
                    $fim,
                    "Aula gerada automaticamente - Professor fixo"
                );

                if($stmt->execute()) {
                    $aulas_geradas++;
                }
            }
        }

        $data_atual->add(new DateInterval('P1D'));
        $dias_gerados++;
    }

    return $aulas_geradas;
}

function gerarAleatorioBalanceado($connection, $params) {
    $aulas_geradas = 0;
    $quantidade = isset($params['quantidade']) ? (int)$params['quantidade'] : 20;

    $professores_result = executarQuery($connection, "SELECT id FROM professores");
    $ucs_result = executarQuery($connection, "SELECT id FROM uc");
    $turmas_result = executarQuery($connection, "SELECT id FROM turmas");

    if (!$professores_result || !$ucs_result || !$turmas_result) return 0;

    $professores = [];
    while($row = $professores_result->fetch_assoc()) {
        $professores[] = $row['id'];
    }

    $ucs = [];
    while($row = $ucs_result->fetch_assoc()) {
        $ucs[] = $row['id'];
    }

    $turmas = [];
    while($row = $turmas_result->fetch_assoc()) {
        $turmas[] = $row['id'];
    }

    if(empty($professores) || empty($ucs) || empty($turmas)) return 0;

    $horarios = ['08:00-09:40', '10:00-11:40', '13:30-15:10', '15:30-17:10', '19:00-20:40', '20:50-22:30'];
    $salas = ['101', '102', '103', '201', '202', 'LAB-01', 'LAB-02', 'OFICINA-01'];

    $stmt = prepararStatement($connection, "INSERT INTO aulas (professor_id, uc_id, turma_id, sala, data_aula, horario_inicio, horario_fim, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return 0;

    for($i = 0; $i < $quantidade; $i++) {
        $dias_aleatorio = rand(0, 30);
        $data_aula = new DateTime();
        $data_aula->add(new DateInterval('P' . $dias_aleatorio . 'D'));

        $dia_semana = $data_aula->format('N');
        if($dia_semana > 5 && rand(1, 10) > 3) continue;

        $horario = $horarios[array_rand($horarios)];
        list($inicio, $fim) = explode('-', $horario);

        if (!empty($professores) && !empty($ucs) && !empty($turmas)) {
            $stmt->bind_param("iiisssss",
                $professores[array_rand($professores)],
                $ucs[array_rand($ucs)],
                $turmas[array_rand($turmas)],
                $salas[array_rand($salas)],
                $data_aula->format('Y-m-d'),
                $inicio,
                $fim,
                "Aula gerada automaticamente - Aleatório balanceado"
            );

            if($stmt->execute()) {
                $aulas_geradas++;
            }
        }
    }

    return $aulas_geradas;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $params = $_POST;

    if (empty($tipo)) {
        redirecionarComErro("Selecione um método de geração de aulas.");
    }

    switch($tipo) {
        case 'padrao_semanal':
            $semanas = isset($params['semanas']) ? (int)$params['semanas'] : 4;
            if ($semanas < 1 || $semanas > 12) {
                redirecionarComErro("A quantidade de semanas deve ser entre 1 e 12.");
            }
            break;
        case 'completo_turno':
            $turno_id = isset($params['turno_id']) ? (int)$params['turno_id'] : 0;
            if ($turno_id <= 0) {
                redirecionarComErro("Selecione um turno válido.");
            }
            break;
        case 'professor_fixo':
            $professor_id = isset($params['professor_id']) ? (int)$params['professor_id'] : 0;
            $uc_id = isset($params['uc_id']) ? (int)$params['uc_id'] : 0;
            $quantidade = isset($params['quantidade']) ? (int)$params['quantidade'] : 10;

            if ($professor_id <= 0) {
                redirecionarComErro("Selecione um professor válido.");
            }
            if ($uc_id <= 0) {
                redirecionarComErro("Selecione uma unidade curricular válida.");
            }
            if ($quantidade < 1 || $quantidade > 100) {
                redirecionarComErro("A quantidade de aulas deve ser entre 1 e 100.");
            }
            break;
        case 'aleatorio_balanceado':
            $quantidade = isset($params['quantidade']) ? (int)$params['quantidade'] : 20;
            if ($quantidade < 1 || $quantidade > 200) {
                redirecionarComErro("A quantidade de aulas deve ser entre 1 e 200.");
            }
            break;
    }

    try {
        $aulas_geradas = gerarAulasAutomaticas($connection, $tipo, $params);
        redirecionarComSucesso($aulas_geradas, $tipo);
    } catch (Exception $e) {
        error_log("Erro ao gerar aulas: " . $e->getMessage());
        redirecionarComErro("Ocorreu um erro ao gerar as aulas. Tente novamente.");
    }
}

$professores = executarQuery($connection, "SELECT id, nome FROM professores ORDER BY nome");
$ucs = executarQuery($connection, "SELECT id, unidade_curricular FROM uc ORDER BY unidade_curricular");
$turnos = executarQuery($connection, "SELECT id, nome FROM turnos ORDER BY id");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Aulas Automaticamente</title>
    <link rel="stylesheet" href="../public/assets/css/cadastro.css">
    <link rel="stylesheet" href="../public/assets/css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("../public/menu.php"); ?>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card" style="max-width: 700px;">
                <div class="card-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>

                <h2 class="card-title">Gerar Aulas Automaticamente</h2>
                <p class="card-subtitle">Escolha um método para gerar aulas baseadas em padrões do sistema</p>

                <?php if(isset($_SESSION['sucesso_geracao'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Foram geradas <?php echo $_SESSION['sucesso_geracao']; ?> aulas automaticamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['sucesso_geracao'], $_SESSION['tipo_geracao']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['erro_geracao'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Erro!</strong> <?php echo $_SESSION['erro_geracao']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['erro_geracao']); ?>
                <?php endif; ?>

                <form method="POST" class="form-cadastro">
                    <div class="form-group-modern">
                        <label>Método de Geração</label>
                        <select name="tipo" class="form-select-modern" onchange="mostrarOpcoes()" required>
                            <option value="">Selecione um método</option>
                            <option value="padrao_semanal">Padrão Semanal (Recomendado)</option>
                            <option value="completo_turno">Completo por Turno</option>
                            <option value="professor_fixo">Professor e UC Fixos</option>
                            <option value="aleatorio_balanceado">Aleatório Balanceado</option>
                        </select>
                    </div>

                    <div id="opcoes-padrao-semanal" style="display: none;">
                        <div class="form-group-modern">
                            <label>Quantidade de Semanas</label>
                            <select name="semanas" class="form-select-modern">
                                <option value="2">2 semanas</option>
                                <option value="4" selected>4 semanas</option>
                                <option value="6">6 semanas</option>
                                <option value="8">8 semanas</option>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label>Data de Início</label>
                            <input type="date" name="data_inicio" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                        </div>
                        <small class="text-muted">Gera 2-4 aulas por dia útil usando horários padrão</small>
                    </div>

                    <div id="opcoes-completo-turno" style="display: none;">
                        <div class="form-group-modern">
                            <label>Turno</label>
                            <select name="turno_id" class="form-select-modern">
                                <option value="">Selecione um turno</option>
                                <?php
                                $turnos_result = executarQuery($connection, "SELECT id, nome FROM turnos ORDER BY id");
                                if ($turnos_result):
                                    while($turno = $turnos_result->fetch_assoc()): ?>
                                        <option value="<?php echo $turno['id']; ?>"><?php echo htmlspecialchars($turno['nome']); ?></option>
                                    <?php
                                    endwhile;
                                endif; ?>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label>Quantidade de Semanas</label>
                            <select name="semanas" class="form-select-modern">
                                <option value="1">1 semana</option>
                                <option value="2" selected>2 semanas</option>
                                <option value="3">3 semanas</option>
                                <option value="4">4 semanas</option>
                            </select>
                        </div>
                        <small class="text-muted">Gera aula para cada turma do turno em cada dia útil</small>
                    </div>

                    <div id="opcoes-professor-fixo" style="display: none;">
                        <div class="form-group-modern">
                            <label>Professor</label>
                            <select name="professor_id" class="form-select-modern">
                                <option value="">Selecione um professor</option>
                                <?php
                                $professores_result = executarQuery($connection, "SELECT id, nome FROM professores ORDER BY nome");
                                if ($professores_result):
                                    while($prof = $professores_result->fetch_assoc()): ?>
                                        <option value="<?php echo $prof['id']; ?>"><?php echo htmlspecialchars($prof['nome']); ?></option>
                                    <?php
                                    endwhile;
                                endif; ?>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label>Unidade Curricular</label>
                            <select name="uc_id" class="form-select-modern">
                                <option value="">Selecione uma UC</option>
                                <?php
                                $ucs_result = executarQuery($connection, "SELECT id, unidade_curricular FROM uc ORDER BY unidade_curricular");
                                if ($ucs_result):
                                    while($uc = $ucs_result->fetch_assoc()): ?>
                                        <option value="<?php echo $uc['id']; ?>"><?php echo htmlspecialchars($uc['unidade_curricular']); ?></option>
                                    <?php
                                    endwhile;
                                endif; ?>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label>Quantidade de Aulas</label>
                            <input type="number" name="quantidade" value="10" min="1" max="50" class="form-control">
                        </div>
                        <small class="text-muted">Gera aulas para o professor e UC específicos</small>
                    </div>

                    <div id="opcoes-aleatorio-balanceado" style="display: none;">
                        <div class="form-group-modern">
                            <label>Quantidade de Aulas</label>
                            <input type="number" name="quantidade" value="20" min="1" max="100" class="form-control">
                        </div>
                        <small class="text-muted">Gera aulas aleatórias distribuídas nos próximos 30 dias</small>
                    </div>

                    <?php
                    // Verificar se há dados necessários
                    $check_professores = $connection->query("SELECT COUNT(*) as total FROM professores")->fetch_assoc()['total'];
                    $check_ucs = $connection->query("SELECT COUNT(*) as total FROM uc")->fetch_assoc()['total'];
                    $check_turmas = $connection->query("SELECT COUNT(*) as total FROM turmas")->fetch_assoc()['total'];

                    if ($check_professores == 0 || $check_ucs == 0 || $check_turmas == 0):
                    ?>
                        <div class="alert alert-warning">
                            <strong>Atenção:</strong> Para gerar aulas, é necessário ter:
                            <ul style="margin-top: 10px; margin-bottom: 0;">
                                <?php if ($check_professores == 0): ?><li>✗ Professores cadastrados</li><?php endif; ?>
                                <?php if ($check_ucs == 0): ?><li>✗ Unidades curriculares cadastradas</li><?php endif; ?>
                                <?php if ($check_turmas == 0): ?><li>✗ Turmas cadastradas</li><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-cadastrar" onclick="return confirm('Tem certeza que deseja gerar aulas automaticamente?');">
                        🚀 Gerar Aulas
                    </button>

                    <a href="Calendario_admin.php" style="display: block; text-align: center; margin-top: 15px; color: #003D7A; text-decoration: none; font-weight: 600;">
                        ← Voltar para Calendário
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarOpcoes() {
            const tipo = document.querySelector('[name="tipo"]').value;

            document.getElementById('opcoes-padrao-semanal').style.display = 'none';
            document.getElementById('opcoes-completo-turno').style.display = 'none';
            document.getElementById('opcoes-professor-fixo').style.display = 'none';
            document.getElementById('opcoes-aleatorio-balanceado').style.display = 'none';

            if(tipo) {
                document.getElementById('opcoes-' + tipo).style.display = 'block';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/menu.js"></script>
</body>
</html>

<?php
// Finalizar o output buffer
ob_end_flush();
?>