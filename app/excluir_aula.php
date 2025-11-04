<?php
include("conexao.php");
include("protect.php");
protect();

if (!isset($_GET['id']) || !isset($_GET['confirm'])) {
    header("Location: ../app/Calendario_admin.php");
    exit;
}

$aula_id = $_GET['id'];
$confirm = $_GET['confirm'];

if ($confirm === 'yes') {
    $sql = "DELETE FROM aulas WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $aula_id);

    if ($stmt->execute()) {
        header("Location: Calendario_admin.php?success=3");
        exit;
    } else {
        echo "Erro ao excluir aula: " . $stmt->error;
        exit;
    }
} else {
    header("Location: Calendario_admin.php");
    exit;
}

$connection->close();
?>