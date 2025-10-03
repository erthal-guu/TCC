<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agenda - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <body>
        <body>

            <?php include 'menu.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Acadêmico - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        /* Header Superior */
        .top-header {
            background: #003D7A;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-section h2 {
            color: white;
            font-size: 24px;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s;
            color: white;
        }

        .notification-icon:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #E31E24;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #003D7A;
            font-weight: bold;
            font-size: 18px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: white;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .logout-btn {
            background: #E31E24;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c01a1f;
            transform: translateY(-2px);
        }

        /* Área de Conteúdo */
        .content-area {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-section {
            margin-bottom: 35px;
        }

        .welcome-section h1 {
            color: #003D7A;
            font-size: 32px;
            margin-bottom: 8px;
        }

        .welcome-section p {
            color: #6c757d;
            font-size: 16px;
        }

        /* Cards Horizontais */
        .cards-container {
            display: flex;
            gap: 25px;
            margin-bottom: 35px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            flex: 1;
            min-width: 280px;
            border-top: 4px solid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .card-professores {
            border-top-color: #003D7A;
        }

        .card-unidades {
            border-top-color: #0066B3;
        }

        .card-turmas {
            border-top-color: #00A1DE;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .card-professores .card-icon {
            background: linear-gradient(135deg, #003D7A, #0056A3);
            color: white;
        }

        .card-unidades .card-icon {
            background: linear-gradient(135deg, #0066B3, #0088D1);
            color: white;
        }

        .card-turmas .card-icon {
            background: linear-gradient(135deg, #00A1DE, #00BFFF);
            color: white;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: #003D7A;
            margin-bottom: 12px;
        }

        .card-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.6;
            font-size: 14px;
        }

        .card-stats {
            background: #f8f9fa;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            width: 100%;
        }

        .card-stats-number {
            font-size: 42px;
            font-weight: bold;
            color: #003D7A;
            display: block;
            margin-bottom: 5px;
        }

        .card-stats-label {
            font-size: 13px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-button {
            background: #003D7A;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s;
        }

        .card-button:hover {
            background: #002b5c;
            transform: scale(1.02);
        }

        .card-unidades .card-button {
            background: #0066B3;
        }

        .card-unidades .card-button:hover {
            background: #004f8a;
        }

        .card-turmas .card-button {
            background: #00A1DE;
        }

        .card-turmas .card-button:hover {
            background: #0082b3;
        }

        /* Seção de Estatísticas */
        .stats-section {
            background: white;
            border-radius: 12px;
            padding: 35px;
            margin-bottom: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stats-section h2 {
            color: #003D7A;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat-item {
            background: linear-gradient(135deg, #003D7A 0%, #0066B3 100%);
            padding: 25px;
            border-radius: 12px;
            color: white;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 61, 122, 0.3);
        }

        .stat-number {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Ações Rápidas */
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .quick-actions h2 {
            color: #003D7A;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .action-btn {
            padding: 18px 20px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 600;
            color: #003D7A;
            font-size: 15px;
        }

        .action-btn:hover {
            border-color: #003D7A;
            background: #f0f7ff;
            transform: translateX(5px);
        }

        .action-icon {
            font-size: 26px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #003D7A, #0066B3);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Header Superior -->
    <div class="top-header">
        <div class="logo-section">
            <h2>🎓 Sistema Acadêmico SESI/SENAI</h2>
        </div>
        <div class="user-section">
            <div class="notifications" onclick="alert('3 novas notificações')">
                <div class="notification-icon">🔔</div>
                <div class="notification-badge">3</div>
            </div>
            <div class="user-info" onclick="alert('Perfil do usuário')">
                <div class="user-avatar">AD</div>
                <div class="user-details">
                    <div class="user-name">Admin Sistema</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
            <button class="logout-btn" onclick="alert('Logout realizado')">Sair</button>
        </div>
    </div>

    <!-- Área de Conteúdo -->
    <div class="content-area">
        <div class="welcome-section">
            <h1>Bem-vindo de volta! 👋</h1>
            <p>Aqui está um resumo do seu sistema acadêmico</p>
        </div>

        <!-- Cards Horizontais -->
        <div class="cards-container">
            <div class="card card-professores">
                <div class="card-content">
                    <div class="card-icon">👨‍🏫</div>
                    <div class="card-title">Professores</div>
                    <div class="card-description">Gerencie e visualize a lista de professores cadastrados no sistema.</div>
                    <div class="card-stats">
                        <span class="card-stats-number">24</span>
                        <span class="card-stats-label">Professores Cadastrados</span>
                    </div>
                    <button class="card-button" onclick="alert('Visualizar Professores')">Visualizar</button>
                </div>
            </div>

            <div class="card card-unidades">
                <div class="card-content">
                    <div class="card-icon">📚</div>
                    <div class="card-title">Unidades Curriculares</div>
                    <div class="card-description">Consulte todas as disciplinas/unidades curriculares disponíveis.</div>
                    <div class="card-stats">
                        <span class="card-stats-number">18</span>
                        <span class="card-stats-label">Unidades Disponíveis</span>
                    </div>
                    <button class="card-button" onclick="alert('Visualizar UCs')">Visualizar</button>
                </div>
            </div>

            <div class="card card-turmas">
                <div class="card-content">
                    <div class="card-icon">👥</div>
                    <div class="card-title">Turmas</div>
                    <div class="card-description">Acompanhe as turmas cadastradas e seus respectivos turnos.</div>
                    <div class="card-stats">
                        <span class="card-stats-number">12</span>
                        <span class="card-stats-label">Turmas Ativas</span>
                    </div>
                    <button class="card-button" onclick="alert('Visualizar Turmas')">Visualizar</button>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="stats-section">
            <h2>📊 Estatísticas do Sistema</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">156</div>
                    <div class="stat-label">Total de Aulas Agendadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Aulas Hoje</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">92%</div>
                    <div class="stat-label">Taxa de Ocupação</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Pendências</div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="quick-actions">
            <h2>⚡ Ações Rápidas</h2>
            <div class="actions-grid">
                <button class="action-btn" onclick="alert('Cadastrar Professor')">
                    <span class="action-icon">➕</span>
                    <span>Novo Professor</span>
                </button>
                <button class="action-btn" onclick="alert('Cadastrar UC')">
                    <span class="action-icon">📖</span>
                    <span>Nova Unidade Curricular</span>
                </button>
                <button class="action-btn" onclick="alert('Cadastrar Turma')">
                    <span class="action-icon">🎯</span>
                    <span>Nova Turma</span>
                </button>
                <button class="action-btn" onclick="alert('Agendar Aula')">
                    <span class="action-icon">📅</span>
                    <span>Agendar Aula</span>
                </button>
            </div>
        </div>
    </div>


    </body>
    </html>

    <script src="assets/js/menu.js"></script>

</head>