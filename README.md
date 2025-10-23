
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h1 align="center">🎓 SGE DaxCode — Sistema de Gestão Escolar</h1>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-11.x-red" alt="Laravel Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-%3E=8.2-blue" alt="PHP Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/Status-Em%20Desenvolvimento-yellow" alt="Project Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/Licença-MIT-green" alt="License"></a>
</p>

---

## 📘 Sobre o Projeto

O **SGE DaxCode** (Sistema de Gestão Escolar) é uma plataforma completa desenvolvida em **Laravel** para automatizar e gerenciar os processos de uma instituição de ensino — da matrícula à geração de relatórios.

O projeto busca entregar uma **experiência moderna, responsiva e acessível**, permitindo que **administração, professores e alunos** interajam dentro de um ambiente integrado e inteligente.

---

## ⚙️ Tecnologias Principais

- **Laravel 11.x**
- **PHP 8.2+**
- **MySQL / MariaDB**
- **Blade Templates**
- **Bootstrap 5.3 + Icons**
- **Chart.js**
- **Tailwind (modo escuro)**

---

## 🧩 Estrutura do Sistema

### 📚 Núcleo Acadêmico
- **Alunos** — Cadastro completo com dados pessoais, documentos e upload de arquivos.
- **Professores** — Gerenciamento de docentes e vínculo com turmas.
- **Turmas** — Criação e acompanhamento por período, turno e status.
- **Disciplinas** — Cadastro, edição e vinculação com turmas e professores.
- **Documentos (UserDocuments)** — Upload e controle de status (pendente/aprovado).

### 🧠 Extensões Planejadas
- **Responsáveis (Pais/Guard.)**
- **Boletins e Avaliações**
- **Calendário Escolar**
- **Relatórios PDF/Excel**
- **Portal do Aluno / Professor**

---

## 🎨 Interface e Usabilidade

- Painel administrativo com **sidebar expansível** e **modo escuro persistente**.
- Dashboard com **gráficos interativos (Chart.js)** e **indicadores automáticos**.
- Layout **padronizado (`app.blade.php`)**, com mensagens de sucesso/erro dinâmicas.
- Upload e exibição de documentos diretamente nas telas de **edição e visualização**.

---

## 🔐 Controle de Acesso e Segurança

- Perfis de usuário: **Administrador, Professor e Aluno**.
- Validação e sanitização de dados em todos os formulários.
- Limitação de upload por tipo e tamanho (PDF, JPG, PNG até 5 MB).
- Autenticação nativa do Laravel com telas personalizadas e logotipo DaxCode.

---

## 🧱 Estrutura de Diretórios (Principais)

```
app/
 ├── Http/
 │   └── Controllers/
 │       ├── AlunoController.php
 │       ├── ProfessorController.php
 │       ├── TurmaController.php
 │       ├── DisciplinaController.php
 │       ├── DashboardController.php
 │       └── Admin/
 │           └── SettingController.php
 ├── Models/
 │   ├── Aluno.php
 │   ├── Professor.php
 │   ├── Turma.php
 │   ├── Disciplina.php
 │   ├── UserDocument.php
 │   └── Setting.php
database/
 └── migrations/
resources/
 └── views/
     ├── alunos/
     ├── professores/
     ├── turmas/
     ├── disciplinas/
     ├── admin/settings/
     └── layouts/
```

---

## 🚀 Instalação e Execução

### Pré-requisitos
- PHP ≥ 8.2
- Composer
- MySQL ou MariaDB
- Node.js + NPM (opcional)

### Passos

```bash
# Clone o repositório
git clone https://github.com/seuusuario/SGE_daxcode.git
cd SGE_daxcode

# Instale as dependências
composer install

# Copie o arquivo de ambiente e configure suas variáveis
cp .env.example .env
php artisan key:generate

# Configure o banco e rode as migrations
php artisan migrate

# (Opcional) Popule com dados iniciais
php artisan db:seed

# Inicie o servidor local
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 📊 Roadmap / Checklist de Desenvolvimento

| Etapa | Módulo | Status | Descrição |
|:------|:--------|:-------:|:-----------|
| 1️⃣ | Alunos | ✅ | CRUD completo + upload de documentos |
| 2️⃣ | Professores | ✅ | CRUD + vínculo com turmas |
| 3️⃣ | Turmas | ✅ | CRUD + exibição de alunos e professores |
| 4️⃣ | Disciplinas | ✅ | CRUD + vínculo com professores/turmas |
| 5️⃣ | Dashboard | ⚙️ | Gráficos dinâmicos |
| 6️⃣ | Responsáveis | ⏳ | Cadastro e vínculo aluno-responsável |
| 7️⃣ | Boletins/Avaliações | ⏳ | Controle de notas e médias |
| 8️⃣ | Perfis e Permissões | ⏳ | Middleware de acesso |
| 9️⃣ | Relatórios PDF/Excel | ⏳ | Exportações de dados |
| 🔟 | Portal do Aluno | 💡 | Área exclusiva para alunos |

---

## 💻 Equipe de Desenvolvimento

| Nome | Função | GitHub |
|------|---------|--------|
| Vinícius Lago | 🧑‍💻 Full Stack Developer / Líder do Projeto | [@vinicius-lago](https://github.com/vinicius-lago) |
| DaxCode Team | 🚀 Colaboração técnica e design | [@DaxCode-Labs](https://github.com/) |

---

## 🧾 Licença

Este projeto é licenciado sob a **MIT License** — veja o arquivo [LICENSE](https://opensource.org/licenses/MIT).

---

<p align="center">
  Desenvolvido com ❤️ por <strong>DaxCode</strong><br>
  <em>“Tecnologia e Educação caminhando juntas.”</em>
</p>
