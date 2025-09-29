
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>Editar Professor</h1>
    <form method="POST" action="../php/editar_professores.php">
        <div class="mb-3">
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
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="Crud_professores.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>