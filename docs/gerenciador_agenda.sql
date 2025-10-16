CREATE DATABASE IF NOT EXISTS `gerenciador_agenda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gerenciador_agenda`;

-- Estrutura da tabela `agenda_turmas`

DROP TABLE IF EXISTS `agenda_turmas`;
CREATE TABLE IF NOT EXISTS `agenda_turmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_professor_materia_turno` int NOT NULL,
  `id_turma` int NOT NULL,
  `dia` enum('Segunda','Terca','Quarta','Quinta','Sexta','Sabado') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_professor_materia_turno` (`id_professor_materia_turno`),
  KEY `id_turma` (`id_turma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estrutura da tabela `disciplinas`

DROP TABLE IF EXISTS `disciplinas`;
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_disciplina` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `codigo_disciplina` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `turno` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_disciplina` (`nome_disciplina`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Extraindo dados da tabela `disciplinas`

INSERT INTO `disciplinas` (`id`, `nome_disciplina`) VALUES
(2, 'Desenvolvimento de sistemas'),
(3, 'Automação Industrial'),
(4, 'Mecatrônica');

-- --------------------------------------------------------

-- Estrutura da tabela `nivel_capacitacao`

DROP TABLE IF EXISTS `nivel_capacitacao`;
CREATE TABLE IF NOT EXISTS `nivel_capacitacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nivel` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Extraindo dados da tabela `nivel_capacitacao`

INSERT INTO `nivel_capacitacao` (`id`, `nivel`) VALUES
(1, 'N0'),
(2, 'N1'),
(3, 'N2'),
(4, 'N3');

-- --------------------------------------------------------

-- Estrutura da tabela `professores`

DROP TABLE IF EXISTS `professores`;
CREATE TABLE IF NOT EXISTS `professores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disciplinas` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nivel_capacitacao` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Extraindo dados da tabela `professores`

INSERT INTO `professores` (`id`, `nome`, `email`, `disciplinas`, `nivel_capacitacao`) VALUES
(1, 'Gustavo erthal ', 'vplgugs@gmail.com', 'Desenvolvimento de sistemas', 'N3');

-- --------------------------------------------------------

-- Estrutura da tabela `turmas` (ATUALIZADA: com professor e sala)

DROP TABLE IF EXISTS `turmas`;
CREATE TABLE IF NOT EXISTS `turmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ano` int NOT NULL,
  `id_turno` int NOT NULL,
  `id_professor` int DEFAULT NULL,
  `sala` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_turno` (`id_turno`),
  KEY `id_professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estrutura da tabela `turnos`

DROP TABLE IF EXISTS `turnos`;
CREATE TABLE IF NOT EXISTS `turnos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Extraindo dados da tabela `turnos`
INSERT INTO `turnos` (`id`, `nome`) VALUES
(1, 'Manha'),
(2, 'Tarde'),
(3, 'Noite');

INSERT INTO turnos (nome) VALUES 
  ('Manhã'),
  ('Tarde'),
  ('Noite');


-- --------------------------------------------------------

-- Estrutura da tabela `usuarios`

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_usuario` (`nome_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Tabela `uc` substituindo `unidades_curriculares`

DROP TABLE IF EXISTS `uc`;
CREATE TABLE IF NOT EXISTS `uc` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sigla` VARCHAR(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unidade_curricular` VARCHAR(150) COLLATE utf8mb4_general_ci NOT NULL,
  `curso_modulo` VARCHAR(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
