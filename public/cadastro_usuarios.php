<?php
include("../app/conexao.php");

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
             header("Location:home.php");
        } else {
            echo "Erro ao cadastrar Usuário: " . mysqli_stmt_error($stmt_insert);
        }
        
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <link rel="stylesheet" href="assets/css/cadastro.css"> 
</head>
<body>
    <div class="container-cadastro">
        <div class="imagem-fundo"></div>

        <div class="formulario-lateral">
            <div class="logo-espaco">
                <img src="assets/img/logo-senai.jpg" alt="Logo da Empresa">
            </div>
            <h2>Cadastro</h2>
            <form id="cadastroForm" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="confirmaSenha">Confirme a Senha:</label>
                    <input type="password" id="confirmaSenha" name="confirmaSenha" required>
                </div>
                <div>
                    <p>já tem uma conta?<a href="login.php"> Entre aqui </a></p>
                </div>
                <button type="submit" onclick="ValidarCampos()">Cadastrar</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>