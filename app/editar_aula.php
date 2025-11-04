<?php
include("conexao.php");
include("protect.php");
protect();

if (!isset($_GET['id'])) {
    header("Location: ../app/Calendario_admin.php");
    exit;
}

$aula_id = $_GET['id'];

$sql = "SELECT a.*, p.nome AS professor_nome, u.unidade_curricular, t.nome AS turma_nome
        FROM aulas a
        LEFT JOIN professores p ON a.professor_id = p.id
        LEFT JOIN uc u ON a.uc_id = u.id
        LEFT JOIN turmas t ON a.turma_id = t.id
        WHERE a.id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../app/Calendario_admin.php");
    exit;
}

$aula = $result->fetch_assoc();

$professores = $connection->query("SELECT id, nome FROM professores ORDER BY nome");
$ucs = $connection->query("SELECT id, unidade_curricular FROM uc ORDER BY unidade_curricular");
$turmas = $connection->query("SELECT id, nome FROM turmas ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $professor_id = $_POST['professor_id'];
    $uc_id = $_POST['uc_id'];
    $turma_id = $_POST['turma_id'];
    $sala = $_POST['sala'];
    $data_aula = $_POST['data_aula'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];
    $observacoes = $_POST['observacoes'];

    $update_sql = "UPDATE aulas SET
                    professor_id = ?, uc_id = ?, turma_id = ?, sala = ?,
                    data_aula = ?, horario_inicio = ?, horario_fim = ?, observacoes = ?
                    WHERE id = ?";

    $stmt = $connection->prepare($update_sql);
    $stmt->bind_param("iiisssssi", $professor_id, $uc_id, $turma_id, $sala, $data_aula, $horario_inicio, $horario_fim, $observacoes, $aula_id);

    if ($stmt->execute()) {
        header("Location: Calendario_admin.php?success=2");
        exit;
    } else {
        echo "Erro ao atualizar aula: " . $stmt->error;
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aula</title>
    <link rel="stylesheet" href="../public/assets/css/cadastro.css" />
    <link rel="stylesheet" href="../public/assets/css/menu.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <?php include("../public/menu.php"); ?>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>

                <h2 class="card-title">Editar Aula</h2>

                <form method="POST" class="form-cadastro">
                    <div class="form-group-modern">
                        <label for="professor_id">Professor:</label>
                        <select id="professor_id" name="professor_id" required>
                            <option value="">Selecione um professor</option>
                            <?php
                            $professores->data_seek(0);
                            while ($prof = $professores->fetch_assoc()): ?>
                                <option value="<?php echo $prof['id']; ?>" <?php echo ($aula['professor_id'] == $prof['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prof['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label for="uc_id">Unidade Curricular:</label>
                        <select id="uc_id" name="uc_id" required>
                            <option value="">Selecione uma UC</option>
                            <?php
                            $ucs->data_seek(0);
                            while ($uc = $ucs->fetch_assoc()): ?>
                                <option value="<?php echo $uc['id']; ?>" <?php echo ($aula['uc_id'] == $uc['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($uc['unidade_curricular']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label for="turma_id">Turma:</label>
                        <select id="turma_id" name="turma_id" required>
                            <option value="">Selecione uma turma</option>
                            <?php
                            $turmas->data_seek(0);
                            while ($turma = $turmas->fetch_assoc()): ?>
                                <option value="<?php echo $turma['id']; ?>" <?php echo ($aula['turma_id'] == $turma['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($turma['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label for="sala">Sala:</label>
                        <input type="text" id="sala" name="sala" value="<?php echo htmlspecialchars($aula['sala']); ?>" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="data_aula">Data da Aula:</label>
                        <input type="date" id="data_aula" name="data_aula" value="<?php echo $aula['data_aula']; ?>" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="horario_inicio">Horário Início:</label>
                        <input type="time" id="horario_inicio" name="horario_inicio" value="<?php echo $aula['horario_inicio']; ?>" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="horario_fim">Horário Fim:</label>
                        <input type="time" id="horario_fim" name="horario_fim" value="<?php echo $aula['horario_fim']; ?>" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="observacoes">Observações:</label>
                        <textarea id="observacoes" name="observacoes" rows="3"><?php echo htmlspecialchars($aula['observacoes']); ?></textarea>
                    </div>

                    <button type="submit" class="btn-cadastrar">Salvar Alterações</button>

                    <p style="text-align: center; margin-top: 16px;">
                        <a href="Calendario_admin.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                            ← Voltar para o calendário
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="../public/assets/js/menu.js"></script>
</body>
</html>