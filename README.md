# Sistema de Gestão de Prestadores de Serviço da Empresa do Seu João

Sistema web desenvolvido em CakePHP 2 para gerenciamento de prestadores de serviço e seus serviços oferecidos.

## 📹 Vídeo de Apresentação

Preparei um vídeo demonstrando o funcionamento do sistema e explicando as principais decisões de arquitetura e produto tomadas durante o desenvolvimento.

[![Assista ao vídeo no YouTube](https://img.youtube.com/vi/5yq61A1qd94/0.jpg)](https://www.youtube.com/watch?v=5yq61A1qd94)

> **[Clique aqui para assistir ao vídeo completo](https://www.youtube.com/watch?v=5yq61A1qd94)**

## 🚀 Instalação

### 📋 Requisitos

- Docker e Docker Compose
- Git

### 1. Clone o repositório

```bash
git clone https://github.com/JhonataAugust0/teste-fullstack.git
cd teste-fullstack
```

### 2. Inicie os containers

```bash
docker-compose up -d
```

Isso irá iniciar:
- **Web Server** (Apache + PHP 7.2) na porta `8080`
- **MySQL 5.7** na porta `3306`

### 3. Configure o banco de dados

O banco de dados será criado automaticamente pelo Docker Compose. As credenciais padrão são:

- **Host:** `db` (dentro do Docker) ou `localhost:3306` (do host)
- **Database:** `desafio_doity`
- **User:** `doity_user`
- **Password:** `doity_password`

### 4. Execute o schema do banco

```bash
# Acesse o container do banco
docker-compose exec -T db mysql -u doity_user -pdoity_password desafio_doity < app/Config/Schema/database_schema.sql
```

Ou execute o SQL manualmente através de um cliente MySQL conectando em `localhost:3306`.

### 5. Configure permissões (se necessário)

```bash
docker-compose exec web chmod -R 755 app/tmp
docker-compose exec web chmod -R 755 app/webroot/img/uploads
```

### 6. Acesse a aplicação

Abra seu navegador em: **http://localhost:8080**

---

## 🚀 Instalação Rápida (Produção)

Para uma instalação mais rápida usando a imagem pré-construída do Docker Hub, que já inclui o código e configura automaticamente o banco de dados:

### 1. Clone o repositório

```bash
git clone https://github.com/JhonataAugust0/teste-fullstack.git
cd teste-fullstack
```

### 2. Inicie os containers com docker-compose.prod.yml

```bash
docker-compose -f docker-compose.prod.yml up -d
```

**O que acontece automaticamente:**

- ✅ Baixa a imagem pré-construída do Docker Hub (`jhonatasilva/seu-joao-doity:latest`)
- ✅ Cria e configura o banco de dados MySQL automaticamente
- ✅ Executa o schema SQL na inicialização do banco
- ✅ Configura as variáveis de ambiente necessárias
- ✅ Aguarda o banco estar saudável antes de iniciar a aplicação

### 3. Acesse a aplicação

Abra seu navegador em: **http://localhost:8080**

**Pronto!** O sistema estará totalmente funcional, sem necessidade de configuração manual do banco de dados.

### Parar os containers (produção)

```bash
docker-compose -f docker-compose.prod.yml down
```

**Nota:** Esta opção é ideal para demonstração ou uso em produção, pois não requer build local e configuração manual do banco.

---

## 📁 Estrutura do Projeto

```
teste-fullstack/
├── app/
│   ├── Config/          # Configurações (banco, rotas, etc)
│   ├── Controller/      # Controladores
│   ├── Lib/
│   │   └── Service/     # Camada de serviços organizada por feature
│   │       ├── Provider/    # Serviços relacionados a prestadores
│   │       ├── Service/     # Serviços relacionados ao catálogo
│   │       ├── Csv/         # Serviços de importação CSV
│   │       ├── PhotoUploadService.php
│   │       └── DashboardService.php
│   ├── Model/          # Modelos de dados
│   ├── View/           # Templates de visualização
│   └── webroot/        # Arquivos públicos (CSS, JS, imagens)
├── docs/               # Documentação técnica
├── docker-compose.yml  # Configuração Docker
└── Dockerfile         # Imagem Docker
```

## 🛠️ Desenvolvimento

### Parar os containers

```bash
docker-compose down
```

### Ver logs

```bash
docker-compose logs -f web
docker-compose logs -f db
```

### Acessar o container web

```bash
docker-compose exec web bash
```

### Acessar o MySQL

```bash
docker-compose exec db mysql -u doity_user -pdoity_password desafio_doity
```

## 🧪 Funcionalidades

### Atividade 01 - Gestão de Prestadores
- ✅ Cadastro de prestadores (nome, telefone, email, foto)
- ✅ Associação de serviços com valores
- ✅ Listagem com paginação
- ✅ Busca por nome, email, telefone ou serviço
- ✅ Edição e exclusão

### Atividade 02 - Importação CSV
- ✅ Upload de arquivo CSV
- ✅ Validação de dados linha a linha
- ✅ Criação automática de serviços não existentes
- ✅ Importação em massa com transação
- ✅ Relatório de erros

### Bônus - Dashboard
- ✅ Métricas de negócio
- ✅ Gráficos de serviços por prestadores
- ✅ Cache de performance

## 🔧 Tecnologias e Ferramentas

- **Backend:** PHP 7.2 + CakePHP 2.10
- **Banco de Dados:** MySQL 5.7
- **Frontend:** Bootstrap 5 + jQuery
- **Containerização:** Docker + Docker Compose
- **Git Flow:** Organização do fluxo de trabalho

## 📚 Documentação Técnica

A documentação completa está disponível em:

- **[ARCHITECTURE.md](docs/ARCHITECTURE.md)** - Arquitetura e padrões do projeto
- **[DATAFLOW.md](docs/DATAFLOW.md)** - Fluxos de dados e comunicação
- **[REFERENCE.md](docs/REFERENCE.md)** - Referência de APIs e serviços
- **[SPECIFICATION.md](docs/SPECIFICATION.md)** - Especificação do projeto de software
 - **[EXPERIENCE_REPORT.md](docs/EXPERIENCE_REPORT.md)** - Relatório de experiência do candidato (decisões, trade-offs e justificativas)
