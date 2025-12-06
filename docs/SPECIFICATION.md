# Especificação do Projeto: Sistema de Gestão do Seu João

## 1. Visão do Produto e Negócio

### 1.1 O Problema (Business Case)

A empresa "Serviços Gerais do Seu João" enfrenta gargalos operacionais
devido ao gerenciamento manual (caderno) de sua base de prestadores.
Isso resulta em:

-   **Lentidão no atendimento:** Dificuldade em encontrar prestadores
    qualificados rapidamente.\
-   **Perda de dados:** Risco físico e falta de backup das informações
    de contato e valores.\
-   **Despadronização:** Cada serviço anotado de uma forma, dificultando
    a análise de preços.

### 1.2 A Solução Proposta

Uma plataforma web centralizada para Gestão de Capital Humano
Terceirizado, focada em agilidade de consulta e integridade dos dados. O
sistema digitaliza o fluxo de cadastro e permite busca instantânea,
servindo como a "fonte única da verdade" para a operação da empresa.

------------------------------------------------------------------------

## 2. Arquitetura da Solução

### 2.1 Diagrama de Contexto

O sistema opera como uma aplicação monolítica MVC containerizada,
garantindo portabilidade e facilidade de implantação.

``` mermaid
graph LR
    User["Administrador (Seu João)"] -- HTTPS --> WebServer["Apache + CakePHP App"]
    WebServer -- "Leitura/Escrita" --> DB[("MySQL Database")]
    WebServer -- "Armazenamento" --> FileSys["Volume de Uploads"]
```

### 2.2 Stack Tecnológico e Decisões (ADR - Architectural Decision Records)

| **Decisão**           | **Tecnologia Escolhida** | **Justificativa Arquitetural** |
|-----------------------|---------------------------|--------------------------------|
| **Framework Backend** | CakePHP 2.10.x            | **Conformidade Legada:** Requisito estrito do cliente. |
| **Banco de Dados**    | MySQL 5.7                 | **Compatibilidade:** Versão LTS robusta, perfeitamente alinhada com o ORM do CakePHP 2. |
| **Ambiente**          | Docker & Compose          | **Reprodutibilidade:** Elimina o problema de "funciona na minha máquina". Isola as dependências de versões antigas do PHP (7.2). |
| **Frontend**          | Bootstrap 5 + jQuery       | **Eficiência de UI:** Permite fidelidade ao design Figma responsivo sem a complexidade de build tools (Webpack) desnecessárias para este escopo. |


------------------------------------------------------------------------

## 3. Especificação de Design e UX

### 3.1 Personas e Jornadas

**Persona Primária:** Seu João (Administrador).\
**Perfil:** Baixa familiaridade com softwares complexos. Gosta de
clareza e botões grandes.

**Jornada Crítica:**\
Cliente liga → Abre sistema → Busca "Eletricista" → Vê lista com fotos e
preços → Contata o prestador.

### 3.2 Diretrizes de Interface (Figma)

-   Hierarquia visual com uso de Cards\
-   Feedback de sistema via mensagens Flash\
-   Acessibilidade com labels claros e validação visual

------------------------------------------------------------------------

## 4. Modelagem de Dados

### 4.1 Entidades Principais

A modelagem utiliza uma relação N:N entre Prestadores e Serviços através de uma tabela pivô,
permitindo que múltiplos prestadores ofereçam o mesmo tipo de serviço com preços diferentes.

-   **Providers (Prestadores):** Dados pessoais + foto do perfil.
-   **Services (Catálogo):** Lista mestre de tipos de serviço disponíveis.
-   **Provider_Services (Pivô):** Vincula prestador ao serviço com seu preço específico.

``` mermaid
erDiagram
    PROVIDERS {
        INT id PK "AUTO_INCREMENT"
        VARCHAR name
        VARCHAR email
        VARCHAR phone
        VARCHAR photo
        DATETIME created
        DATETIME modified
    }

    SERVICES {
        INT id PK "AUTO_INCREMENT"
        VARCHAR name "Tipo de serviço"
        TEXT description
        DATETIME created
        DATETIME modified
    }

    PROVIDER_SERVICES {
        INT id PK "AUTO_INCREMENT"
        INT provider_id FK
        INT service_id FK
        DECIMAL value "Preço do prestador"
        DATETIME created
        DATETIME modified
    }

    PROVIDERS ||--o{ PROVIDER_SERVICES : "oferece"
    SERVICES ||--o{ PROVIDER_SERVICES : "é oferecido por"
```

