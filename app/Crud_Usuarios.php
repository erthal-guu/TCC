<?php
include("conexao.php");
include("protect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];

        $sql_check = "SELECT COUNT(*) as count FROM professores WHERE id = $id";
        $result_check = $connection->query($sql_check);
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] > 0) {
            $error_message = "Não é possível excluir este usuário pois está vinculado a um professor!";
        } else {
            $sql_delete = "DELETE FROM usuarios WHERE id = $id";
            if ($connection->query($sql_delete)) {
                header("Location: Crud_Usuarios.php?msg=deleted");
                exit();
            } else {
                $error_message = "Erro ao excluir usuário: " . $connection->error;
            }
        }
    }
}

$sql = "SELECT id, nome_usuario, email FROM usuarios";
$result = $connection->query($sql);
if (!$result) {
    die("Erro na consulta: " . $connection->error);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link rel="stylesheet" href="../public/assets/css/lista.css">
    <link rel="stylesheet" href="../public/assets/css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">

</head>
<body>
    <?php include("../public/menu.php"); ?>

    <div class="container mt-5">
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Usuário excluído com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h2 style="color: #003D7A; font-weight: 600; font-size: 1.8rem;">
                Gerencie os usuários cadastrados
            </h2>
        </div>

        <div class="d-flex flex-wrap justify-content-center">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="professor-card">
                        <div class="card-header-section">
                            <img src="../public/assets/img/user.png"
                                 class="professor-avatar"
                                 alt="Avatar de <?= htmlspecialchars($row['nome_usuario']) ?>">
                            <div class="professor-info">
                                <div class="professor-name"><?= htmlspecialchars($row['nome_usuario']) ?></div>
                                <div class="professor-email">
                                    <i class="mdi mdi-email-outline"></i>
                                    <?= htmlspecialchars($row['email']) ?>
                                </div>
                            </div>
                            <i class="mdi mdi-dots-vertical menu-dots"></i>
                        </div>

                        <div class="info-section">
                            <div class="info-label">
                                <i class="mdi mdi-account-key"></i> Tipo de Usuário
                            </div>
                            <div class="info-content">
                                Administrador
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="editar_usuarios.php?id=<?= $row['id'] ?>" class="btn btn-editar">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            <form method="POST" style="flex: 1;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir o usuário <?= htmlspecialchars(addslashes($row['nome_usuario'])) ?>?');">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-deletar w-100">
                                    <i class="mdi mdi-delete"></i> Deletar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center p-5">
                    <i class="mdi mdi-account-off" style="font-size: 4rem; color: #6c757d;"></i>
                    <p class="mt-3 text-muted">Nenhum usuário encontrado.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mt-4 text-center mb-5">
        <a href="../public/cadastro_usuarios.php"
           class="btn btn-primary btn-lg"
           style="background-color: #003D7A; border: #003D7A; padding: 15px 40px; border-radius: 8px;">
            <i class="mdi mdi-plus-circle"></i> Adicionar Novo Usuário
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/menu.js"></script>
</body>
</html>

<?php
mysqli_close($connection);
?>