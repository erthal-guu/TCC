<?php
include("../app/conexao.php");

$sql_disciplina = "SELECT nome_disciplina FROM disciplinas";
$result_disciplina = $connection->query($sql_disciplina);

$sql_nivel = "SELECT nivel FROM nivel_capacitacao";
$result_nivel = $connection->query($sql_nivel);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $disciplina = $_POST['disciplina'];
    $nivelCapacitacao = $_POST['nivel_capacitacao'];

    $sql_check = "SELECT email FROM professores WHERE email = ?";
    $stmt_check = mysqli_prepare($connection, $sql_check);
    
    if (!$stmt_check) {
        die("Erro ao preparar a consulta de verificação: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Email já Cadastrado!');</script>";
        exit();
    } else {
        $sql_insert = "INSERT INTO professores (nome, email, disciplinas, nivel_capacitacao) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($connection, $sql_insert);
        
        if (!$stmt_insert) {
            die("Erro ao preparar a consulta de inserção: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt_insert, "ssss", $nome, $email, $disciplina, $nivelCapacitacao);

        if (mysqli_stmt_execute($stmt_insert)) {
            header("Location: ../app/Crud_professores.php");

        } else {
            echo "Erro ao cadastrar Professor: " . mysqli_stmt_error($stmt_insert);
        }
        
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
    mysqli_close($connection);
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
                <p class="card-subtitle">Preencha os dados para cadastrar um novo professor no sistema</p>
                
                <form id="cadastroForm" method="post" class="form-cadastro">
                    <div class="form-group-modern">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu Nome" required>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu Email" required>
                    </div>
                    
                    <div class="form-group-modern">    
                        <label for="disciplina">Disciplina:</label>
                        <select class="form-select-modern" name="disciplina" required>
                            <option value="">Escolha uma disciplina</option>
                            <?php
                            if ($result_disciplina->num_rows > 0) {
                                while($row = $result_disciplina->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['nome_disciplina']) . '">' . htmlspecialchars($row['nome_disciplina']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Nenhuma disciplina cadastrada</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="nivel_capacitacao">Nível Capacitação:</label>
                        <select class="form-select-modern" name="nivel_capacitacao" required>
                            <option value="">Escolha um nível</option>
                            <?php
                            if ($result_nivel->num_rows > 0) {
                                while($row = $result_nivel->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['nivel']) . '">' . htmlspecialchars($row['nivel']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Nenhum nível cadastrado</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-cadastrar" onclick="ValidarCampos()">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>