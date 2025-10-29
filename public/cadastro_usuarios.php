<?php
include("../app/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirmaSenha'];

    if ($senha !== $confirmaSenha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } else {
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
            echo "<script>alert('Email já cadastrado!');</script>";
        } else {
            $sql_insert = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($connection, $sql_insert);
            
            if (!$stmt_insert) {
                die("Erro ao preparar a consulta de inserção: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt_insert, "sss", $nome, $email, $hashSenha);

            if (mysqli_stmt_execute($stmt_insert)) {
                echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
                exit();
            } else {
                echo "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt_insert);
            }
            
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Agenda</title>
    <link rel="stylesheet" href="assets/css/cadastro.css"> 
</head>
<body>
   <?php include("../public/menu.php");?>
    <div class="header-principal">
        <div class="header-content">
            <img src="assets/img/logo-senai-home.png" alt="Logo SENAI" class="logo-senai" />
        </div>
    </div>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>

                <h2 class="card-title">Cadastrar Usuários</h2>
                <p class="card-subtitle">Preencha os dados abaixo para se cadastrar no sistema</p>

                <form class="form-cadastro" method="post">
                    <div class="form-group-modern">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                    </div>

                    <div class="form-group-modern">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    </div>

                    <div class="form-group-modern">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="form-group-modern">
                        <label for="confirmaSenha">Confirme a Senha</label>
                        <input type="password" id="confirmaSenha" name="confirmaSenha" placeholder="Confirme sua senha" required>
                    </div>

                    <button type="submit" class="btn-cadastrar">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>