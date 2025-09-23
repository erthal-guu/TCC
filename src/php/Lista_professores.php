<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador_agenda";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT 
            p.nome AS nome_professor,
            d.nome_disciplina,
            pd.nivel_capacitacao
        FROM 
            professores AS p
        INNER JOIN 
            professor_disciplina AS pd ON p.id = pd.id_professor
        INNER JOIN 
            disciplinas AS d ON d.id = pd.id_disciplina
        ORDER BY
            p.nome";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Professores e Disciplinas</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Lista de Professores e Suas Disciplinas</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Nome do Professor</th><th>Disciplina</th><th>Nível de Capacitação</th></tr>";
        
        // 4. Processar e exibir os resultados
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nome_professor"] . "</td>";
            echo "<td>" . $row["nome_disciplina"] . "</td>";
            echo "<td>" . $row["nivel_capacitacao"] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Nenhum professor encontrado.</p>";
    }
    
    $conn->close();
    ?>

</body>
</html>