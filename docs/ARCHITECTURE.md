# 🏛️ Arquitetura do Projeto

Este documento descreve a arquitetura de alto nível do Sistema de Gestão de Prestadores, seus princípios de design e padrões adotados.

---

## 📚 Índice

1. [Filosofia de Design](#-filosofia-de-design)
2. [Visão em Camadas](#-visão-em-camadas)
3. [Estrutura de Módulos](#-estrutura-de-módulos)
4. [Padrões Adotados](#-padrões-adotados)
5. [Organização de Diretórios](#-organização-de-diretórios)
6. [Design System](#-design-system)

---

## 🎯 Filosofia de Design

O sistema segue princípios fundamentais que guiam todas as decisões arquiteturais:

### Separação de Responsabilidades

> **"Controllers são para requisições HTTP. Services são para lógica de negócio. Models são para acesso a dados."**

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Controllers     │     │   Services       │     │    Models       │
│                  │     │                  │     │                 │
│  - HTTP          │────>│  - Business      │────>│  - Database     │
│  - Validation    │     │  - Rules         │     │  - Relations    │
│  - Response      │     │  - Transactions  │     │  - Validation   │
└─────────────────┘     └─────────────────┘     └─────────────────┘
```

### Testabilidade

Toda lógica de negócio está em classes Service, facilitando:
- Testes unitários isolados
- Mocking simples
- Cobertura de código alta

### Consistência Arquitetural

Todos os serviços seguem o mesmo padrão:
- **Facade Pattern** para coordenação
- **Query Service** para consultas
- **CRUD Service** para operações de escrita

---

## 📐 Visão em Camadas

```
┌─────────────────────────────────────────────────────────────────┐
│                        CONTROLLERS                              │
│  Recebem requisições HTTP, validam entrada, delegam para       │
│  Services e formatam resposta.                                 │
│  Ex: ProvidersController, ServicesController                   │
└───────────────────────────┬─────────────────────────────────────┘
                            │ usa
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        SERVICES (Facade)                        │
│  Coordenam operações de negócio através de serviços            │
│  especializados. Interface simplificada para Controllers.      │
│  Ex: ProviderBusinessService, ServiceService                   │
└───────────────────────────┬─────────────────────────────────────┘
                            │ delega para
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                   SERVICES ESPECIALIZADOS                       │
│  Query Services: Busca, filtros, paginação                     │
│  CRUD Services: Create, Update, Delete                         │
│  Ex: ProviderQueryService, ServiceCrudService                   │
└───────────────────────────┬─────────────────────────────────────┘
                            │ usa
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                          MODELS                                  │
│  Acesso a dados, validações, relacionamentos.                  │
│  Ex: Provider, Service, ProviderService                        │
└───────────────────────────┬─────────────────────────────────────┘
                            │ acessa
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                       DATABASE (MySQL)                           │
│  Armazenamento persistente de dados                             │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📦 Estrutura de Módulos

O projeto é organizado em módulos funcionais independentes:

```
app/
├── Controller/           # 🎮 Controladores HTTP
│   ├── ProvidersController.php
│   ├── ServicesController.php
│   └── HomeController.php
│
├── Lib/Service/          # 🔧 Camada de Serviços
│   ├── Provider/         # Módulo de Prestadores
│   │   ├── ProviderBusinessService.php (Facade)
│   │   ├── ProviderQueryService.php
│   │   └── ProviderCrudService.php
│   ├── Service/          # Módulo de Catálogo
│   │   ├── ServiceService.php (Facade)
│   │   ├── ServiceQueryService.php
│   │   └── ServiceCrudService.php
│   ├── Csv/              # Módulo de Importação
│   │   ├── CsvImportService.php
│   │   ├── CsvFileValidator.php
│   │   └── CsvRowValidator.php
│   ├── PhotoUploadService.php (utilitário compartilhado)
│   └── DashboardService.php (utilitário compartilhado)
│
├── Model/                # 📊 Modelos de Dados
│   ├── Provider.php
│   ├── Service.php
│   └── ProviderService.php
│
└── View/                 # 🎨 Templates
    ├── Providers/
    ├── Services/
    └── Home/
```

### Responsabilidades por Módulo

| Módulo              | Responsabilidade                               |
| ------------------- | ---------------------------------------------- |
| `Provider/`         | Gestão completa de prestadores (CRUD, busca)  |
| `Service/`          | Gestão do catálogo de serviços                |
| `Csv/`              | Importação em massa via arquivo CSV           |
| `PhotoUploadService` | Upload e gerenciamento de imagens            |
| `DashboardService`  | Métricas e estatísticas do negócio            |

---

## 🎨 Padrões Adotados

### 1. Facade Pattern

Interface simplificada que coordena serviços especializados:

```php
// ProviderBusinessService.php (Facade)
class ProviderBusinessService {
    protected $_queryService;  // ProviderQueryService
    protected $_crudService;   // ProviderCrudService

    public function listWithFilters($params) {
        return $this->_queryService->listWithFilters($params);
    }

    public function create($data) {
        return $this->_crudService->create($data);
    }
}
```

**Benefícios:**
- Controllers não precisam conhecer múltiplos serviços
- Facilita manutenção e testes
- Consistência na interface

### 2. Service Layer Pattern

Separação clara entre lógica de negócio e apresentação:

```php
// Controller delega para Service
$result = $this->_providerService->create($this->request->data);

// Service processa lógica de negócio
if ($result['success']) {
    $this->Flash->success($result['message']);
}
```

**Benefícios:**
- Lógica reutilizável
- Controllers enxutos
- Fácil testabilidade

### 3. Query vs CRUD Separation

Separação de responsabilidades entre leitura e escrita:

- **Query Services:** Busca, filtros, paginação, ordenação
- **CRUD Services:** Criação, atualização, exclusão, transações

**Benefícios:**
- Código mais organizado
- Facilita otimizações específicas
- Melhor rastreabilidade

### 4. Transaction Management

Operações críticas envolvidas em transações:

```php
$dataSource = $this->_Provider->getDataSource();
$dataSource->begin();

try {
    // Operações múltiplas
    $this->_Provider->saveAssociated($data);
    $dataSource->commit();
} catch (Exception $e) {
    $dataSource->rollback();
}
```

---

## 📁 Organização de Diretórios

### Estrutura por Feature

Serviços organizados em diretórios por domínio de negócio:

```
app/Lib/Service/
├── Provider/          # Tudo relacionado a prestadores
├── Service/           # Tudo relacionado ao catálogo
├── Csv/               # Tudo relacionado a importação
├── PhotoUploadService.php  # Utilitário compartilhado
└── DashboardService.php     # Utilitário compartilhado
```

**Vantagens:**
- Fácil localização de código relacionado
- Escalabilidade (novos módulos não poluem a raiz)
- Clareza de responsabilidades

### Convenções de Nomenclatura

- **Facades:** `*BusinessService.php` ou `*ServiceService.php`
- **Query Services:** `*QueryService.php`
- **CRUD Services:** `*CrudService.php`
- **Validators:** `*Validator.php`

---

## 🎨 Design System

### Frontend Stack

- **Framework CSS:** Bootstrap 5
- **JavaScript:** jQuery
- **Estrutura:** Templates CakePHP (.ctp)

### Componentes Visuais

- **Tabelas:** Listagem com paginação
- **Formulários:** Validação client-side e server-side
- **Modais:** Bootstrap modals para ações
- **Flash Messages:** Feedback de operações

### Responsividade

- Layout adaptável
- Sidebar colapsável em telas pequenas
- Tabelas com scroll horizontal quando necessário

---

## 🔗 Próximos Documentos

- [DATAFLOW.md](./DATAFLOW.md) - Fluxo de dados reativo
- [REFERENCE.md](./REFERENCE.md) - Referência de componentes
- [SPECIFICATION.md](./SPECIFICATION.md) - Especificação completa

