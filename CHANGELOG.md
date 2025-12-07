# Changelog

Todas as mudanças notáveis deste projeto estão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [Unreleased]

### Revisão de Código e Documentação (`3ee3e3c` - 07/12/2025)

#### Adicionado - Documentação Técnica Completa
- **README.md:**
  - Instruções de instalação para desenvolvimento (docker-compose.yml)
  - Instruções de instalação rápida para produção (docker-compose.prod.yml)
  - Estrutura do projeto e tecnologias utilizadas
  - Comandos úteis para desenvolvimento

- **ARCHITECTURE.md:**
  - Filosofia de design e separação de responsabilidades
  - Visão em camadas (Controllers → Services → Models → Database)
  - Estrutura de módulos organizados por feature
  - Padrões adotados (Facade, Service Layer, Query/CRUD Separation)
  - Organização de diretórios

- **DATAFLOW.md:**
  - Modelo de fluxo de dados no CakePHP 2
  - Padrão de atualização unidirecional
  - Comunicação entre componentes
  - Fluxos detalhados por feature (Cadastro, Importação CSV, Busca, Dashboard)
  - Estado Global vs Local (Cache, Session)

- **REFERENCE.md:**
  - Referência completa de todos os Services (métodos, parâmetros, retornos)
  - Referência de Models (validações, relacionamentos, callbacks)
  - Referência de Controllers (actions, componentes)
  - Estruturas de dados padronizadas

- **SPECIFICATION.md:**
  - Atualizado com seção sobre padrões arquiteturais
  - Referências aos novos documentos de arquitetura
  - Documentação da nova organização de diretórios

#### Alterado - Organização de Serviços por Feature
- **Estrutura de Diretórios:**
  - Serviços organizados em `app/Lib/Service/Provider/` (prestadores)
  - Serviços organizados em `app/Lib/Service/Service/` (catálogo)
  - Serviços organizados em `app/Lib/Service/Csv/` (importação)
  - Utilitários compartilhados mantidos na raiz (PhotoUploadService, DashboardService)

- **Refatoração de ServiceService:**
  - Refatorado para padrão Facade (consistente com ProviderBusinessService)
  - Criado `ServiceQueryService` para consultas e busca
  - Criado `ServiceCrudService` para operações CRUD
  - Removido método `getProvidersList()` (não utilizado e fora de contexto)

- **Atualização de Imports:**
  - Todos os `App::uses()` atualizados para refletir novos caminhos
  - Controllers atualizados para usar novos paths dos serviços

#### Melhorado - Consistência Arquitetural
- Todos os serviços seguem o mesmo padrão Facade
- Separação clara entre Query Services e CRUD Services
- Código mais organizado e fácil de manter
- Melhor rastreabilidade de responsabilidades

---

### CI/CD - Integração e Entrega Contínua (`c6bc447`, `74d9fb4`, `4398c45` - 06/12/2025)

#### Adicionado - Pipelines GitHub Actions
- **Workflow de Integração Contínua (CI):**
  - Executa apenas na branch `main` (push e pull requests)
  - Linter PHP CodeSniffer com padrão CakePHP
  - Testes unitários com banco MySQL de teste
  - Fail-fast: Pipeline falha na primeira falha de teste (`--stop-on-failure`)
  - Cache de dependências Composer para builds mais rápidos

- **Workflow de Entrega Contínua (CD):**
  - Build automático da imagem Docker
  - Publicação no GitHub Container Registry (ghcr.io)
  - Tags automáticas: `latest`, SHA do commit, nome da branch
  - Cache de layers Docker via GitHub Actions
  - Suporte a releases para versionamento

- **Arquivos criados:**
  - `.github/workflows/ci.yml` - Pipeline de lint e testes
  - `.github/workflows/cd.yml` - Pipeline de build e publicação

---

### Dashboard de Métricas (`a70cebe`, `533dade`, `6477ffa`, `468672a` - 06/12/2025)

#### Adicionado - Painel de Controle Completo
- **Métricas em Tempo Real:**
  - Total de prestadores cadastrados
  - Total de tipos de serviços no catálogo
  - Ticket médio dos serviços
  - Serviço mais popular (com mais prestadores)
  - Faixa de preços (mínimo e máximo)
  - Últimos prestadores cadastrados com seus serviços

