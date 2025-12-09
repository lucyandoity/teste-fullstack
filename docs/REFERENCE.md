# 📋 API Reference

Referência rápida de todas as interfaces, serviços, models e controllers do Sistema de Gestão de Prestadores.

---

## 📚 Índice

1. [Services - Provider](#-services---provider)
2. [Services - Service](#-services---service)
3. [Services - CSV](#-services---csv)
4. [Services - Utilitários](#-services---utilitários)
5. [Models](#-models)
6. [Controllers](#-controllers)

---

## 🔧 Services - Provider

### ProviderBusinessService (Facade)

**Localização:** `app/Lib/Service/Provider/ProviderBusinessService.php`

**Descrição:** Serviço de fachada que coordena operações de prestadores.

```php
class ProviderBusinessService {
    public function listWithFilters($queryParams = array()): array
    public function findById($id): array|false
    public function create($data): array
    public function update($id, $data): array
    public function delete($id): array
}
```

**Métodos:**

| Método | Parâmetros | Retorno | Descrição |
|--------|-----------|---------|-----------|
| `listWithFilters` | `$queryParams` (array) | `array` com `providers`, `totalCount`, `paging` | Lista prestadores com filtros, ordenação e paginação |
| `findById` | `$id` (int) | `array\|false` | Busca prestador pelo ID |
| `create` | `$data` (array) | `array` com `success`, `message`, `id` ou `validationErrors` | Cria novo prestador |
| `update` | `$id` (int), `$data` (array) | `array` com `success`, `message` ou `validationErrors` | Atualiza prestador existente |
| `delete` | `$id` (int) | `array` com `success`, `message` | Remove prestador |

### ProviderQueryService

**Localização:** `app/Lib/Service/Provider/ProviderQueryService.php`

**Descrição:** Serviço responsável por consultas e listagem de prestadores.

```php
class ProviderQueryService {
    public function listWithFilters($queryParams = array()): array
    public function findById($id): array|false
}
```

**Parâmetros de `listWithFilters`:**

- `search` (string): Termo de busca (nome, email, telefone ou serviço)
- `sort` (string): Campo para ordenação (`name`, `email`, `value`)
- `direction` (string): Direção (`asc`, `desc`)
- `page` (int): Número da página

### ProviderCrudService

**Localização:** `app/Lib/Service/Provider/ProviderCrudService.php`

**Descrição:** Serviço responsável por operações CRUD de prestadores.

```php
class ProviderCrudService {
    public function create($data): array
    public function update($id, $data): array
    public function delete($id): array
}
```

**Estrutura de `$data` para create/update:**

```php
array(
    'Provider' => array(
        'first_name' => 'João',
        'last_name' => 'Silva',
        'email' => 'joao@email.com',
        'phone' => '(82) 99999-9999',
        'photo' => 'arquivo .png |.jpg |.jpeg'
    ),
    'ProviderService' => array(
        array(
            'service_id' => 1,
            'value' => '150.00'
        ),
        // ... mais serviços
    )
)
```

---

## 🔧 Services - Service

### ServiceService (Facade)

**Localização:** `app/Lib/Service/Service/ServiceService.php`

**Descrição:** Serviço de fachada que coordena operações de serviços do catálogo.

```php
class ServiceService {
    public function findById($id): array|false
    public function buildSearchConditions($queryParams = array()): array
    public function create($data): array
    public function update($id, $data): array
    public function delete($id): array
}
```

**Métodos:**

| Método | Parâmetros | Retorno | Descrição |
|--------|-----------|---------|-----------|
| `findById` | `$id` (int) | `array\|false` | Busca serviço pelo ID |
| `buildSearchConditions` | `$queryParams` (array) | `array` (configurações Paginator) | Constrói condições de busca |
| `create` | `$data` (array) | `array` com `success`, `message`, `id` | Cria novo serviço |
| `update` | `$id` (int), `$data` (array) | `array` com `success`, `message` | Atualiza serviço |
| `delete` | `$id` (int) | `array` com `success`, `message` | Remove serviço |

### ServiceQueryService

**Localização:** `app/Lib/Service/Service/ServiceQueryService.php`

**Descrição:** Serviço responsável por consultas de serviços.

```php
class ServiceQueryService {
    public function findById($id): array|false
    public function buildSearchConditions($queryParams = array()): array
}
```

### ServiceCrudService

**Localização:** `app/Lib/Service/Service/ServiceCrudService.php`

**Descrição:** Serviço responsável por operações CRUD de serviços.

```php
class ServiceCrudService {
    public function create($data): array
    public function update($id, $data): array
    public function delete($id): array
}
```

---

## 🔧 Services - CSV

### CsvImportService

**Localização:** `app/Lib/Service/Csv/CsvImportService.php`

**Descrição:** Serviço de orquestração para importação de prestadores via CSV.

```php
class CsvImportService {
    public function import($file): array
}
```

**Parâmetros:**

- `$file` (array): Dados do arquivo no formato `$_FILES`

**Retorno:**

```php
array(
    'success' => true|false,
    'message' => 'Mensagem de resultado',
    'stats' => array(
        'total' => 10,
        'imported' => 8,
        'skipped' => 2,
        'services_created' => 3
    ),
    'errors' => array(
        array('line' => 5, 'message' => 'Erro...'),
        // ...
    )
)
```

**Formato CSV esperado:**

```csv
name,email,phone,service_name,service_value
João Silva,joao@email.com,(82) 99999-1111,Eletricista,150.00
João Silva,joao@email.com,(82) 99999-1111,Encanador,200.00
```

### CsvFileValidator

**Localização:** `app/Lib/Service/Csv/CsvFileValidator.php`

**Descrição:** Validação de segurança do arquivo CSV.

```php
class CsvFileValidator {
    public function validate($file): array
}
```

**Validações:**
- Extensão (.csv)
- MIME type
- Tamanho máximo (25MB)
- Magic bytes (prevenção de arquivos disfarçados)
- Número máximo de linhas (1000)

### CsvRowValidator

**Localização:** `app/Lib/Service/Csv/CsvRowValidator.php`

**Descrição:** Validação e sanitização de dados de linha do CSV.

```php
class CsvRowValidator {
    public function validate($data, $lineNumber = 0): array
    public function sanitizeMonetaryValue($value): float
    public function decodeField($field): string
}
```

**Validações:**
- Campos obrigatórios (name, email, phone)
- Formato de email
- Tamanho máximo de campos (255 caracteres)
- Padrões maliciosos (XSS, code injection)
- Valor monetário (se service_name preenchido)

---

## 🔧 Services - Utilitários

### PhotoUploadService

**Localização:** `app/Lib/Service/PhotoUploadService.php`

**Descrição:** Upload e gerenciamento de fotos.

```php
class PhotoUploadService {
    public function upload($file): array
    public function validate($file): array
    public function remove($photoPath): bool
}
```

**Retorno de `upload`:**

```php
array(
    'success' => true|false,
    'path' => 'uploads/photo_abc123.jpg', // ou null
    'error' => 'Mensagem de erro' // se success = false
)
```

**Extensões permitidas:** jpg, jpeg, png, gif
**Tamanho máximo:** 5MB

### DashboardService

**Localização:** `app/Lib/Service/DashboardService.php`

**Descrição:** Métricas e estatísticas do sistema.

```php
class DashboardService {
    public function getMetrics($forceRefresh = false): array
    public function invalidateCache(): bool
}
```

**Retorno de `getMetrics`:**

```php
array(
    'total_providers' => 50,
    'total_services_types' => 15,
    'avg_ticket' => 175.50,
    'top_service' => 'Eletricista',
    'price_range' => array('min' => 100.00, 'max' => 500.00),
    'recent_providers' => array(...),
    'services_chart_data' => array(
        'labels' => array('Eletricista', 'Encanador', ...),
        'data' => array(10, 8, ...)
    )
)
```

**Cache:** 15 minutos (configurável em `app/Config/core.php`)

---

## 📊 Models

### Provider

**Localização:** `app/Model/Provider.php`

**Relacionamentos:**

```php
$hasMany = array(
    'ProviderService' => array(
        'dependent' => true // CASCADE delete
    )
)
```

**Validações:**

- `name`: obrigatório, 3-255 caracteres
- `email`: obrigatório, formato válido, único
- `phone`: obrigatório, formato brasileiro

**Callbacks:**

- `beforeSave`: Sanitiza telefone e normaliza email

### Service

**Localização:** `app/Model/Service.php`

**Relacionamentos:**

```php
$hasMany = array(
    'ProviderService' => array(
        'dependent' => true
    )
)
```

**Validações:**

- `name`: obrigatório, 2-255 caracteres
- `description`: opcional, máximo 5000 caracteres

### ProviderService

**Localização:** `app/Model/ProviderService.php`

**Relacionamentos:**

```php
$belongsTo = array(
    'Provider',
    'Service'
)
```

**Validações:**

- `service_id`: obrigatório, numérico
- `value`: obrigatório, formato monetário (aceita vírgula ou ponto)

**Callbacks:**

- `beforeSave`: Converte vírgula para ponto no valor monetário

---

## 🎮 Controllers

### ProvidersController

**Localização:** `app/Controller/ProvidersController.php`

**Actions:**

| Action | Método HTTP | Descrição |
|--------|-------------|-----------|
| `index` | GET | Lista prestadores com busca e paginação |
| `view` | GET | Exibe detalhes de um prestador |
| `add` | GET, POST | Formulário de cadastro |
| `edit` | GET, POST, PUT | Formulário de edição |
| `delete` | POST, DELETE | Remove prestador |
| `import` | POST | Importa prestadores via CSV |

**Componentes utilizados:**

- `Paginator`: Paginação de resultados
- `Flash`: Mensagens de feedback

### ServicesController

**Localização:** `app/Controller/ServicesController.php`

**Actions:**

| Action | Método HTTP | Descrição |
|--------|-------------|-----------|
| `index` | GET | Lista serviços com busca e paginação |
| `view` | GET | Exibe detalhes de um serviço |
| `add` | GET, POST | Formulário de cadastro |
| `edit` | GET, POST, PUT | Formulário de edição |
| `delete` | POST, DELETE | Remove serviço |

### HomeController

**Localização:** `app/Controller/HomeController.php`

**Actions:**

| Action | Método HTTP | Descrição |
|--------|-------------|-----------|
| `index` | GET | Dashboard com métricas |

---

## 📝 Estruturas de Dados Padronizadas

### Retorno de Operações CRUD

```php
// Sucesso
array(
    'success' => true,
    'message' => 'Operação realizada com sucesso.',
    'id' => 123 // opcional, apenas em create
)

// Erro
array(
    'success' => false,
    'message' => 'Mensagem de erro',
    'validationErrors' => array( // opcional
        'field' => array('Erro de validação')
    )
)
```

### Retorno de Listagem

```php
array(
    'providers' => array(...), // ou 'services'
    'totalCount' => 50,
    'paging' => array(
        'page' => 1,
        'current' => 6,
        'count' => 50,
        'prevPage' => false,
        'nextPage' => true,
        'pageCount' => 9,
        'limit' => 6
    )
)
```

---

## 🔗 Links Relacionados

- [ARCHITECTURE.md](./ARCHITECTURE.md) - Visão arquitetural
- [DATAFLOW.md](./DATAFLOW.md) - Fluxos de dados
- [SPECIFICATION.md](./SPECIFICATION.md) - Especificação completa
 - [EXPERIENCE_REPORT.md](./EXPERIENCE_REPORT.md) - Relatório de experiência do candidato (decisões técnicas e aprendizados)

