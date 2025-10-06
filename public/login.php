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
    <title>Formulário de Login</title>
    <link rel="stylesheet" href="assets/css/cadastro.css" />
</head>
<body>
    <div class="container-cadastro">
        <div class="imagem-fundo"></div>

        <div class="formulario-lateral">
            <div class="logo-espaco">
                <img src="assets/img/logo-senai.jpg" alt="Logo da Empresa" />
            </div>
            <h2>Login</h2>
            <form id="cadastroForm" method="post" onsubmit="return ValidarCampos()">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required />
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required />
                </div>
                <div>
                    <p>Não tem uma conta? <a href="cadastro_usuarios.php">Cadastre-se aqui</a></p>
                </div>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