- **Gráfico de Barras (Chart.js):**
  - Visualização de prestadores por serviço
  - Top 10 serviços com mais prestadores
  - Cores alinhadas com identidade visual Doity

- **Cache de Métricas:**
  - Cache de 15 minutos para otimizar performance
  - Invalidação automática ao criar/editar/excluir prestadores
  - Invalidação após importação CSV

- **Arquitetura:**
  - `DashboardService` - Serviço de métricas com cache integrado
  - `HomeController` - Controller dedicado para o dashboard
  - Rota `/` agora direciona para o dashboard

---

### Importação CSV de Prestadores (`9c4ff8c` - 06/12/2025)

#### Adicionado - Funcionalidade de Importação em Massa
- **Upload de arquivo CSV** via modal na listagem de prestadores
  - Aceita arquivos .csv com até 25MB
  - Auto-detecção de delimitador (vírgula ou ponto-e-vírgula)
  - Limite de 1000 linhas por arquivo (previne DoS)

- **Suporte a Múltiplos Serviços por Prestador**
  - Para adicionar vários serviços, repita o email em linhas diferentes:
    ```csv
    João,joao@email.com,82999,Eletricista,150.00
    João,joao@email.com,82999,Encanador,200.00
    ```
  - Sistema agrupa automaticamente por email antes de importar

- **Arquitetura SRP - Serviços Especializados**
  - `CsvFileValidator` - Validação de arquivo (extensão, MIME type, tamanho, conteúdo malicioso)
  - `CsvRowValidator` - Validação e sanitização de dados (XSS, campos obrigatórios)
  - `CsvImportService` - Orquestração do processo de importação

#### Segurança Implementada
- **CsvFileValidator:**
  - Validação de MIME type real (não apenas extensão)
  - Detecção de padrões maliciosos (executáveis, PHP, JavaScript)
  - Proteção contra null bytes e caracteres de controle
  - Limite de linhas para prevenir ataques DoS

- **CsvRowValidator:**
  - Sanitização com `htmlspecialchars()` para prevenir XSS
  - Validação de padrões proibidos (`<script>`, `javascript:`, `onclick=`)
  - Limite de tamanho de campos (255 caracteres)
  - Validação e sanitização de valores monetários
  - `service_value` obrigatório quando `service_name` está preenchido

- **Transações:**
  - Rollback automático se >50% das linhas tiverem erros
  - Commit apenas quando maioria das importações for bem-sucedida

#### Formato CSV Esperado
```csv
name,email,phone,service_name,service_value
João Silva,joao@email.com,(82) 99999-1111,Eletricista,150.00
João Silva,joao@email.com,(82) 99999-1111,Encanador,200.00
Maria Costa,maria@email.com,(82) 88888-8888,Diarista,120.00
Ana Santos,ana@email.com,(82) 77776-5555,,
```

**Colunas:**
- `name`, `email`, `phone` → Obrigatórios
- `service_name` → Opcional (serviço criado automaticamente se não existir)
- `service_value` → Obrigatório se `service_name` estiver preenchido

#### Arquivos Criados
- `app/Lib/Service/CsvFileValidator.php` - Validação de segurança de arquivo
- `app/Lib/Service/CsvRowValidator.php` - Validação e sanitização de dados
- `app/Lib/Service/CsvImportService.php` - Orquestrador de importação

#### Arquivos Modificados
- `app/Controller/ProvidersController.php` - Action `import()` adicionada

---

### UX Improvements (`da70b15`, `d444291`, `c358490`, `6758f6e`, `adb7840`, `3635f5b`, `a8706cc`, `1649464` - 05/12/2025)

#### Adicionado - Interface de Listagem
- **Contador de resultados** na listagem de prestadores e serviços
  - Exibe total de registros encontrados ("X prestador(es) encontrado(s)")
  - Atualiza dinamicamente com filtros de busca

- **Banner de filtro ativo** quando há busca aplicada
  - Mostra termo pesquisado em destaque
  - Botão "Limpar filtro" para reset rápido
  - Responsivo: layout flexível para mobile

- **Empty states** para listas vazias
  - Mensagem amigável quando não há resultados
  - Ícone ilustrativo e texto orientativo

