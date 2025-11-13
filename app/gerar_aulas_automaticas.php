<?php
ob_start();
include("conexao.php");
include("protect.php");
protect();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $data_inicio = $_POST['data_inicio'];
    $semanas = 1;

    $aulas_geradas = 0;

$horarios = [
    'manha' => ['07:15-08:05', '08:05-08:55', '08:55-09:45', '10:05-10:55', '10:55-11:45'],
    'tarde' => ['13:30-14:20', '14:20-15:10', '15:10-16:00', '16:20-17:10', '17:10-18:00'],
    'noite' => ['19:00-19:50', '19:50-20:40', '20:40-21:30', '21:40-22:30', '22:30-23:20']
];

    $salas = ['101', '102', '103', '201', '202', 'LAB-01', 'LAB-02'];

    $professores_result = $connection->query("SELECT id, nome, unidade_curricular FROM professores");
    $ucs_result = $connection->query("SELECT id, unidade_curricular FROM uc");
    $turmas_result = $connection->query("SELECT t.id, t.nome, tr.nome as turno FROM turmas t INNER JOIN turnos tr ON t.id_turno = tr.id");

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

    $data_atual = new DateTime($data_inicio);
    $data_fim = clone $data_atual;
    $data_fim->add(new DateInterval('P' . $semanas . 'W'));

    $stmt = $connection->prepare("INSERT INTO aulas (professor_id, uc_id, turma_id, sala, data_aula, horario_inicio, horario_fim, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

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
                        if (isset($turma['turno'])) {
                            $turno = strtolower(trim($turma['turno']));
                            if (in_array($turno, ['manhã', 'manha', 'matutino'])) $turno = 'manha';
                            elseif (in_array($turno, ['tarde'])) $turno = 'tarde';
                            elseif (in_array($turno, ['noite', 'noturno'])) $turno = 'noite';

                            if (isset($horarios[$turno])) {
                                $horario = $horarios[$turno][array_rand($horarios[$turno])];
                                $horario_array = explode('-', $horario);
                                $inicio = $horario_array[0];
                                $fim = $horario_array[1];
                            } else {
                                $inicio = sprintf('%02d:00', 8 + $i * 2);
                                $fim = sprintf('%02d:40', 9 + $i * 2);
                            }
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

    $stmt->close();
    $_SESSION['sucesso_geracao'] = $aulas_geradas;
    header("Location: gerar_aulas_automaticas.php");
    exit;
}

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
            <div class="cadastro-card" style="max-width: 600px;">
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
                <p class="card-subtitle">Gere aulas para as próximas semanas de forma automática</p>

                <?php if(isset($_SESSION['sucesso_geracao'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Foram geradas <?php echo $_SESSION['sucesso_geracao']; ?> aulas automaticamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['sucesso_geracao']); ?>
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
                        <select name="tipo" class="form-select-modern" required>
                            <option value="">Selecione um método</option>
                            <option value="padrao_semanal">Padrão Semanal</option>
                        </select>
                    </div>

                    <div id="opcoes-padrao-semanal" style="display: none;">
                        <div class="form-group-modern">
                            <label>Data de Início</label>
                            <input type="date" name="data_inicio" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                        </div>
                        <small class="text-muted">Gera aulas para uma semana (2-4 aulas por dia útil)</small>
                    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/menu.js"></script>
</body>
</html>