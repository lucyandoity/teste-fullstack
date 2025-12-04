# Changelog

Todas as mudanças notáveis deste projeto estão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [Unreleased]

### Refatoração: Modelo de Dados Corrigido (RF02)

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

## [0.1.0] - 2025-12-04

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
| `e7e421d` | 02/12 16:37 | docs | Instruções do desafio |
| `abd6725` | 02/12 16:38 | build | Setup Docker PHP 7.2 + MySQL 5.7 |
| `2d842dc` | 02/12 16:41 | chore | Instalação CakePHP 2.10.24 |
| `60032e9` | 02/12 17:03 | feat | Schema do banco de dados |
| `299f092` | 03/12 20:14 | feat | Scaffold CRUD via cake bake |
| `7f45dd0` | 03/12 20:55 | docs | Especificação técnica (SDD) |
| `cc1794d` | 03/12 23:56 | chore | .dockerignore e .gitignore |
| `302c214` | 04/12 00:05 | refactor | Adiciona camada de serviços |
| `03a74f5` | 04/12 00:06 | refactor | Delega lógica para service layer |
| `11a43e5` | 04/12 00:06 | chore | Remove testes auto-gerados |
| `28228d5` | 04/12 00:07 | fix | Formulários de upload de foto |
| `ab83f42` | 04/12 12:32 | feat | Validação monetária flexível (vírgula/ponto) |
| `cca7935` | 04/12 12:41 | docs | Atualiza roadmap com idiomas e API |
| `c5eeaeb` | 04/12 12:49 | feat | Implementa filtros de busca nas listagens |
| `aaeb7ee` | 04/12 12:54 | docs | Adiciona checklist de progresso |

