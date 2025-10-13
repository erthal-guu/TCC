<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_disciplina = isset($_POST['disciplina']) ? trim($_POST['disciplina']) : '';
    $turno = isset($_POST['turno']) ? $_POST['turno'] : '';
    $codigo_disciplina = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    $professores = isset($_POST['professores']) ? $_POST['professores'] : [];
    
    if (empty($nome_disciplina) || empty($turno) || empty($codigo_disciplina)) {
        $msg = "Por favor, preencha todos os campos obrigatórios!";
        $msgType = "danger";
    } else {
        $check = $connection->prepare("SELECT id FROM unidades_curriculares WHERE nome_unidade = ?");
        $check->bind_param("s", $nome_disciplina);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $msg = "Essa unidade curricular já está cadastrada!";
            $msgType = "warning";
        } else {
            $sql = "INSERT INTO unidades_curriculares (nome_unidade, codigo_unidade, turno) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sss", $nome_disciplina, $codigo_disciplina, $turno);
            
            if ($stmt->execute()) {
                $unidade_id = $connection->insert_id;
                
                if (!empty($professores)) {
                    $sql_prof = "INSERT INTO professor_unidade (id_professor, id_unidade) VALUES (?, ?)";
                    $stmt_prof = $connection->prepare($sql_prof);
                    
                    foreach ($professores as $professor_id) {
                        $stmt_prof->bind_param("ii", $professor_id, $unidade_id);
                        $stmt_prof->execute();
                    }
                    $stmt_prof->close();
                }
                
                $msg = "Unidade Curricular cadastrada com sucesso!";
                $msgType = "success";
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
                            <label for="disciplina">Nome da Disciplina *</label>
                            <input type="text" id="disciplina" name="disciplina" class="form-control" 
                                   placeholder="Ex: Programação Web" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="codigo">Código da Disciplina *</label>
                            <input type="text" id="codigo" name="codigo" class="form-control" 
                                   placeholder="Ex: UC-001" required>
                        </div>
                    </div>
                </div>

                <div class="form-group-modern">
                    <label for="turno">Turno(s) *</label>
                    <select id="turno" name="turno" class="form-select-modern" required>
                        <option value="" selected disabled>Selecione o(s) turno(s)</option>
                        <option value="MATUTINO">Matutino</option>
                        <option value="VESPERTINO">Vespertino</option>
                        <option value="NOTURNO">Noturno</option>
                        <option value="MATUTINO E VESPERTINO">Matutino e Vespertino</option>
                        <option value="MATUTINO E NOTURNO">Matutino e Noturno</option>
                        <option value="VESPERTINO E NOTURNO">Vespertino e Noturno</option>
                        <option value="INTEGRAL">Integral (Todos)</option>
                    </select>
                </div>

                <div class="form-group-modern">
                    <label for="professores">Professores Responsáveis (Opcional)</label>
                    <select id="professores" name="professores[]" class="form-select-modern" 
                            multiple style="height: 150px;">
                        <?php while($row = $professores_result->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nome']); ?></option>
                        <?php endwhile; ?>
                    </select>
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