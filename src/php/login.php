<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    
    $sql = "SELECT senha FROM users WHERE email = $email AND telefone = $telefone";

    $stmt = mysqli_prepare($connection, $sql);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $senha_hash_do_banco = $row['senha'];

        if (password_verify($senha, $senha_hash_do_banco)) {
            echo "Login bem-sucedido!";
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Email ou telefone não encontrados.";
    }

    mysqli_stmt_close($stmt);

    mysqli_close($connection);
}
?>