# Sistema de Gestão de Serviços - Seu João

Este projeto foi desenvolvido utilizando o framework **CakePHP 2.10** e **MySQL** para gerenciar prestadores de serviços.

## Requisitos

- PHP 7.4 ou superior
- MySQL
- Composer

## Instalação

1. Clone o repositório.
2. Instale as dependências (se necessário, embora o CakePHP venha junto):
   ```bash
   composer install
   ```
3. Configure o banco de dados:
   - O arquivo `app/Config/database.php` já está configurado para `localhost`, usuário `root` e senha vazia.
   - Banco de dados: `desafio_tecnico`
   - Se suas credenciais forem diferentes, edite `app/Config/database.php`.

4. Crie o banco de dados e as tabelas:
   - Importe o arquivo SQL (schema.sql) ou crie manualmente conforme a estrutura nos Models.

## Funcionalidades

### 1. Cadastro de Prestadores
- **Listagem com paginação** e ordenação (mais recentes primeiro).
- **Busca funcional**: Filtre prestadores por nome ou email.
- **Cadastro Completo**:
  - Upload de foto com pré-visualização.
  - Máscara e validação de telefone único.
  - Adição dinâmica de serviços (modal para novos serviços, select para existentes).
- **Edição e Exclusão**: Funcionalidades completas de CRUD.
- **Exportação CSV**: Botão para baixar lista completa de prestadores.

### 2. Importação Avançada
- Suporte a arquivos **CSV, XLS e XLSX** (Excel).
- Validação de extensão e tamanho máximo (25MB).
- **Upload via AJAX** com barra de progresso visual.
- Modal de sucesso client-side com redirecionamento automático.
- Utilização da biblioteca `PhpSpreadsheet` para leitura de planilhas.

### 3. Interface e UX (Bônus)
- **Design Moderno**: Tailwind CSS para estilização responsiva.
- **Ícones**: Integração com *Lucide Icons* para ícones vetoriais nítidos.
- **Flash Messages**: Notificações de sucesso/erro estilizadas.
- **Dashboard**: Cards com estatísticas:
  - Total de Prestadores
  - Total de Serviços
  - Média de Valor dos Serviços

## Tecnologias e Decisões
- **Design Moderno**: Utilizando Tailwind CSS para uma interface limpa e responsiva.
- **CakePHP 2**: Framework robusto MVC.
- **Git Flow**: Branches master/develop utilizadas.

## Autor
Desenvolvido para o desafio técnico da Doity.

## Video
https://youtu.be/G1Ox3O2SaiE

---
*Este projeto é uma solução para o [Teste Técnico FullStack da Doity](https://doity.notion.site/Teste-FullStack-b67c69625967440e97d48d475af366c7).*
