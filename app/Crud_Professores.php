<?php
include("conexao.php");
$sql = "SELECT id, nome, unidade_curricular, nivel_capacitacao, email FROM professores"; 
$result = $connection->query($sql);
if (!$result) {
    die("Erro na consulta: " . $connection->error);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Professores e Disciplinas</title>
    <link rel="stylesheet" href="../public/assets/css/lista.css"> 
    <link rel="stylesheet" href="../public/assets/css/menu.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center mt-5">
    <div class="main d-flex flex-wrap justify-content-center">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="one">
                    <div class="text-right pr-2 pt-1"><i class="mdi mdi-dots-vertical dotdot"></i></div>
                    <div class="d-flex justify-content-center mb-3">
                        <img src="https://i.imgur.com/hczKIze.jpg" width="50" class="rounded-circle" alt="Foto de <?= htmlspecialchars($row['nome']) ?>">
                    </div>
                    <div class="text-center">
                        <span class="name"><?= htmlspecialchars($row['nome']) ?></span>
                        <p class="mail"><?= htmlspecialchars($row['email'] ?? 'Email não informado') ?></p>
                    </div>
                    <div class="unidade_curricular"><strong>Disciplinas:</strong> <?= htmlspecialchars($row['unidade_curricular']) ?></div>
                    <div class="nivel_capacitacao"><strong>Nível de Capacitação:</strong> <?= htmlspecialchars($row['nivel_capacitacao']) ?></div>
                    <div class="actions">
                        <a href="editar_professores.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o professor <?= htmlspecialchars(addslashes($row['nome'])) ?>?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Deletar</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Nenhum professor encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-4 text-center">
    <a href="../public/cadastro_professores.php" class="btn btn-primary btn-lg">Adicionar Novo Professor</a>
</div>

<script src="../public/assets/js/menu.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    include("conexao.php");

    $id = $_POST['id'];

    $sql_delete = "DELETE FROM professores WHERE id = ?";
    $stmt_delete = mysqli_prepare($connection, $sql_delete);
        
    if (!$stmt_delete) {
        die("Erro ao preparar a consulta de exclusão: " . mysqli_error($connection));
    }   
    
    mysqli_stmt_bind_param($stmt_delete,"i",$id);
    
    if(mysqli_stmt_execute($stmt_delete)){
        echo "<script>alert('Professor excluído com sucesso!'); window.location.href='Crud_professores.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir professor: " . mysqli_stmt_error($stmt_delete) . "');</script>";
    }

    mysqli_stmt_close($stmt_delete);
    mysqli_close($connection);
}
?>
