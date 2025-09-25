<?php
include("conexao.php");

$sql = "SELECT nome,disciplina,nivel_capacitacao FROM professores ";



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
</head>
<body>

    <h1>Lista de Professores e Suas Disciplinas</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table>"; 
        echo "<tr><th>Nome do Professor</th><th>Disciplina</th><th>Nível de Capacitação</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
             echo "<td>" . htmlspecialchars($row["disciplina"]) . "</td>";
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

</body>
</html>