- **Busca inteligente por telefone**
  - Busca funciona independente do formato digitado
  - "82982136275" encontra "(82) 98213-6275"
  - Normalização de dígitos no backend

- **Busca por nome de serviço**
  - Campo de busca agora pesquisa também pelo serviço prestado
  - SQL otimizado com subquery para performance

#### Corrigido - Paginação
- **Paginação preservando parâmetros de busca**
  - Links "Anterior/Próximo" mantêm filtros aplicados
  - Corrigido merge de named params com query params
  - Contador de páginas correto com resultados filtrados

#### Corrigido - Formulários
- **Botão de submit no mobile** para busca
  - Adicionado botão com ícone de seta
  - Teclado móvel não bloqueava submissão

- **Formulário de cadastro** (add.ctp)
  - Corrigido HTML malformado (div extra)
  - Botão "Salvar Cadastro" funciona no primeiro clique

---

### Refatoração de Arquitetura (Suporte às melhorias de UX) (`6758f6e`, `adb7840`, `3635f5b` - 05/12/2025)

> **Contexto:** O `ProviderBusinessService` cresceu significativamente (~500 linhas)
> devido à implementação das melhorias de UX (busca avançada, paginação customizada,
> filtros dinâmicos). A refatoração em serviços menores foi uma necessidade natural
> para manter o código manutenível e garantir segurança nas queries SQL.

#### Arquitetura de Serviços (SRP)

**Antes:** `ProviderBusinessService` monolítico (~400 linhas)
- Misturava CRUD, queries, upload de foto e lógica de apresentação

**Depois:** Serviços especializados
```
ProviderBusinessService (Fachada - ~100 linhas)
├── ProviderQueryService   → Busca, filtros, ordenação, paginação
├── ProviderCrudService    → Create, Update, Delete
└── PhotoUploadService     → Upload e validação de imagens
```

#### Novos Arquivos Criados
- `app/Lib/Service/ProviderQueryService.php`
  - Busca otimizada com SQL e prepared statements
  - Subquery para busca por nome de serviço
  - Paginação manual com validação de página
  - Ordenação por valor (soma dos serviços)

- `app/Lib/Service/ProviderCrudService.php`
  - Operações de persistência com transações
  - Processamento de nome completo (first + last)
  - Integração com PhotoUploadService

- `app/Lib/Service/PhotoUploadService.php`
  - Validação de extensão e tamanho
  - Geração de nome único
  - Remoção segura de arquivos

#### Segurança
- **SQL Injection corrigido** na busca por telefone
  - Antes: concatenação direta de string
  - Depois: `$db->value()` para prepared statements

- **Sanitização de LIKE patterns**
  - Escape de caracteres especiais (%, _, \)

#### Controller Simplificado
- `ProvidersController` agora só faz:
  - Receber requisições HTTP
  - Delegar para serviços
  - Definir variáveis para views
  - Gerenciar Flash messages
  - Redirecionar

---

### Frontend UI Implementation (Fase 3) (`5781457`, `0970144`, `563bea3`, `d60c7aa`, `33177da`, `54c74f6`, `abf664e`, `b0212e4`, `33556ce`, `ef10fea`, `0af77d0`, `3a4983d` - 04/12/2025 - 05/12/2025)

#### Adicionado
- **Layout Bootstrap 5** (`5781457` - 2025-12-04)
  - Substituído layout padrão do CakePHP por Bootstrap 5
  - Fonte Inter e variáveis CSS customizadas
  - Navbar responsiva com branding
  - Removido `cake.generic.css` deprecado

- **Componentes de UI** (`0970144` - 2025-12-04)
  - `sidebar.ctp`: Navegação lateral com links para Prestadores e Serviços
  - `Flash/success.ctp`: Template de mensagem de sucesso estilizado
  - `Flash/error.ctp`: Template de mensagem de erro estilizado
  - Ícones Bootstrap Icons integrados

- **Views de Prestadores Estilizadas** (`563bea3` - 2025-12-04)
  - `index.ctp`: Listagem em cards com avatares, badges e coluna de serviços
  - `add.ctp`: Formulário moderno com dropzone de foto e campos dinâmicos
  - `edit.ctp`: Consistente com add, dados pré-populados
  - Labels e placeholders em Português Brasileiro

