<?php
session_start();
include("../app/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $sql = "SELECT id, senha, nome_usuario FROM usuarios WHERE email = ?";

    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $senha_hash_do_banco = $row['senha'];

        if (password_verify($senha, $senha_hash_do_banco)) {
            $_SESSION['id_usuario'] = $row['id'];
            $_SESSION['nome_usuario'] = $row['nome_usuario'];
            $_SESSION['email'] = $email;

            mysqli_stmt_close($stmt);
            mysqli_close($connection);

            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Email ou senha inválidos.');</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado.');</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistema de Agenda</title>
    <link rel="stylesheet" href="assets/css/cadastro.css" />
</head>
<body>
    <div class="header-principal">
        <div class="header-content">
            <img src="assets/img/logo-senai.jpg" alt="Logo SENAI" class="logo-senai" />
        </div>
    </div>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <h2 class="card-title">Bem-vindo de volta!</h2>
                <p class="card-subtitle">Faça login para acessar o sistema de agenda</p>

                <form class="form-cadastro" method="post">
                    <div class="form-group-modern">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required />
                    </div>

                    <div class="form-group-modern">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required />
                    </div>

                    <button type="submit" class="btn-cadastrar">Entrar</button>

                    <p style="text-align: center; margin-top: 16px; color: #6c757d; font-size: 14px;">
                        Não tem uma conta? <a href="cadastro_usuarios.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">Cadastre-se aqui</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>