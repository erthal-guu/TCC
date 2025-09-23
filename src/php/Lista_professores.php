<?php
include("conexao.php");

$sql = "SELECT 
    p.nome AS nome_professor,
    d.nome_disciplina,
    pd.nivel_capacitacao
FROM 
    professores p
JOIN 
    professor_disciplina pd ON p.id = pd.id_professor
JOIN 
    disciplinas d ON d.id = pd.id_disciplina
ORDER BY
    p.nome";



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
            echo "<td>" . htmlspecialchars($row["nome_professor"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["nome_disciplina"]) . "</td>";
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