- **Rota da Página Inicial** (`f24234e` - 2025-12-04)
  - `/` agora redireciona para listagem de prestadores
  - Usuários aterrisam diretamente no CRUD principal

#### Corrigido
- **Carregamento de Serviços na Listagem** (`e1f38e7` - 2025-12-04)
  - `buildSearchConditions()` agora inclui `contain` para ProviderService.Service
  - Controller simplificado, removida duplicação de contain

---

### Refatoração: Modelo de Dados Corrigido (RF02) (`b758c2d`, `89fb0ca`, `7ef1dee`, `7ea721f`, `7b32c7a`, `70022a6`, `b8199bd`, `fbad7a4` - 2025-12-04)

> **Motivação:** A implementação inicial usava relação 1:N simples (Service pertence a Provider),
> mas os requisitos RF02 indicam que serviços são **tipos padronizados** que podem ser oferecidos
> por **múltiplos prestadores** com preços diferentes. A refatoração implementa corretamente
> a relação N:N com tabela pivô para atender aos requisitos do sistema.

#### Antes (incorreto)
```
providers (1) ──────> (N) services
                          ├── provider_id
                          ├── name
                          └── value
```

#### Depois (correto - RF02)
```
providers (N) <──── provider_services ────> (N) services
                    ├── provider_id              ├── name (catálogo)
                    ├── service_id               └── description
                    └── value (preço específico)
```

#### Alterações no Banco de Dados
- `services` → Agora é **Catálogo de Serviços** (Lista Mestre)
  - Removido: `provider_id`, `value`
  - Mantido: `id`, `name`, `description`, `created`, `modified`
- `provider_services` → **Nova tabela pivô**
  - `provider_id` (FK → providers)
  - `service_id` (FK → services)
  - `value` (preço específico do prestador)

#### Alterações nos Models
- `Service` → Simplificado para catálogo (hasMany ProviderService)
- `ProviderService` → **Novo Model** para tabela pivô
- `Provider` → hasMany ProviderService (antes: hasMany Service)

#### Alterações na Camada de Serviço
- `ProviderService.php` → Renomeado para `ProviderBusinessService.php`
  - Evita conflito de nome com o novo Model `ProviderService`
  - Gerencia Provider + vínculos via `saveAssociated()`
- `ServiceService.php` → Simplificado (catálogo independente)

#### Alterações nos Controllers
- `ProvidersController` → Carrega serviços do catálogo para dropdown
- `ServicesController` → Removida dependência de providers

#### Alterações nas Views
- `Providers/add|edit` → Dropdown de serviços + campo valor
- `Providers/index` → Exibe serviços vinculados na listagem
- `Providers/view` → Lista serviços com preços
- `Services/*` → Interface simplificada de catálogo

---

## [0.2.0] - 05/12/2025

### 📅 05/12/2025 - Dia 4: Frontend UI com Bootstrap 5

#### Adicionado
- **Layout Bootstrap 5** (`5781457`)
  - Navbar responsiva com hamburger menu para mobile
  - Container principal com card estilizado
  - Integração com Google Fonts (Inter)
  - CSS customizado em `webroot/css/style.css`

- **Navegação Lateral** (`0970144`)
  - Links ativos com destaque visual
  - Flash messages estilizadas como toast

- **Views de Prestadores** (`563bea3`)
  - Listagem com tabela responsiva e paginação
  - Foto do prestador com fallback para iniciais
  - Exibição de serviços e valores por prestador
  - Formulários de add/edit com Bootstrap 5

- **Views de Serviços** (`33177da`, `d60c7aa`)
  - Catálogo de serviços com cards modernos
  - Formulários estilizados para add/edit
  - Modal de importação CSV

- **Modal de Importação** (`54c74f6`)
  - Drag & drop para upload de arquivo CSV
  - Validação de formato no frontend
  - Feedback visual durante upload

- **Responsividade Mobile** (`abf664e`)
  - Telefone, serviços e valores visíveis no mobile
  - Ícones de ação (editar/excluir) inline no mobile
  - Tabela de serviços compacta para telas pequenas
  - Menu hamburger fecha ao clicar nos links

