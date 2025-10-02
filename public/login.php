<?php
session_start();

include("../app/conexao.php");

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


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Login</title>
    <link rel="stylesheet" href="assets/css/cadastro.css"> 
</head>
<body>
    <div class="container-cadastro">
        <div class="imagem-fundo"></div>

        <div class="formulario-lateral">
            <div class="logo-espaco">
                <img src="assets/img/logo-senai.jpg" alt="Logo da Empresa">
            </div>
            <h2>Login</h2>
            <form id="cadastroForm" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div>
                    <p>Não tem uma conta ?<a href="cadastro_usuarios.php"> Cadastre-se aqui </a></p>
                </div>
                <button type="submit" onclick="ValidarCampos()">Entrar</button>
            </form>
        </div>
    </div>
    <script src="Login.js"></script>
</body>
</html>