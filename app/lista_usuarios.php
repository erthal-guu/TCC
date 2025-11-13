<?php
include("conexao.php");
include("protect.php");
protect();

$sql = "SELECT u.id, u.nome_usuario, u.email
        FROM usuarios u
        ORDER BY u.nome_usuario ASC";

$result = $connection->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar_id'])) {
    $id_deletar = $_POST['deletar_id'];

    $sql_check = "SELECT COUNT(*) as count FROM professores WHERE id = $id_deletar";
    $result_check = $connection->query($sql_check);
    $row_check = $result_check->fetch_assoc();

    if ($row_check['count'] > 0) {
        echo "<script>alert('Não é possível excluir este usuário pois está vinculado a um professor!');</script>";
    } else {
        $sql_delete = "DELETE FROM usuarios WHERE id = ?";
        $stmt_delete = mysqli_prepare($connection, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $id_deletar);

        if (mysqli_stmt_execute($stmt_delete)) {
            header("Location: lista_usuarios.php");
            exit();
        } else {
            echo "<script>alert('Erro ao deletar usuário!');</script>";
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../public/assets/css/lista.css">
    <link rel="stylesheet" href="../public/assets/css/menu.css" />
    <?php include("../public/menu.php"); ?>
</head>
<body>

    <div class="container-page">
        <div class="header-section mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1>Gerenciamento de Usuários</h1>
                <p style="color: #718096; margin: 0;">Visualize e gerencie todos os usuários cadastrados</p>
            </div>
            <a href="../public/cadastro_usuarios.php" class="btn btn-primary" style="background-color: #003D7A; border: #003D7A;">+ Novo Usuário</a>
        </div>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome de Usuário</th>
                                <th>Email</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <img src="../public/assets/img/user.png" alt="User" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                            <span style="font-weight: 600;"><?= $row['nome_usuario'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #4a5568;"><?= $row['email'] ?></span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="editar_usuarios.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-editar">
                                                <i class="mdi mdi-pencil"></i> Editar
                                            </a>
                                            <form method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                <input type="hidden" name="deletar_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-deletar">
                                                    <i class="mdi mdi-delete"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state text-center py-5">
                    <i class="mdi mdi-account-off" style="font-size: 4rem; color: #cbd5e0;"></i>
                    <h3 class="mt-3" style="color: #4a5568;">Nenhum usuário encontrado</h3>
                    <p style="color: #718096;">Comece cadastrando um novo usuário no sistema.</p>
                    <a href="../public/cadastro_usuarios.php" class="btn btn-primary mt-3" style="background-color: #003D7A; border: #003D7A;">
                        <i class="mdi mdi-plus"></i> Cadastrar Primeiro Usuário
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/menu.js"></script>
</body>
</html>