#### Alterado
- **Rotas** (`f24234e`)
  - Homepage redirecionada para `/providers`

#### Corrigido
- **Layout Corrompido** (`abf664e`)
  - Removido jQuery e Bootstrap JS duplicados
  - Corrigido tags `<body>` duplicadas

- **Edição de Prestadores** (`33556ce`, `ef10fea`)
  - Concatenação de first_name + last_name no edit
  - Validação de tipo para foto e valor do serviço

- **Estilos da Tabela** (`b0212e4`)
  - Font-size 12px nos headers
  - Removido text-uppercase

---

## [0.1.0] - 04/12/2025

---

### 📅 02/12/2025 - Dia 1: Infraestrutura

#### Adicionado
- **Docker Environment** (`abd6725` - 16:38)
  - Dockerfile com PHP 7.2, Apache e suporte a mcrypt
  - docker-compose.yml com MySQL 5.7 e volumes persistentes
  - Configuração otimizada para desenvolvimento local

- **CakePHP 2.10.24** (`2d842dc` - 16:41)
  - Estrutura completa do framework em `lib/Cake/`
  - Configurações padrão em `app/Config/`
  - Templates de console e bake

- **Schema do Banco de Dados** (`60032e9` - 17:03)
  - Tabela `providers` (id, name, email, phone, photo, created, modified)
  - Tabela `services` (id, provider_id, name, description, value, created, modified)
  - Relacionamento 1:N entre Provider e Service com CASCADE delete
  - Campos DECIMAL(10,2) para valores monetários

---

### 📅 03/12/2025 - Dia 2: CRUD e Documentação

#### Adicionado
- **Scaffold Providers e Services** (`299f092` - 20:14)
  - Controllers: `ProvidersController` e `ServicesController`
  - Models: `Provider` e `Service` com relacionamentos
  - Views: index, view, add, edit para ambas entidades
  - Fixtures para testes

- **Especificação Técnica (SDD)** (`7f45dd0` - 20:55)
  - Documento de Arquitetura em `docs/SPECIFICATION.md`
  - Diagrama de contexto Mermaid
  - Diagrama ER das entidades
  - Decisões arquiteturais (ADR)
  - Roadmap de evolução (V2-V4)

- **DevOps - .dockerignore e .gitignore** (`cc1794d` - 23:56)
  - Exclusão de arquivos Git, documentação, testes
  - Otimização de build do container
  - Padrões para IDEs (VS Code, PhpStorm)
  - Exclusão de cache e logs

---

### 📅 04/12/2025 - Dia 3: Arquitetura e Refinamentos

#### Adicionado
- **Service Layer - ProviderService** (`302c214` - 00:05)
  - `create()` - Criação com upload de foto
  - `update()` - Atualização com substituição de foto
  - `delete()` - Exclusão com remoção de arquivo
  - `findById()` - Busca por ID com serviços relacionados
  - `buildSearchConditions()` - Filtros de busca
  - `_processPhotoUpload()` - Processamento de upload com hash único

- **Service Layer - ServiceService** (`302c214` - 00:05)
  - `create()` - Criação com sanitização monetária
  - `update()` - Atualização de serviços
  - `delete()` - Exclusão de serviços
  - `findById()` - Busca por ID
  - `getProvidersList()` - Lista de prestadores para dropdown
  - `buildSearchConditions()` - Filtros de busca

- **Validações do Provider** (`03a74f5` - 00:06)
  - Nome obrigatório (2-255 caracteres)
  - Email único, formato válido, normalizado para lowercase
  - Telefone com regex flexível (com/sem parênteses no DDD)
  - Formatação automática para "XX XXXXX-XXXX"

- **Validações do Service** (`03a74f5` - 00:06)
  - Nome obrigatório (2-255 caracteres)
  - Descrição até 2000 caracteres
  - Valor monetário obrigatório e positivo
  - Provider obrigatório e existente no banco

#### Alterado
- **ProvidersController** (`03a74f5` - 00:06)
  - Delegação de lógica para `ProviderService`
  - Controller "thin" com apenas orquestração HTTP
  - Flash messages em português

- **ServicesController** (`03a74f5` - 00:06)
  - Delegação de lógica para `ServiceService`
  - Controller "thin" com apenas orquestração HTTP
  - Flash messages em português

