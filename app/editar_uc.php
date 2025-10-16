<?php
include("conexao.php");
include("protect.php");
protect();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: lista_uc.php");
    exit();
}

$sql = "SELECT * FROM uc WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$uc = $result->fetch_assoc();

if (!$uc) {
    echo "<script>alert('Unidade curricular não encontrada.'); window.location.href='lista_uc.php';</script>";
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sigla = $_POST['sigla'] ?? '';
    $unidade_curricular = $_POST['unidade_curricular'] ?? '';
    $curso_modulo = $_POST['curso_modulo'] ?? '';

    if (empty($sigla) || empty($unidade_curricular) || empty($curso_modulo)) {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!');</script>";
    } else {
        $sql_update = "UPDATE uc SET sigla = ?, unidade_curricular = ?, curso_modulo = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($connection, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "sssi", $sigla, $unidade_curricular, $curso_modulo, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            header("Location: lista_uc.php");
            exit();
        } else {
            echo "Erro ao atualizar unidade curricular: " . mysqli_stmt_error($stmt_update);
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Unidade Curricular</title>
    <link rel="stylesheet" href="../public/assets/css/cadastro.css" />
    <link rel="stylesheet" href="../public/assets/css/menu.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <?php include("../public/menu.php"); ?>

    <div class="container-page">
        <h2>Editar Unidade Curricular</h2>

        <form method="post" class="form-cadastro">
            <div class="form-group-modern">
                <label for="sigla">Sigla/Código *</label>
                <input type="text" id="sigla" name="sigla" value="<?= htmlspecialchars($uc['sigla']) ?>" required />
            </div>

            <div class="form-group-modern">
                <label for="unidade_curricular">Nome da Unidade Curricular *</label>
                <input type="text" id="unidade_curricular" name="unidade_curricular" value="<?= htmlspecialchars($uc['unidade_curricular']) ?>" required />
            </div>

            <div class="form-group-modern">
                <label for="curso_modulo">Curso/Módulo *</label>
                <input type="text" id="curso_modulo" name="curso_modulo" value="<?= htmlspecialchars($uc['curso_modulo']) ?>" required />
            </div>

            <button type="submit" class="btn-cadastrar">✏️ Atualizar</button>
            <p style="text-align: center; margin-top: 16px;">
                <a href="lista_uc.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">
                    ← Voltar para lista de unidades curriculares
                </a>
            </p>
        </form>
    </div>
    
</body>
</html>