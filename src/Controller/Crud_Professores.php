<?php
include("conexao.php");
$sql = "SELECT id, nome, disciplinas, nivel_capacitacao FROM professores";
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
    <link rel="stylesheet" href="../view/css/lista.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">

    <h1>Lista de Professores e Suas Disciplinas</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>"; 
        echo "<tr><th>Nome do Professor</th><th>Disciplina</th><th>Nível de Capacitação</th><th>Ações</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["disciplinas"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["nivel_capacitacao"]) . "</td>";
    echo "<td>
            <a href='editar_professores.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm'>Editar</a>
            <form method='POST' onsubmit='return confirm(\"Tem certeza que deseja excluir este professor?\")'>
                <input type='hidden' name='id' value='" . $row["id"] . "'>
                <input type='hidden' name='action' value='delete'>
                <button type='submit' class='btn btn-danger btn-sm'>Deletar</button>
            </form>
          </td>";
    echo "</tr>";
        }
        echo "</table>"; 
    } else {
        echo "<p>Nenhum professor encontrado.</p>";
    }

    $result->close();
    $connection->close();
    ?>
    
    <div class="Button_container mt-3">
        <a href="../html/cadastro_professores.html" class="btn btn-primary btn-lg">Adicionar</a>
    </div>
</div>
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
        echo "<script>alert('Professor excluído com sucesso!!'); window.location.href='Crud_professores.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir professor: " . mysqli_stmt_error($stmt_delete) . "');</script>";
    }

    mysqli_stmt_close($stmt_delete);
    mysqli_close($connection);
}
?>