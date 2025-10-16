<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    die("ID do professor não informado.");
}
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $disciplina = $_POST['disciplina'];
    $nivelCapacitacao = $_POST['nivel_capacitacao'];

    $sql_update = "UPDATE professores SET nome=?, email=?, disciplinas=?, nivel_capacitacao=? WHERE id=?";
    $stmt_update = mysqli_prepare($connection, $sql_update);

    if (!$stmt_update) {
        die("Erro ao preparar a atualização: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt_update, "ssssi", $nome, $email, $disciplina, $nivelCapacitacao, $id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('Professor atualizado com sucesso!'); window.location.href='Crud_professores.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar professor: " . mysqli_stmt_error($stmt_update) . "');</script>";
    }

    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);
    exit();
}

$sql = "SELECT * FROM professores WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows === 0) {
    die("Professor não encontrado.");
}

$professor = $result->fetch_assoc();

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link rel="stylesheet" href="../public/assets/css/cadastro.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
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
                
                <h2 class="card-title">Editar Professor</h2>
    <form method="POST">
        <div class=" ">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($professor['nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($professor['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Disciplina</label>
            <input type="text" name="disciplina" class="form-control" value="<?php echo htmlspecialchars($professor['disciplinas']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nível de Capacitação</label>
            <input type="text" name="nivel_capacitacao" class="form-control" value="<?php echo htmlspecialchars($professor['nivel_capacitacao']); ?>" required>
        </div>
        <button type="submit" class="btn-cadastrar">Salvar Alterações</button>
        <a href="Crud_Professores.php" style="color: #003D7A; 
          text-decoration: none; 
          font-weight: 600; 
          margin-top: 20px; 
          display: inline-block;">
  ← Cancelar
</a>

    </form>
</body>
</html>