### 4.2 Dicionário de Dados Simplificado

-   **providers.photo:** Caminho relativo armazenado no volume do servidor.
-   **services:** Catálogo de tipos de serviço (ex: "Eletricista", "Encanador").
-   **provider_services.value:** `DECIMAL(10,2)` - preço específico do prestador para o serviço.

------------------------------------------------------------------------

## 5. Requisitos Funcionais e Regras de Negócio

### RF01 -- Gestão de Prestadores

- **Cadastro:** Deve permitir upload de imagens (JPG/PNG). O sistema deve renomear o arquivo (hash único) para evitar conflitos.
- **Validação:** Email deve ser único no sistema. Telefone deve seguir máscara padrão.
- **Serviços:** Ao cadastrar um prestador, selecionar serviços do catálogo e definir o preço de cada um.

### RF02 -- Catálogo de Serviços

- **Lista Mestre:** Serviços são tipos padronizados que podem ser oferecidos por múltiplos prestadores.
- **Independência:** O catálogo existe independentemente dos prestadores.
- **Precificação:** O valor é definido por prestador na tabela pivô (provider_services).

### RF03 -- Importação em Massa

- **Entrada:** Arquivo CSV padronizado.
- **Processamento:** O sistema valida linha a linha. Em caso de erro parcial, as linhas válidas são importadas e as inválidas são reportadas.
- **Rollback:** Se mais de 50% das linhas tiverem erros, a importação é cancelada.
- **Múltiplos Serviços:** Permite adicionar vários serviços ao mesmo prestador.

##### Formato do Arquivo CSV

```csv
name,email,phone,service_name,service_value
João Silva,joao@email.com,(82) 99999-1111,Eletricista,150.00
João Silva,joao@email.com,(82) 99999-1111,Encanador,200.00
Maria Costa,maria@email.com,82988887777,Diarista,120.50
Ana Santos,ana@email.com,(82) 77776-5555,
```

| Coluna | Obrigatório | Descrição |
|--------|-------------|-----------|
| `name` | ✅ Sim | Nome completo do prestador |
| `email` | ✅ Sim | Email único (validado). Repetir para múltiplos serviços. |
| `phone` | ✅ Sim | Telefone (formato livre) |
| `service_name` | ❌ Não | Nome do serviço (criado automaticamente se não existir no catálogo) |
| `service_value` | ⚠️ Condicional | Obrigatório se `service_name` estiver preenchido |

**Limitações de Segurança:**
- Tamanho máximo: 25MB
- Máximo de linhas: 1.000
- Codificação: UTF-8 recomendado
- Delimitador: vírgula (`,`) ou ponto-e-vírgula (`;`) - auto-detectado

#### Arquitetura de Segurança da Importação

```
CsvImportService (Orquestrador)
├── CsvFileValidator     → Valida arquivo (extensão, MIME, tamanho, conteúdo)
├── CsvRowValidator      → Valida e sanitiza dados (XSS, campos, formatos)
└── Provider/Service     → Acesso direto aos Models (transação em lote)
```

**CsvFileValidator - Proteções:**
- Validação de extensão E MIME type real
- Detecção de padrões maliciosos (PHP, EXE, JavaScript)
- Limite de linhas para prevenir DoS
- Verificação de caracteres nulos/controle

**CsvRowValidator - Proteções:**
- Sanitização com `htmlspecialchars()` (XSS)
- Validação de padrões proibidos (`<script>`, `onclick=`)
- Limite de tamanho por campo (255 caracteres)
- Validação de formato de email e valor monetário

------------------------------------------------------------------------

## 6. Planejamento de Evolução (Roadmap)

Embora fora do escopo do MVP (Minimum Viable Product), a arquitetura foi preparada para futuras expansões:

- **Módulo de Ordens de Serviço (V2):** Registrar quais serviços foram efetivamente contratados pelos clientes finais.
- ~~**Dashboard de Métricas (V2):**~~ ✅ **Implementado!** Gráficos de serviços por prestadores e métricas em tempo real.
- **Internacionalização e Idiomas (V3):** * Implementação de atributo "Idiomas Falados" (Relacionamento N:N) para os prestadores.
    * Objetivo: Permitir que clientes não-lusófonos (turistas, expatriados) filtrem prestadores que falam idiomas além do português.
