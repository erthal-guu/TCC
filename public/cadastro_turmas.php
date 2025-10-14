<?php
include("../app/conexao.php");

$sql_turnos = "SELECT id, nome FROM turnos";
$result_turnos = $connection->query($sql_turnos);

$sql_professores = "SELECT id, nome FROM professores";
$result_professores = $connection->query($sql_professores);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $ano = $_POST['ano'] ?? '';
    $id_turno = $_POST['id_turno'] ?? '';
    $id_professor = $_POST['id_professor'] ?? null;
    $sala = $_POST['sala'] ?? null;
    if (empty($nome) || empty($ano) || empty($id_turno)) {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!');</script>";
    } else {
        $sql_check = "SELECT id FROM turmas WHERE nome = ? AND ano = ?";
        $stmt_check = mysqli_prepare($connection, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "si", $nome, $ano);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            echo "<script>alert('Turma já cadastrada com esse nome e ano!');</script>";
        } else {
            $sql_insert = "INSERT INTO turmas (nome, ano, id_turno, id_professor, sala) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($connection, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "siiss", $nome, $ano, $id_turno, $id_professor, $sala);

            if (mysqli_stmt_execute($stmt_insert)) {
                header("Location: ../app/lista_turmas.php");
                exit();
            } else {
                echo "Erro ao cadastrar Turma: " . mysqli_stmt_error($stmt_insert);
            }

            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Turmas</title>
    <link rel="stylesheet" href="assets/css/cadastro.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <?php include("menu.php"); ?>
    <header class="header-principal">
        <div class="header-content">
            <img src="assets/img/logo-senai-home.png" alt="SENAI Logo" class="logo-senai" />
        </div>
    </header>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card">
                <h2 class="card-title">Cadastro de Turmas</h2>
                <form method="post" class="form-cadastro">
                    <div class="form-group-modern">
                        <label for="nome">Nome da Turma:</label>
                        <input type="text" id="nome" name="nome" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="ano">Ano:</label>
                        <input type="text" id="ano" name="ano" required min="2000" max="2100" />
                    </div>

                    <div class="form-group-modern">
                        <label for="id_turno">Turno:</label>
                        <select class="form-select-modern" id="id_turno" name="id_turno" required>
                            <option value="">Escolha um turno</option>
                            <?php
                            if ($result_turnos->num_rows > 0) {
                                while ($row = $result_turnos->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nome']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Nenhum turno cadastrado</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label for="id_professor">Professor:</label>
                        <select class="form-select-modern" id="id_professor" name="id_professor">
                            <option value="">Nenhum professor</option>
                            <?php
                            if ($result_professores->num_rows > 0) {
                                while ($row = $result_professores->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nome']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label for="sala">Sala:</label>
                        <input type="text" id="sala" name="sala" />
                    </div>

                    <button type="submit" class="btn-cadastrar">Cadastrar</button>

                    <p style="text-align: center; margin-top: 16px;">
                        <a href="home.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                            ← Voltar para Home
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>