- **AppModel Simplificado** (`03a74f5` - 00:06)
  - Remoção de métodos não utilizados
  - Apenas configurações base ($actsAs, $recursive)

- **Provider Model** (`03a74f5` - 00:06)
  - Validações completas com mensagens em PT-BR
  - Callback `beforeSave()` para normalização
  - Relacionamento hasMany com Service

- **Service Model** (`03a74f5` - 00:06)
  - Validações completas com mensagens em PT-BR
  - Callback `beforeSave()` para sanitização monetária
  - Relacionamento belongsTo com Provider

- **AppController** (`03a74f5` - 00:06)
  - Components: Flash, Session, Paginator, Security
  - Helpers: Html, Form, Flash
  - Headers de segurança (X-Frame-Options, X-Content-Type-Options)

#### Removido
- **Arquivos de Teste Auto-gerados** (`11a43e5` - 00:06)
  - `ProvidersControllerTest.php`, `ServicesControllerTest.php`, `ServiceTest.php`
  - Fixtures geradas automaticamente
  - Reservado para implementação manual futura

#### Corrigido
- **Upload de Foto** (`28228d5` - 00:07)
  - Formulários de Provider com `enctype="multipart/form-data"` correto

#### Adicionado
- **Validação Monetária Flexível** (`ab83f42` - 12:32)
  - Aceita tanto vírgula quanto ponto como separador decimal
  - Sanitização de formato brasileiro (R$ 1.234,56 → 1234.56)
  - Método `_sanitizeDecimalValue()` no Service Model

- **Atualização do Roadmap** (`cca7935` - 12:41)
  - Idiomas dos prestadores (V3)
  - API restrita para app do parceiro (V4)

- **Filtros de Busca** (`c5eeaeb` - 12:49)
  - Campo de pesquisa em `Providers/index.ctp`
  - Campo de pesquisa em `Services/index.ctp`
  - Busca por nome, email e telefone

- **Checklist de Progresso** (`aaeb7ee` - 12:54)
  - Fase 1: Infraestrutura ✓
  - Fase 2: Backend ✓
  - Fase 3: Frontend (pendente)
  - Fase 4: Importação CSV (pendente)
  - Fase 5: Documentação (em progresso)

---

## Histórico de Commits (ordem cronológica)

