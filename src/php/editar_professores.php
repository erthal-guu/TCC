<?php
include("conexao.php");

if (!isset($_GET['id'])) {
    die("ID do professor não informado.");
}
$id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $disciplina = $_POST['disciplina'];
    $nivelCapacitacao = $_POST['nivel_capacitacao'];

    $sql_update = "UPDATE professores SET nome=?, email=?, disciplinas=?, nivel_capacitacao=? WHERE id=?";
    $stmt_update = mysqli_prepare($connection, $sql_update);

    if (!$stmt_update) {
        die("Erro ao preparar a atualização: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt_update, "ssssi", $nome, $email, $disciplina, $nivelCapacitacao, $id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('Professor atualizado com sucesso!'); window.location.href='Crud_professores.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar professor: " . mysqli_stmt_error($stmt_update) . "');</script>";
    }

    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);
    exit();
}
$sql = "SELECT * FROM professores WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows === 0) {
    die("Professor não encontrado.");
}

$professor = $result->fetch_assoc();

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>