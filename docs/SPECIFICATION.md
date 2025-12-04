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

A modelagem foi desenhada para atender à relação 1:N (Um Prestador possui N Serviços), otimizando a leitura e a especificidade dos valores cobrados por cada profissional.

-   **Providers (Prestadores):** Dados pessoais imutáveis + foto do
    perfil.\
-   **Services (Serviços):** Especialização, descrição e valor de cada
    prestador.

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
        INT provider_id FK
        VARCHAR name
        TEXT description
        DECIMAL value
        DATETIME created
        DATETIME modified
    }

    PROVIDERS ||--o{ SERVICES : "possui"
```

### 4.2 Dicionário de Dados Simplificado

-   **providers.photo:** Caminho relativo armazenado no volume do
    servidor.\
-   **services.value:** `DECIMAL(10,2)` garantindo precisão monetária.

------------------------------------------------------------------------

## 5. Requisitos Funcionais e Regras de Negócio

### RF01 -- Gestão de Prestadores

- **Cadastro:** Deve permitir upload de imagens (JPG/PNG). O sistema deve renomear o arquivo (hash único) para evitar conflitos.
- **Validação:** Email deve ser único no sistema. Telefone deve seguir máscara padrão.

### RF02 -- Catálogo de Serviços

- **Associação:** Um serviço só pode existir se vinculado a um prestador existente (ON DELETE CASCADE).
- **Precificação**: O valor é obrigatório e deve ser tratado numericamente.

### RF03 -- Importação em Massa

- **Entrada:** Arquivo CSV padronizado.
- **Processamento:** O sistema deve validar linha a linha. Em caso de erro parcial, o sistema deve informar qual linha falhou ou rejeitar o lote (transacional).

------------------------------------------------------------------------

## 6. Planejamento de Evolução (Roadmap)

Embora fora do escopo do MVP (Minimum Viable Product), a arquitetura foi preparada para futuras expansões:

- **Módulo de Ordens de Serviço (V2):** Registrar quais serviços foram efetivamente contratados pelos clientes finais.
- **Dashboard de Métricas (V2):** Gráficos de "Serviços mais procurados" e "Prestadores mais ativos".
- **Internacionalização e Idiomas (V3):** * Implementação de atributo "Idiomas Falados" (Relacionamento N:N) para os prestadores.
    * Objetivo: Permitir que clientes não-lusófonos (turistas, expatriados) filtrem prestadores que falam idiomas além do português.
- **API REST e App do Parceiro (V4):** * Desenvolvimento de API para um aplicativo restrito aos prestadores já homologados.
    * Objetivo: Permitir que os prestadores recebam notificações de novos serviços e atualizem seu status de disponibilidade em tempo real, sem permitir o auto-cadastro externo (mantendo a curadoria centralizada no admin).

---

## 10. Checklist de Progresso

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
    - [ ] Mensagens de Feedback (Flash Messages).

### 🎨 Fase 3: Frontend e UI
- [ ] **Fundação:**
    - [ ] Limpeza do CSS nativo do CakePHP.
    - [ ] Instalação/Link do Bootstrap 5.
    - [ ] Definição do Layout Principal (`default.ctp`) com Sidebar.
- [ ] **Componentes:**
    - [ ] Sidebar de Navegação responsiva.
    - [ ] Estilização da Tabela de Listagem (Avatares, Badges).
    - [ ] Estilização de Formulários (Inputs, Botões).
    - [ ] Modal de Importação (Frontend).

### 🚀 Fase 4: Funcionalidades Avançadas (Atividade 02)
- [ ] **Importação CSV:**
    - [ ] Upload de arquivo `.csv`.
    - [ ] Parsing e Leitura do arquivo.
    - [ ] Validação de dados do CSV.
    - [ ] Inserção em massa no Banco de Dados.

### 🏁 Fase 5: Documentação e Entrega
- [x] Documentação Técnica (SPECIFICATION.md).
- [ ] Documentação de Instalação (README.md final).
- [ ] Gravação do Vídeo Explicativo (Loom/YouTube).
- [ ] Revisão Final de Código.
