<?php
include("conexao.php");

 if ($_SERVER['REQUEST_METHOD'] === 'POST'){
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];
$hashSenha = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO professores (nome,email,telefone,senha) VALUES('$nome','$email','$telefone','$hashSenha')";
if (mysqli_query($connection ,$sql)) {
    header("location : home.html");
    exit();   
}
else {
    echo "Erro ao cadastrar professor: " . mysqli_error($connection);
}

}
?>