<?php
include("conexao.php");

$sql = "SELECT nome,disciplinas,nivel_capacitacao FROM professores ";



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
    <link rel="stylesheet" href="../css/lista.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
</head>
<body>
<div class="container">
</table>

    <h1>Lista de Professores e Suas Disciplinas</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>"; 
        echo "<tr><th>Nome do Professor</th><th>Disciplina</th><th>Nível de Capacitação</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
             echo "<td>" . htmlspecialchars($row["disciplinas"]) . "</td>";
             echo "<td>" . htmlspecialchars($row["nivel_capacitacao"]) . "</td>";
             echo "</tr>";
        }
        echo "</table>"; 
    } else {
        echo "<p>Nenhum professor encontrado.</p>";
    }

    $result->close();
    $connection->close();
    ?>
    <div class="Button_container">
    <button type="button" class="btn btn-primary btn-lg">Adicionar</button>
    <button type="button" class="btn btn-primary btn-lg">Editar</button>
    <button type="button" class="btn btn-primary btn-lg">Deletar</button>
    </div>
</div>
</body>
</html>
