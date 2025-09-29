<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

    $sql_check = "SELECT email FROM usuarios WHERE email = ?";
    $stmt_check = mysqli_prepare($connection, $sql_check);
    
    if (!$stmt_check) {
        die("Erro ao preparar a consulta de verificação: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Email já Cadastrado!');</script>";
        exit();
    } else {
        $sql_insert = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($connection, $sql_insert);
        
        if (!$stmt_insert) {
            die("Erro ao preparar a consulta de inserção: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt_insert, "sss", $nome, $email, $hashSenha);

        if (mysqli_stmt_execute($stmt_insert)) {
             header("Location: ../html/home.html");
        } else {
            echo "Erro ao cadastrar Usuário: " . mysqli_stmt_error($stmt_insert);
        }
        
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
    mysqli_close($connection);
}
?>