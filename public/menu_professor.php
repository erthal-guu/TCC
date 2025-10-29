<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu.css">
</head>
<body>
<div class="overlay" id="overlay"></div>
<button class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-title"><p><img src="../public/assets/img/logo-senai-home.png" alt=""></p></div>
    </div>

    <div class="sidebar-content">
        <div class="menu-section">
            <div class="menu-section-title">PRINCIPAL</div>
            <a href="../public/home_professor.php">
                <div class="menu-icon icon-home"></div>
                <span>Home</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">CADASTROS</div>
            <a href="../public/cadastro_usuarios.php">
                <div class="menu-icon icon-user"></div>
                <span>Cadastrar Usuaário</span>
            <a href="../public/cadastro_Professores.php">
                <div class="menu-icon icon-professor"></div>
                <span>Cadastrar Professor</span>
            </a>
            <a href="../public/cadastro_uc.php">
                <div class="menu-icon icon-book"></div>
                <span>Cadastrar UC</span>
            </a>
            <a href="../public/cadastro_turmas.php">
                <div class="menu-icon icon-users"></div>
                <span>Cadastrar Turma</span>
            </a>
            <a href="cadastrar_relacionamento.php">
                <div class="menu-icon icon-link"></div>
                <span>Relacionar Prof/Matéria/Turno</span>
            </a>
        </div>

        <div class="menu-divider"></div>

        <div class="menu-section">
            <div class="menu-section-title">AGENDA</div>
            <a href="gerenciar_agenda.php">
                <div class="menu-icon icon-calendar"></div>
                <span>Gerenciar Agenda</span>
            </a>
            <a href="visualizar_agenda.php">
                <a href="../app/Calendario.php"><div class="menu-icon icon-eye"></div><span>Visualizar Agenda</span></a>
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer-text">
            © 2025 Sistema Acadêmico<br>
            Versão 1.0
        </div>
    </div>
</div>
    <script src="assets/js/menu.js"></script>
</body>
</html>