| Hash | Data/Hora | Tipo | Descrição |
|------|-----------|------|-----------|
| `e7e421d` | 02/12/2025 16:37 | docs | Instruções do desafio |
| `abd6725` | 02/12/2025 16:38 | build | Setup Docker PHP 7.2 + MySQL 5.7 |
| `2d842dc` | 02/12/2025 16:41 | chore | Instalação CakePHP 2.10.24 |
| `60032e9` | 02/12/2025 17:03 | feat | Schema do banco de dados |
| `299f092` | 03/12/2025 20:14 | feat | Scaffold CRUD via cake bake |
| `7f45dd0` | 03/12/2025 20:55 | docs | Especificação técnica (SDD) |
| `cc1794d` | 03/12/2025 23:56 | chore | .dockerignore e .gitignore |
| `302c214` | 04/12/2025 00:05 | refactor | Adiciona camada de serviços |
| `03a74f5` | 04/12/2025 00:06 | refactor | Delega lógica para service layer |
| `11a43e5` | 04/12/2025 00:06 | chore | Remove testes auto-gerados |
| `28228d5` | 04/12/2025 00:07 | fix | Formulários de upload de foto |
| `ab83f42` | 04/12/2025 12:32 | feat | Validação monetária flexível (vírgula/ponto) |
| `cca7935` | 04/12/2025 12:41 | docs | Atualiza roadmap com idiomas e API |
| `c5eeaeb` | 04/12/2025 12:49 | feat | Implementa filtros de busca nas listagens |
| `aaeb7ee` | 04/12/2025 12:54 | docs | Adiciona checklist de progresso |
| `f24234e` | 04/12/2025 | feat | Define providers index como homepage |
| `e1f38e7` | 04/12/2025 | fix | Adiciona contain em buildSearchConditions para services |
| `5781457` | 04/12/2025 | feat | Layout Bootstrap 5 com CSS customizado |
| `0970144` | 04/12/2025 | feat | Navegação lateral e flash messages |
| `563bea3` | 04/12/2025 | feat | Views de prestadores com Bootstrap 5 |
| `b758c2d` | 04/12/2025 | fix | Corrige modelo de dados para N:N (RF02) |
| `89fb0ca` | 04/12/2025 | fix | Adapta models para estrutura N:N |
| `7ef1dee` | 04/12/2025 | fix | Atualiza controllers para modelo de catálogo |
| `7ea721f` | 04/12/2025 | fix | Atualiza views de providers para seleção de serviços |
| `3b32c7a` | 04/12/2025 | fix | Atualiza views de services para interface de catálogo |
| `70022a6` | 04/12/2025 | refactor | Renomeia ProviderService para ProviderBusinessService |
| `b8199bd` | 04/12/2025 | docs | Atualiza documentação para refatoração N:N |
| `fbad7a4` | 04/12/2025 | merge | Integra refatoração de modelo de dados (N:N) |
| `60e5757` | 04/12/2025 | docs | Adiciona entradas de implementação UI no changelog |
| `3a4983d` | 05/12/2025 | docs | Adiciona entradas de implementação UI no changelog v0.2.0 |
| `0af77d0` | 05/12/2025 | merge | Finaliza implementação de UI frontend com Bootstrap 5 |
| `33177da` | 05/12/2025 | feat | Catálogo de serviços estilizado |
| `d60c7aa` | 05/12/2025 | feat | Formulários de serviços com Bootstrap 5 |
| `54c74f6` | 05/12/2025 | feat | Modal de importação CSV |
| `b0212e4` | 05/12/2025 | style | Ajustes de font-size e uppercase |
| `33556ce` | 05/12/2025 | fix | Concatenação de nome no edit |
| `ef10fea` | 05/12/2025 | merge | Integra fix de edição de prestadores |
| `abf664e` | 05/12/2025 | fix | Responsividade mobile e limpeza do layout |
| `da70b15` | 05/12/2025 | feat | Adiciona contador de resultados, banner de filtro e empty states |
| `d444291` | 05/12/2025 | fix | Corrige HTML malformado no formulário de cadastro |
| `c358490` | 05/12/2025 | style | Adiciona estilos para empty states e responsividade |
| `6758f6e` | 05/12/2025 | refactor | Extrai serviços do ProviderBusinessService para suportar melhorias de UX |
| `adb7840` | 05/12/2025 | refactor | Simplifica ProviderBusinessService como fachada |
| `3635f5b` | 05/12/2025 | refactor | Simplifica ProvidersController delegando para serviços |
| `a8706cc` | 05/12/2025 | docs | Atualiza changelog e especificação com melhorias de UX |
| `1649464` | 05/12/2025 | merge | Finaliza feature UX improvements |
| `9c4ff8c` | 06/12/2025 | feat | Implementa importação em massa de prestadores via CSV |
| `6138979` | 06/12/2025 | merge | Integra importação CSV de prestadores |
| `a70cebe` | 06/12/2025 | feat | Adiciona painel de métricas com gráficos |
| `533dade` | 06/12/2025 | feat | Implementa cache de métricas do dashboard |
| `6477ffa` | 06/12/2025 | merge | Integra cache de métricas do dashboard |
| `468672a` | 06/12/2025 | merge | Integra dashboard de métricas com gráficos |
| `8f820cb` | 06/12/2025 | fix | Corrige encoding UTF-8 na conexão com banco |
| `a34e01d` | 06/12/2025 | merge | Corrige encoding UTF-8 para suporte a acentos |
| `c6bc447` | 06/12/2025 | feat | Cria fluxo de CI para qualidade de código (linter) |
| `4398c45` | 06/12/2025 | chore | Adiciona target prod no Dockerfile e compose para avaliação local |
| `74d9fb4` | 06/12/2025 | docs | Atualiza CHANGELOG e SPECIFICATION com detalhes de CI/CD |
| `b20f4b1` | 06/12/2025 | merge | Merge branch 'feature/ci-cd' into develop |
| `3ee3e3c` | 07/12/2025 | refactor | Organiza serviços por feature e refatora ServiceService para padrão Facade |

