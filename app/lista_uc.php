<?php
include("conexao.php");
include("protect.php");
protect();

$sql = "SELECT u.id, u.sigla, u.unidade_curricular, u.curso_modulo
        FROM uc u
        ORDER BY u.unidade_curricular ASC";

$result = $connection->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar_id'])) {
    $id_deletar = $_POST['deletar_id'];
    $sql_delete = "DELETE FROM uc WHERE id = ?";
    $stmt_delete = mysqli_prepare($connection, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $id_deletar);
    
    if (mysqli_stmt_execute($stmt_delete)) {
        header("Location: lista_uc.php");
        exit();
    } else {
        echo "<script>alert('Erro ao deletar unidade curricular!');</script>";
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Unidades Curriculares</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../public/assets/css/lista.css"> 
    <link rel="stylesheet" href="../public/assets/css/menu.css" />
    <?php include("../public/menu.php"); ?>
</head>
<body>

    <div class="container-page">
        <div class="header-section mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1>📚 Gerenciamento de Unidades Curriculares</h1>
                <p style="color: #718096; margin: 0;">Visualize e gerencie todas as unidades curriculares cadastradas</p>
            </div>
            <a href="../public/cadastro_uc.php" class="btn btn-primary">+ Nova UC</a>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Sigla</th>
                            <th>Unidade Curricular</th>
                            <th>Curso/Módulo</th>
                            <th style="width: 150px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($uc = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($uc['sigla']) ?></td>
                                <td><?= htmlspecialchars($uc['unidade_curricular']) ?></td>
                                <td><?= htmlspecialchars($uc['curso_modulo'] ?? '-') ?></td>
                                <td>
                                    <a href="editar_uc.php?id=<?= $uc['id'] ?>" class="btn btn-sm btn-warning me-1">✏️ Editar</a>
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Tem certeza que deseja deletar a unidade curricular <?= htmlspecialchars(addslashes($uc['unidade_curricular'])) ?>? Esta ação não pode ser desfeita.');">
                                        <input type="hidden" name="deletar_id" value="<?= $uc['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state text-center mt-5">
                <h3>🔭 Nenhuma unidade curricular cadastrada</h3>
                <p style="color: #718096; margin-bottom: 20px;">Comece cadastrando sua primeira unidade curricular!</p>
                <a href="cadastro_uc.php" class="btn btn-primary">+ Cadastrar Primeira UC</a>
            </div>
        <?php endif; ?>
        
        <div class="mt-4 text-center">
            <a href="home.php" style="color: white; text-decoration: none; font-weight: 600;">
                ← Voltar para Home
            </a>
        </div>
    </div>

<script src="../public/assets/js/menu.js"></script>
</body>
</html>