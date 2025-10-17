<?php
include("conexao.php");
include("protect.php");
protect();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: lista_turmas.php");
    exit();
}

$sql = "SELECT * FROM turmas WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$turma = $result->fetch_assoc();

if (!$turma) {
    echo "<script>alert('Turma não encontrada.'); window.location.href='lista_turmas.php';</script>";
    exit();
}

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
        $sql_update = "UPDATE turmas SET nome = ?, ano = ?, id_turno = ?, id_professor = ?, sala = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($connection, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "siissi", $nome, $ano, $id_turno, $id_professor, $sala, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            header("Location: lista_turmas.php");
            exit();
        } else {
            echo "Erro ao atualizar turma: " . mysqli_stmt_error($stmt_update);
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Turma</title>
    <link rel="stylesheet" href="../public/assets/css/cadastro.css" />
    <link rel="stylesheet" href="../public/assets/css/menu.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <?php include("../public/menu.php"); ?>

    <div class="container-page">
        <h2>Editar Turma</h2>

        <form method="post" class="form-cadastro">
            <div class="form-group-modern">
                <label for="nome">Nome da Turma:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($turma['nome']) ?>" required />
            </div>
            <div class="form-group-modern">
                <label for="ano">Ano:</label>
                <input type="number" id="ano" name="ano" value="<?= htmlspecialchars($turma['ano']) ?>" required min="2000" max="2100" />
            </div>

            <div class="form-group-modern">
                <label for="id_turno">Turno:</label>
                <select id="id_turno" name="id_turno" required class="form-group-modern" >
                    <option value="">Escolha um turno</option>
                    <?php
                    if ($result_turnos->num_rows > 0) {
                        while ($row = $result_turnos->fetch_assoc()) {
                            $selected = $row['id'] == $turma['id_turno'] ? "selected" : "";
                            echo '<option value="' . htmlspecialchars($row['id']) . '" ' . $selected . '>' . htmlspecialchars($row['nome']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group-modern">
                <label for="id_professor">Professor:</label>
                <select id="id_professor" name="id_professor">
                    <option value="">Nenhum professor</option>
                    <?php
                    if ($result_professores->num_rows > 0) {
                        while ($row = $result_professores->fetch_assoc()) {
                            $selected = $row['id'] == $turma['id_professor'] ? "selected" : "";
                            echo '<option value="' . htmlspecialchars($row['id']) . '" ' . $selected . '>' . htmlspecialchars($row['nome']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group-modern">
                <label for="sala">Sala:</label>
                <input type="text" id="sala" name="sala" value="<?= htmlspecialchars($turma['sala']) ?>" />
            </div>

            <button type="submit" class="btn-cadastrar">Atualizar</button>
            <p style="text-align: center; margin-top: 16px;">
                <a href="lista_turmas.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                    ← Voltar para lista de turmas
                </a>
            </p>
        </form>
        <script src="../public/assets/js/menu.js"></script>
    </div>
    
</body>
</html>