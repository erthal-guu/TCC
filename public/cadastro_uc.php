<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador_agenda";

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nome_disciplina = isset($_POST['disciplina']) ? $_POST['disciplina'] : '';
    $turno = isset($_POST['turno']) ? $_POST['turno'] : '';
    $codigo_disciplina = isset($_POST['codigo']) ? $_POST['codigo'] : '';
    
    // Validação básica
   if (empty($nome_disciplina) || empty($turno) || empty($codigo_disciplina)) {
    $msg = "Por favor, preencha todos os campos!";
} else {
    // Verifica se já existe
    $check = $conn->prepare("SELECT id FROM disciplinas WHERE nome_disciplina = ?");
    $check->bind_param("s", $nome_disciplina);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $msg = "Essa disciplina já está cadastrada!";
    } else {
        // Insere matéria
        $sql = "INSERT INTO disciplinas (nome_disciplina, codigo_disciplina, turno) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome_disciplina, $codigo, $turno);
        
        if ($stmt->execute()) {
            $msg = "Matéria cadastrada com sucesso!";
        } else {
            $msg = "Erro: " . $stmt->error;
        }
        $stmt->close();
    }
    $check->close();
}
}

// Busca professores para o select
$professores_result = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
?><?php

// Busca professores para o select
$professores_result = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Matéria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/cadastro_uc.css">

</head>
<body>
<?php include 'menu.php'; ?>

  
<div class="container">
    <h1>CADASTRAR NOVA UNIDADE CURRICULAR</h1>

    <?php if($msg) echo "<div class='alert alert-success mt-2'>$msg</div>"; ?>
    

    <div class="card mt-2 mx-auto p-4 bg-light">
      <div class="card-body bg-light">
        
        <form id="contact-form" role="form" method="POST" action="">
          <div class="controls">
            <div class="row">
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="form_name">Disciplina</label>
                  <input id="form_name" type="text" name="disciplina" class="form-control" placeholder="Digite a disciplina" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="form_need">Turno</label>
                  <select id="form_need" name="turno" class="form-control" required>
                    <option value="" selected disabled>Selecione o(s) turno(s)</option>
                    <option>MATUTINO</option>
                    <option>VESPERTINO</option>
                    <option>NORTURNO</option>
                    <option>MATUNINO E VESPERTINO</option>
                    <option>MATUTINO E NORTURNO</option>
                    <option>VESPERTINO E NORTURNO</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="form_codigo">Código da disciplina</label>
              <textarea id="form_codigo" name="codigo" class="form-control" placeholder="Digite o código da disciplina" rows="2" required></textarea>
            </div>

            <div class="form-group">
              <label for="form_professores">Professores Responsáveis</label>
              <select id="form_professores" name="professores[]" class="form-control" multiple required>
                <?php while($row = $professores_result->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>"><?= $row['nome']; ?></option>
                <?php endwhile; ?>
              </select>
              <small>Segure Ctrl (Windows) ou Cmd (Mac) para selecionar mais de um</small>
            </div>

            <input type="submit" class="btn btn-success btn-send btn-block" value="Cadastrar">
          </div>
        </form>
      </div>
    </div>
  </div>
                     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>