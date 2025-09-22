<?php
session_start();

include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    
    $sql = "SELECT id, senha FROM usuarios WHERE email = ? AND telefone = ?";

    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "ss", $email, $telefone);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $senha_hash_do_banco = $row['senha'];

        if (password_verify($senha, $senha_hash_do_banco)) {
            $_SESSION['id_usuario'] = $row['id'];
            $_SESSION['email'] = $email;
            
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            header("Location: ../html/home.html");
            exit();
        } else {
            echo "<script>alert('Email, senha ou telefone Inválidos')</script>";
        }
    } else {
        echo "<script>alert('Email, senha ou telefone Inválidos')</script>";
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>