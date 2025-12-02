# Especificação do Projeto - Sistema de Gestão do Seu João

## 1. Visão Geral
Desenvolvimento de um sistema web para gestão de prestadores de serviços terceirizados. O objetivo é substituir o controle manual (caderno) por uma solução digital que facilite a busca, cadastro e organização das informações.

## 2. Personas
* **Seu João (Administrador):** Dono da empresa. Não possui alto letramento digital. Precisa de interfaces limpas, botões claros e fluxos diretos. Seu objetivo principal é saber "quem faz o quê" rapidamente.

## 3. Histórias de Usuário
* **US01 - Gestão de Prestadores:** "Como administrador, quero cadastrar, editar, visualizar e excluir prestadores (com foto), para manter a base atualizada."
* **US02 - Busca de Serviços:** "Como administrador, quero buscar prestadores e filtrar por serviços, para atender rapidamente a demanda de um cliente."
* **US03 - Importação de Dados:** "Como administrador, quero importar uma lista de serviços via CSV, para não precisar digitar tudo manualmente."

## 4. Requisitos Funcionais
* **RF01:** O sistema deve permitir o CRUD (Create, Read, Update, Delete) de Prestadores.
* **RF02:** O cadastro do prestador deve conter: Nome, Telefone, Email, Foto e Associação aos serviços que presta.
* **RF03:** As informações do serviço associado devem conter: Nome, Descrição e Valor.
* **RF04:** A listagem de prestadores deve possuir paginação.
* **RF05:** O sistema deve permitir upload de arquivos CSV para importação em massa de serviços/clientes.

## 5. Requisitos Não Funcionais
* **RNF01 (Stack):** Backend em CakePHP 2, Banco de dados MySQL.
* **RNF02 (Frontend):** HTML, CSS, JavaScript, jQuery. Fidelidade ao layout Figma.
* **RNF03 (Ambiente):** O projeto deve ser containerizado (Docker) para facilitar a instalação e avaliação.

## 6. Restrições e Regras de Negócio
* Fotos devem ser otimizadas para não sobrecarregar o servidor.
* A validação dos campos obrigatórios deve ocorrer no frontend e backend.
* O CSV deve seguir um layout pré-definido para ser aceito.

## 7. Diferencial (Bônus)
* Dashboard inicial com métricas rápidas (Total de prestadores, Serviços mais ofertados) para dar uma visão gerencial ao Seu João.