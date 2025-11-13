# Sistema Gerenciador de Agenda Escolar

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistema web para gestão de agenda escolar, desenvolvido como Trabalho de Conclusão de Curso (TCC). Permite o gerenciamento completo de professores, turmas, unidades curriculares e agendamento de aulas, com interfaces diferenciadas para administradores e professores.


## ✨ Funcionalidades

### 👨‍💼 Para Administradores
- **Gestão de Professores**: Cadastrar, editar e remover professores
- **Gestão de Unidades Curriculares**: Gerenciar disciplinas e currículos
- **Gestão de Turmas**: Criar e administrar turmas por turno
- **Agendamento de Aulas**: Agendar aulas com data, horário e sala
- **Calendário Visual**: Visualização completa das aulas agendadas
- **Dashboard Estatístico**: Métricas e informações do sistema
- **Geração Automática**: Criar aulas automaticamente baseadas na agenda regular
- **Gestão de Usuários**: Administrar contas de acesso

### 👨‍🏫 Para Professores
- **Visualização de Agenda**: Ver suas aulas agendadas
- **Calendário Personalizado**: Interface otimizada para professores
- **Detalhes das Aulas**: Informações sobre sala, horário e turma

## 🛠 Tecnologias

- **Backend**: PHP 8.0+
- **Banco de Dados**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3.3
- **Ícones**: Material Design Icons
- **Servidor**: Apache (WAMP)

## 📋 Pré-requisitos

- WAMP Server 3.2+ (ou similar LAMP/MAMP)
- PHP 8.0 ou superior
- MySQL 8.0 ou superior
- Navegador web moderno
- Acesso ao phpMyAdmin

## 🚀 Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/Erthal-guu/TCC.git
cd TCC
```

### 2. Configure o ambiente

Certifique-se de que o WAMP Server está instalado e rodando.

### 3. Configuração do banco de dados

1. Abra o phpMyAdmin (`http://localhost/phpmyadmin/`)
2. Crie um novo banco de dados chamado `gerenciador_agenda`
3. Importe o arquivo `docs/gerenciador_agenda.sql`

```bash
# Ou via linha de comando (MySQL)
mysql -u root -p gerenciador_agenda < docs/gerenciador_agenda.sql
```

### 4. Configuração do projeto

1. Copie o projeto para o diretório do WAMP:
   ```bash
   # Se estiver usando WAMP
   cp -r TCC C:/wamp64/www/
   ```

2. Verifique as configurações de banco em `config/database.php`:
   ```php
   <?php
   $server = "localhost";
   $user = "root";
   $password = "";
   $database = "gerenciador_agenda";
   ?>
   ```

### 5. Acesso ao sistema

Abra seu navegador e acesse:
- **URL Principal**: `http://localhost/TCC/public/`

## ⚙️ Configuração

### Usuário Padrão

Após a instalação, o sistema cria um usuário administrador padrão:

- **Email**: `Admin@gmail.com`
- **Senha**: Verifique o hash no banco de dados ou execute a query abaixo para definir uma nova senha:

```sql
UPDATE usuarios
SET senha = '$2y$10$iQWZL0rXPSLvSUYREm5glOf7ZWS2MmC1I0wsr7nHmnT42r.unvG.u'
WHERE nome_usuario = 'Admin';
```

*(Hash da senha: "admin123")*

### Importação de Unidades Curriculares

Para importar unidades curriculares em lote:
1. Formate seu arquivo CSV seguindo o padrão de `docs/uc.CSV`
2. Utilize a interface de importação em `app/lista_uc.php`

## 📁 Estrutura do Projeto

```
TCC/
├── 📁 app/                     # Lógica da aplicação
│   ├── 📄 conexao.php          # Conexão com banco de dados
│   ├── 📄 protect.php          # Proteção de sessão
│   ├── 📄 Crud_Professores.php # CRUD de professores
│   ├── 📄 calendario_admin.php # Calendário administrativo
│   ├── 📄 calendario_professor.php # Calendário professor
│   ├── 📄 gerar_aulas_automaticas.php # Geração automática
│   └── 📄 logout.php           # Logout do sistema
├── 📁 config/                  # Configurações
│   └── 📄 database.php         # Configuração do banco
├── 📁 docs/                    # Documentação
│   ├── 📄 README.md            # Documentação
│   ├── 📄 gerenciador_agenda.sql # Schema do banco
│   └── 📄 uc.CSV              # Dados de exemplo
├── 📁 public/                  # Arquivos públicos
│   ├── 📁 assets/              # Recursos estáticos
│   │   ├── 📁 css/             # Estilos CSS
│   │   ├── 📁 js/              # JavaScript
│   │   └── 📁 img/             # Imagens
│   ├── 📄 login.php            # Login administrador
│   ├── 📄 login_professor.php  # Login professor
│   ├── 📄 home_admin.php       # Dashboard admin
│   ├── 📄 home_professor.php   # Dashboard professor
│   └── 📄 cadastro_*.php       # Formulários de cadastro
└── 📄 README.md                # Este arquivo
```

