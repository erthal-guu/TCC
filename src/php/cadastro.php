<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

    $sql_check="SELECT email FROM users WHERE email = '$email'";
    $result_check = mysqli_query($connection, $sql_check);
    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Email já Cadastrado!');</script>";
        exit();
    }
    else{

    $sql = "INSERT INTO users (nome,email,telefone,senha) VALUES ('$nome', '$email', '$telefone', '$hashSenha')";

    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Deu boa');</script>";
    } else {
        echo "Erro ao cadastrar Usuário: " . mysqli_error($connection);
    }
    }
}
?>