- **API REST e App do Parceiro (V4):** * Desenvolvimento de API para um aplicativo restrito aos prestadores já homologados.
    * Objetivo: Permitir que os prestadores recebam notificações de novos serviços e atualizem seu status de disponibilidade em tempo real, sem permitir o auto-cadastro externo (mantendo a curadoria centralizada no admin).

---

## 7. Checklist de Progresso

### 🏗️ Fase 1: Infraestrutura e Configuração
- [x] Configuração do Docker (PHP 7.2 + Apache + MySQL 5.7).
- [x] Instalação do CakePHP 2.10.24.
- [x] Configuração de permissões de pasta (tmp/logs).
- [x] Definição da Arquitetura MVC e Padrões de Projeto.
- [x] Modelagem do Banco de Dados (Schema SQL).

### ⚙️ Fase 2: Backend e Regras de Negócio
- [x] **CRUD Prestadores:**
    - [x] Listagem com Paginação.
    - [x] Cadastro e Edição de dados pessoais.
    - [x] Upload de Foto (Renomeação e movimentação de arquivo).
    - [x] Exclusão lógica ou física (Cascade).
- [x] **CRUD Serviços:**
    - [x] Associação com Prestador (1:N).
    - [x] Validação Monetária Flexível (Aceitar vírgula e ponto).
    - [x] Sanitização de dados (`beforeSave`).
- [x] **Funcionalidades Globais:**
    - [x] Busca/Filtro de Prestadores por nome/email.
    - [x] Busca por telefone (independente do formato).
    - [x] Busca por nome do serviço prestado.
    - [x] Mensagens de Feedback (Flash Messages).
- [x] **Refatoração de Serviços:**
    - [x] `ProviderQueryService` - Extraído para busca e paginação.
    - [x] `ProviderCrudService` - Extraído para operações CRUD.
    - [x] `PhotoUploadService` - Extraído para upload de imagens.
    - [x] `ProviderBusinessService` - Fachada simplificada.

### 🎨 Fase 3: Frontend e UI
- [x] **Fundação:**
    - [x] Limpeza do CSS nativo do CakePHP.
    - [x] Instalação/Link do Bootstrap 5.
    - [x] Definição do Layout Principal (`default.ctp`) com Sidebar.
- [x] **Componentes:**
    - [x] Sidebar de Navegação responsiva.
    - [x] Estilização da Tabela de Listagem (Avatares, Badges).
    - [x] Estilização de Formulários (Inputs, Botões).
    - [x] Modal de Importação (Frontend).
- [x] **UX Improvements:**
    - [x] Contador de resultados na listagem.
    - [x] Banner de filtro ativo com opção de limpar.
    - [x] Empty states para listas vazias.
    - [x] Paginação preservando parâmetros de busca.
    - [x] Botão de submit no mobile para busca.

### 🚀 Fase 4: Funcionalidades Avançadas (Atividade 02)
- [x] **Importação CSV:**
    - [x] Upload de arquivo `.csv`.
    - [x] Parsing e Leitura do arquivo (auto-detecção de delimitador).
    - [x] Validação de dados do CSV (email duplicado, campos obrigatórios).
    - [x] Inserção em massa no Banco de Dados (transacional).
    - [x] Criação automática de serviços não existentes.
    - [x] Mensagem de feedback com erros detalhados.

### 🏁 Fase 5: Documentação e Entrega
- [x] Documentação Técnica (SPECIFICATION.md).
- [ ] Documentação de Instalação (README.md final).
- [ ] Gravação do Vídeo Explicativo (Loom/YouTube).
- [ ] Revisão Final de Código.

### ⭐ Fase Bônus: Diferenciais Competitivos
- [x] **Dashboard de Métricas:**
    - [x] Total de prestadores cadastrados.
    - [x] Total de tipos de serviços no catálogo.
    - [x] Ticket médio dos serviços.
    - [x] Serviço mais popular (com mais prestadores).
    - [x] Faixa de preços (mínimo e máximo).
    - [x] Últimos prestadores cadastrados com serviços.
    - [x] Gráfico de barras (Chart.js) - Prestadores por serviço.
- [x] **Cache de Métricas (Performance):**
    - [x] Cache de 15 minutos para reduzir queries ao banco.
    - [x] Invalidação automática ao alterar dados.
    - [x] Configuração via `core.php` do CakePHP.