## 🎯 Uso

### Fluxo de Trabalho do Administrador

1. **Login**: Acesse `http://localhost/TCC/public/login.php`
2. **Dashboard**: Visualize estatísticas do sistema
3. **Gerenciar Professores**: Cadastre professores e suas qualificações
4. **Gerenciar Turmas**: Crie turmas por turno
5. **Cadastrar UCs**: Adicione unidades curriculares
6. **Agendar Aulas**: Use a interface de calendário para agendar

### Fluxo de Trabalho do Professor

1. **Login**: Acesse `http://localhost/TCC/public/login_professor.php`
2. **Visualizar Agenda**: Veja suas aulas agendadas
3. **Calendário**: Consulte o calendário de suas aulas

## 🗄️ Banco de Dados

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuarios` | Usuários administradores do sistema |
| `professores` | Cadastro de professores |
| `uc` | Unidades curriculares |
| `turmas` | Turmas por turno |
| `aulas` | Aulas agendadas |
| `agenda_turmas` | Agenda regular de turmas |
| `disciplinas` | Disciplinas acadêmicas |
| `turnos` | Turnos (manhã, tarde, noite) |

### Relacionamentos

```
usuarios (admin)
├── gerencia professores
├── gerencia turmas
├── gerencia uc
└── gerencia aulas

aulas → professores (N:1)
aulas → turmas (N:1)
aulas → uc (N:1)

agenda_turmas → professor_materia_turno (N:1)
agenda_turmas → turmas (N:1)
```

## 🤝 Contribuição

Contribuições são bem-vindas! Para contribuir:

1. **Fork** este repositório
2. Crie uma branch para sua feature: `git checkout -b feature/NovaFuncionalidade`
3. **Commit** suas mudanças: `git commit -m 'Adicionando NovaFuncionalidade'`
4. **Push** para a branch: `git push origin feature/NovaFuncionalidade`
5. Abra um **Pull Request**

### Diretrizes de Contribuição

- Siga os padrões de código existentes
- Comentários explicativos em português
- Mantenha a consistência com o estilo atual
- Teste suas funcionalidades

## 🔧 Manutenção

### Backup do Banco de Dados

```bash
# Backup completo
mysqldump -u root -p gerenciador_agenda > backup_$(date +%Y%m%d).sql

# Restauração
mysql -u root -p gerenciador_agenda < backup_arquivo.sql
```

### Logs de Erro

Os logs do PHP podem ser encontrados em:
- **WAMP**: `C:\wamp64\logs\php\php_error.log`
- **Apache**: `C:\wamp64\logs\apache\error.log`

## 📝 Roadmap Futuro

- [ ] Sistema de notificações por email
- [ ] Relatórios estatísticos avançados
- [ ] Aplicação mobile (React Native)
- [ ] Sistema de avaliações
- [ ] Modo offline (PWA)

## 🐛 Problemas Conhecidos

- **Importação de CSV**: Formatação precisa ser UTF-8
- **Cache**: Limpar cache do navegador após atualizações
- **Timezone**: Verificar configuração de `date.timezone` no php.ini

## 📞 Suporte

Para suporte, reporte issues no [GitHub Issues](https://github.com/Erthal-guu/TCC/issues).

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

---

## 👨‍💻 Autores

- **Erthal-guu** - *Desenvolvimento Principal* - [GitHub](https://github.com/Erthal-guu)
- **isaacLkt** - *Desenvolvimento e Banco de Dados* - [GitHub](https://github.com/isaaclkt)
- **Bebelaaa** - *Interface e Documentação*

## 🙏 Agradecimentos

- **SENAI** - Pela oportunidade de desenvolvimento
- **Professores e Colaboradores** - Pelo suporte durante o desenvolvimento
- **Comunidade Open Source** - Pelas ferramentas e recursos utilizados

---

**Desenvolvido com ❤️ para o TCC**