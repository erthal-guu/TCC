<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unidade_curricular = isset($_POST['unidade_curricular']) ? trim($_POST['unidade_curricular']) : '';
    $sigla = isset($_POST['sigla']) ? trim($_POST['sigla']) : '';
    $curso_modulo = isset($_POST['curso_modulo']) ? trim($_POST['curso_modulo']) : '';
    $id_turno = isset($_POST['id_turno']) ? $_POST['id_turno'] : '';
    $professores = isset($_POST['professores']) ? $_POST['professores'] : [];
    
    if (empty($unidade_curricular) || empty($sigla) || empty($curso_modulo)) {
        $msg = "Por favor, preencha todos os campos obrigatórios!";
        $msgType = "danger";
    } else {
        
        $check = $connection->prepare("SELECT id FROM uc WHERE unidade_curricular = ? OR sigla = ?");
        $check->bind_param("ss", $unidade_curricular, $sigla);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $msg = "Essa unidade curricular ou código já está cadastrado!";
            $msgType = "warning";
        } else {
            
            $sql = "INSERT INTO uc (sigla, unidade_curricular, curso_modulo) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sss", $sigla, $unidade_curricular, $curso_modulo);
            
            if ($stmt->execute()) {
                $id_unidade = $connection->insert_id;
                
                
                if (!empty($id_turno)) {
                    $table_check = $connection->query("SHOW TABLES LIKE 'uc_turno'");
                    
                    if ($table_check && $table_check->num_rows > 0) {
                        $sql_turno = "INSERT INTO uc_turno (id_uc, id_turno) VALUES (?, ?)";
                        $stmt_turno = $connection->prepare($sql_turno);
                        $stmt_turno->bind_param("ii", $id_unidade, $id_turno);
                        $stmt_turno->execute();
                        $stmt_turno->close();
                    }
                }
                
                
                if (!empty($professores)) {
                    $table_check = $connection->query("SHOW TABLES LIKE 'professor_unidade'");
                    
                    if ($table_check && $table_check->num_rows > 0) {
                        $sql_prof = "INSERT INTO professor_unidade (id_professor, id_unidade) VALUES (?, ?)";
                        $stmt_prof = $connection->prepare($sql_prof);
                        
                        foreach ($professores as $id_professor) {
                            $id_professor = intval($id_professor);
                            $stmt_prof->bind_param("ii", $id_professor, $id_unidade);
                            $stmt_prof->execute();
                        }
                        $stmt_prof->close();
                    }
                }
                
                $msg = "Unidade Curricular cadastrada com sucesso!";
                $msgType = "success";
                
                
                $unidade_curricular = '';
                $sigla = '';
                $curso_modulo = '';
                $id_turno = '';
            } else {
                $msg = "Erro ao cadastrar: " . $stmt->error;
                $msgType = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}


$professores_result = $connection->query("SELECT id, nome FROM professores ORDER BY nome ASC");


$turnos_result = $connection->query("SELECT id, nome FROM turnos ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Unidade Curricular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="assets/css/cadastro.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="top-header">
    <div class="logo-section">
        <h2><img src="assets/img/logo-senai-home.png" alt="Logo SENAI" /></h2>
    </div>
</div>

<div class="container-page">
    <div class="cadastro-wrapper">
        <div class="cadastro-card" style="max-width: 700px;">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>

            <h2 class="card-title">Cadastrar Nova Unidade Curricular</h2>

            <?php if($msg): ?>
                <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
                    <?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form class="form-cadastro" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="unidade_curricular">Nome da Unidade Curricular *</label>
                            <input type="text" id="unidade_curricular" name="unidade_curricular" class="form-control" 
                                   placeholder="Ex: Programação Web" 
                                   value="<?= htmlspecialchars($unidade_curricular ?? '') ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="sigla">Sigla/Código *</label>
                            <input type="text" id="sigla" name="sigla" class="form-control" 
                                   placeholder="Ex: UC-001" 
                                   value="<?= htmlspecialchars($sigla ?? '') ?>" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="curso_modulo">Curso/Módulo *</label>
                            <input type="text" id="curso_modulo" name="curso_modulo" class="form-control" 
                                   placeholder="Ex: Técnico em Informática - Módulo 2" 
                                   value="<?= htmlspecialchars($curso_modulo ?? '') ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="id_turno">Turno (Opcional)</label>
                            <select id="id_turno" name="id_turno" class="form-select-modern">
                                <option value="">Selecione o turno</option>
                                <?php 
                                if ($turnos_result && $turnos_result->num_rows > 0) {
                                    while($turno = $turnos_result->fetch_assoc()): 
                                ?>
                                    <option value="<?= $turno['id']; ?>" 
                                            <?= (isset($id_turno) && $id_turno == $turno['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($turno['nome']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                } else {
                                    echo '<option disabled>Nenhum turno cadastrado</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group-modern">
                    <label for="professores">Professores Responsáveis (Opcional)</label>
                    <select id="professores" name="professores[]" class  
                            multiple style="height: 150px;">
                        <?php 
                        if ($professores_result && $professores_result->num_rows > 0) {
                            while($row = $professores_result->fetch_assoc()): 
                        ?>
                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nome']); ?></option>
                        <?php 
                            endwhile;
                        } else {
                            echo '<option disabled>Nenhum professor cadastrado</option>';
                        }
                        ?>
                    </select>
                    <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar múltiplos professores</small>
                </div>

                <button type="submit" class="btn-cadastrar">
                    📚 Cadastrar Unidade Curricular
                </button>

                <p style="text-align: center; margin-top: 16px;">
                    <a href="home.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                        ← Voltar para Home
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/menu.js"></script>
</body>
</html>

<?php $connection->close(); ?>
