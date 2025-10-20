<?php
include("../app/conexao.php");
include("../app/protect.php");
protect();

$sql_professores = "SELECT id, nome FROM professores ORDER BY nome";
$result_professores = $connection->query($sql_professores);

$sql_uc = "SELECT id, unidade_curricular FROM uc ORDER BY unidade_curricular";
$result_uc = $connection->query($sql_uc);

$sql_turmas = "SELECT id, nome, turno FROM turmas ORDER BY nome";
$result_turmas = $connection->query($sql_turmas);

$connection->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Aula - Sistema de Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <link rel="stylesheet" href="assets/css/agendamento.css">
</head>
<body>
    <header class="header-principal">
        <div class="header-content">
            <button class="menu-button" onclick="window.location.href='home.php'">←</button>
            <img src="assets/img/logo-senai-home.png" alt="Logo SENAI" class="logo-senai">
        </div>
    </header>

    <div class="container-page">
        <div class="cadastro-wrapper">
            <div class="cadastro-card" style="max-width: 700px;">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <h1 class="card-title">Agendar Nova Aula</h1>
                <p class="card-subtitle">Preencha os dados para agendar uma nova aula no sistema</p>

                <form action="../app/processa_agendamento.php" method="POST" class="form-cadastro" id="formAgendamento">
                    
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="professor">Professor *</label>
                            <select name="professor_id" id="professor" class="form-select-modern" required>
                                <option value="">Selecione um professor</option>
                                <?php 
                                if ($result_professores && $result_professores->num_rows > 0) {
                                    while($row = $result_professores->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label for="uc">Unidade Curricular *</label>
                            <select name="uc_id" id="uc" class="form-select-modern" required>
                                <option value="">Selecione uma UC</option>
                                <?php 
                                if ($result_uc && $result_uc->num_rows > 0) {
                                    while($row = $result_uc->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['unidade_curricular']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="turma">Turma *</label>
                            <select name="turma_id" id="turma" class="form-select-modern" required>
                                <option value="">Selecione uma turma</option>
                                <?php 
                                if ($result_turmas && $result_turmas->num_rows > 0) {
                                    while($row = $result_turmas->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome']) . " - " . htmlspecialchars($row['turno']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label for="sala">Sala/Local</label>
                            <input type="text" name="sala" id="sala" placeholder="Ex: Sala 101" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="data">Data da Aula </label>
                            <input type="date" name="data_aula" id="data" required>
                        </div>

                        <div class="form-group-modern">
                            <label for="horario_inicio">Horário de Início</label>
                            <input type="time" name="horario_inicio" id="horario_inicio" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="horario_fim">Horário de Término</label>
                            <input type="time" name="horario_fim" id="horario_fim" required>
                        </div>

                        <div class="form-group-modern">
                            <label for="carga_horaria">Carga Horária (horas)</label>
                            <input type="number" name="carga_horaria" id="carga_horaria" step="0.5" min="0.5" placeholder="Ex: 2" required>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label for="observacoes">Observações</label>
                        <textarea name="observacoes" id="observacoes" rows="4" placeholder="Observações sobre a aula (opcional)"></textarea>
                    </div>

                    <button type="submit" class="btn-cadastrar">Agendar Aula</button>
                    <p style="text-align: center; margin-top: 16px;">
                        <a href="home.php" style="color: #003D7A; text-decoration: none; font-weight: 600;">← Voltar para Home</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formAgendamento').addEventListener('submit', function(e) {
            const horarioInicio = document.getElementById('horario_inicio').value;
            const horarioFim = document.getElementById('horario_fim').value;
            
            if (horarioInicio && horarioFim && horarioInicio >= horarioFim) {
                e.preventDefault();
                alert('O horário de término deve ser maior que o horário de início!');
            }
        });
        document.getElementById('data').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
