<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$msg = "";
$msgType = "";

// Busca unidades curriculares da nova tabela uc
$sql_uc = "SELECT unidade_curricular FROM uc ORDER BY unidade_curricular ASC";
$result_uc = $connection->query($sql_uc);

// Busca níveis de capacitação
$sql_nivel = "SELECT nivel FROM nivel_capacitacao ORDER BY nivel ASC";
$result_nivel = $connection->query($sql_nivel);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $unidade_curricular = isset($_POST['unidade_curricular']) ? $_POST['unidade_curricular'] : '';
    $nivel_capacitacao = isset($_POST['nivel_capacitacao']) ? $_POST['nivel_capacitacao'] : '';

    // Validação de campos obrigatórios
    if (empty($nome) || empty($email) || empty($unidade_curricular) || empty($nivel_capacitacao)) {
        $msg = "Por favor, preencha todos os campos obrigatórios!";
        $msgType = "danger";
    } else {
        // Verifica se o email já está cadastrado
        $sql_check = "SELECT email FROM professores WHERE email = ?";
        $stmt_check = $connection->prepare($sql_check);
        
        if (!$stmt_check) {
            $msg = "Erro ao preparar a consulta de verificação: " . $connection->error;
            $msgType = "danger";
        } else {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            if ($result_check->num_rows > 0) {
                $msg = "Este email já está cadastrado!";
                $msgType = "warning";
            } else {
                // Insere o novo professor na tabela professores
                $sql_insert = "INSERT INTO professores (nome, email, unidade_curricular, nivel_capacitacao) VALUES (?, ?, ?, ?)";
                $stmt_insert = $connection->prepare($sql_insert);
                
                if (!$stmt_insert) {
                    $msg = "Erro ao preparar a consulta de inserção: " . $connection->error;
                    $msgType = "danger";
                } else {
                    $stmt_insert->bind_param("ssss", $nome, $email, $unidade_curricular, $nivel_capacitacao);

                    if ($stmt_insert->execute()) {
                        $msg = "Professor cadastrado com sucesso!";
                        $msgType = "success";
                        
                        // Limpa os campos após sucesso
                        $nome = '';
                        $email = '';
                        $unidade_curricular = '';
                        $nivel_capacitacao = '';
                        
                        // Redireciona após 2 segundos
                        header("refresh:2;url=home.php");
                    } else {
                        $msg = "Erro ao cadastrar Professor: " . $stmt_insert->error;
                        $msgType = "danger";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Professores</title>
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"> 
</head>
<body>
    <?php include("menu.php");?>
    
    <header class="header-principal">
        <div class="header-content">
            <img src="assets/img/logo-senai-home.png" alt="SENAI Logo" class="logo-senai">
        </div>
    </header>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" y1="8" x2="19" y2="14"></line>
                        <line x1="22" y1="11" x2="16" y2="11"></line>
                    </svg>
                </div>
                
                <h2 class="card-title">Cadastro de Professor</h2>
                
                <?php if($msg): ?>
                    <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
                        <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="form-cadastro">
                    <div class="form-group-modern">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite o nome do professor" 
                               value="<?= htmlspecialchars($nome ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="Digite o email do professor" 
                               value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    <div class="form-group-modern">    
                        <label for="unidade_curricular">Unidade Curricular *</label>
                        <select id="unidade_curricular" name="unidade_curricular" class="form-select-modern" required>
                            <option value="">Selecione uma unidade curricular</option>
                            <?php
                            if ($result_uc && $result_uc->num_rows > 0) {
                                while($row = $result_uc->fetch_assoc()) {
                                    $selected = (isset($unidade_curricular) && $unidade_curricular == $row['unidade_curricular']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['unidade_curricular']) . '" ' . $selected . '>' . htmlspecialchars($row['unidade_curricular']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Nenhuma unidade curricular cadastrada</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="nivel_capacitacao">Nível de Capacitação *</label>
                        <select id="nivel_capacitacao" name="nivel_capacitacao" class="form-select-modern" required>
                            <option value="">Selecione um nível</option>
                            <?php
                            if ($result_nivel && $result_nivel->num_rows > 0) {
                                while($row = $result_nivel->fetch_assoc()) {
                                    $selected = (isset($nivel_capacitacao) && $nivel_capacitacao == $row['nivel']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['nivel']) . '" ' . $selected . '>' . htmlspecialchars($row['nivel']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Nenhum nível cadastrado</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-cadastrar">👨‍🏫 Cadastrar Professor</button>
                    
                    <p style="text-align: center; margin-top: 16px;">
                        <a href="home.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                            ← Voltar para Home
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $connection->close(); ?>