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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Professores e Disciplinas</title>
    <link rel="stylesheet" href="../public/assets/css/lista.css"> 
    <link rel="stylesheet" href="../public/assets/css/menu.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .professor-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 15px;
            width: 380px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .professor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        
        .card-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .professor-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid #003D7A;
            object-fit: cover;
        }
        
        .professor-info {
            flex: 1;
            margin-left: 20px;
        }
        
        .professor-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: #003D7A;
            margin-bottom: 5px;
        }
        
        .professor-email {
            font-size: 0.9rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .info-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #003D7A;
        }
        
        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #003D7A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-content {
            font-size: 1rem;
            color: #333;
            line-height: 1.5;
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .card-actions .btn {
            flex: 1;
            padding: 10px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-editar {
            background-color: #ffc107;
            border: none;
            color: #000;
        }
        
        .btn-editar:hover {
            background-color: #ffb300;
            transform: scale(1.02);
        }
        
        .btn-deletar {
            background-color: #dc3545;
            border: none;
            color: white;
        }
        
        .btn-deletar:hover {
            background-color: #c82333;
            transform: scale(1.02);
        }
        
        .menu-dots {
            color: #6c757d;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        .menu-dots:hover {
            color: #003D7A;
        }
    </style>
</head>
<body>
    <?php include("../public/menu.php"); ?>
    
    <div class="container mt-5">
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Professor excluído com sucesso!
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
                Gerencie os professores cadastrados
            </h2>
        </div>
        
        <div class="d-flex flex-wrap justify-content-center">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="professor-card">
                        <div class="card-header-section">
                            <img src="<?= htmlspecialchars($row['foto_url'] ?? '../public/assets/img/user.png') ?>" 
                                 class="professor-avatar" 
                                 alt="Foto de <?= htmlspecialchars($row['nome']) ?>">
                            <div class="professor-info">
                                <div class="professor-name"><?= htmlspecialchars($row['nome']) ?></div>
                                <div class="professor-email">
                                    <i class="mdi mdi-email-outline"></i>
                                    <?= htmlspecialchars($row['email'] ?? 'Email não informado') ?>
                                </div>
                            </div>
                            <i class="mdi mdi-dots-vertical menu-dots"></i>
                        </div>
                        
                        <div class="info-section">
                            <div class="info-label">
                                <i class="mdi mdi-book-open-variant"></i> Unidade Curricular
                            </div>
                            <div class="info-content">
                                <?= htmlspecialchars($row['unidade_curricular']) ?>
                            </div>
                        </div>
                        
                        <div class="info-section">
                            <div class="info-label">
                                <i class="mdi mdi-certificate"></i> Nível de Capacitação
                            </div>
                            <div class="info-content">
                                <?= htmlspecialchars($row['nivel_capacitacao']) ?>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <a href="editar_professores.php?id=<?= $row['id'] ?>" class="btn btn-editar">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            <form method="POST" style="flex: 1;" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir o professor <?= htmlspecialchars(addslashes($row['nome'])) ?>?');">
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
                    <p class="mt-3 text-muted">Nenhum professor encontrado.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mt-4 text-center mb-5">
        <a href="../public/cadastro_professores.php" 
           class="btn btn-primary btn-lg" 
           style="background-color: #003D7A; border: #003D7A; padding: 15px 40px; border-radius: 8px;">
            <i class="mdi mdi-plus-circle"></i> Adicionar Novo Professor
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/menu.js"></script>
</body>
</html>

<?php
mysqli_close($connection);
?>