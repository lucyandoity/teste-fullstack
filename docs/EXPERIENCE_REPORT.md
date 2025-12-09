# Relatório de Experiência de Desenvolvimento

**Candidato:** Jhonata Augusto Silva
**Vaga:** Desenvolvedor PHP Full‑Stack Júnior
**Projeto:** Sistema de Gestão "Seu João"

---

## 1. Introdução e Contexto

Este documento relata a jornada de desenvolvimento durante o teste técnico. O objetivo é compartilhar a linha de raciocínio, as decisões arquiteturais tomadas e como experiências prévias contribuíram para a resolução de problemas complexos em um ambiente legado (CakePHP 2). O foco vai além da codificação: engloba entendimento do negócio, qualidade e usabilidade.

## 2. Abordagem Inicial — O Produto antes do Código (SDD)

Antes de escrever qualquer linha de código ou configurar o ambiente, foi elaborado um Documento de Design de Software (SDD).

* Justificativa: priorizar o entendimento do negócio tanto quanto a tecnologia.
* Ações: definição da persona "Seu João" e mapeamento de histórias de usuário.
* Objetivo: garantir que o que fosse implementado resolvesse dores reais (substituir o caderno, agilizar buscas, garantir confiança nos dados).

**Resultado:** o planejamento orientou as decisões técnicas subsequentes, reduzindo retrabalho e assegurando foco nas necessidades do usuário final (por exemplo, busca e importação de dados).

## 3. Execução Técnica — CakePHP 2 e Docker

Com a visão de produto definida, foi necessário viabilizar a infraestrutura técnica:

* **Desafio:** ecossistema legado (PHP 7.2), dependências obsoletas (ex.: mcrypt) e problemas clássicos de permissões (pasta `tmp` do CakePHP).
* **Solução:** criação de um `Dockerfile` robusto que compila extensões via PECL e scripts de inicialização para ajustar permissões automaticamente nos volumes.

**Impacto:** ambiente isolado e reprodutível; elimina o problema de "funciona na minha máquina" e permite concentrar esforços na lógica de negócio.

## 4. O Grande Desafio — Divergência de Requisitos e Engenharia

### 4.1 Conflito

Ao analisar o Figma, a interface sugeria um cadastro simples (associação de um prestador a um único serviço/valor), enquanto a label apresentava "Quais serviços você vai prestar?" (plural).

Os requisitos de negócio (Notion) falavam explicitamente em **serviços** e em uma lista de prestadores.

Problema detectado: seguir o Figma literalmente impediria cadastrar o mesmo prestador oferecendo múltiplos serviços sem duplicar dados (e‑mail) ou criar múltiplos cadastros, o que degrada a integridade do banco e a experiência do usuário.

### 4.2 Solução Arquitetural

Priorizando integridade e escalabilidade, foi adotada uma modelagem N:N com tabela pivô `provider_services`.

* Permite que um prestador ofereça múltiplos serviços com preços distintos.
* Torna o catálogo de serviços independente dos prestadores.

**Frontend:** implementação de um repeater (jQuery) para cadastrar o prestador e N serviços em um único fluxo.
**Backend:** Camada de Serviço que usa `saveAssociated()` dentro de transações (begin/commit/rollback) para garantir atomicidade — ou todo o conjunto é salvo corretamente, ou nada é persistido.

Essa decisão alterou levemente o layout proposto, mas entregou um sistema funcionalmente superior e aderente às regras de negócio.

## 5. Front‑end — Fidelidade e Evolução da UX

Busquei fidelidade ao UI Kit do Figma (cores, tipografia Inter, espaçamentos), mas com uma evolução estratégica no fluxo de navegação:

* Substituí o cadastro de serviços via modal por uma **Página de Catálogo de Serviços** dedicada, transformando "Serviço" em entidade gerenciável.
* Justificativa: evita duplicidades (ex.: "Pintor" vs "Serviço de Pintura") e permite padronização pelo administrador.
* Melhoria de feedback: troquei `alert/confirm` nativos por modais e toasts para elevação da percepção de qualidade.

## 6. O Diferencial (Bônus) — Visão de Negócio

Para surpreender o cliente, implementei um **Dashboard Gerencial** na tela inicial com foco em transformar dados em informação:

* **Métricas em tempo real:** total de prestadores ativos, serviços cadastrados e ticket médio.
* **Top Service:** algoritmo que identifica o serviço mais ofertado.
* **Performance:** queries de agregação (COUNT, AVG, GROUP BY) encapsuladas em um `DashboardService` e cacheadas por 15 minutos para evitar sobrecarga do banco.

## 7. Limitações Técnicas — O Desafio dos Testes Automatizados

Foi identificada a ausência de testes unitários (PHPUnit) no pipeline de CI por causa da incompatibilidade entre o CakePHP 2.x e versões modernas de PHPUnit/PHP.

* **Problema:** CakePHP 2.x depende de PHPUnit 3.7, incompatível com assinaturas do PHP 7.2+.
* **Tentativa:** aplicação de patches manuais durante o build não foi suficiente para garantir estabilidade.

**Decisão:** priorizei qualidade estática com PHPCS seguindo PSR‑2 (linter rigoroso), em vez de construir uma infraestrutura complexa e frágil para TDD no escopo deste desafio.

## 8. Conclusão e Aprendizados

Principais aprendizados e comportamentos demonstrados:

* **Resiliência:** trabalhar com frameworks legados exige paciência e adaptabilidade.
* **Visão crítica e mentalidade de dono:** questionar designs quando conflitam com as regras de dados e propor soluções melhores.
* **Foco em produto:** priorizar entendimento do negócio e entregar valor mensurável (dashboard, integridade, UX).

Estou entusiasmado com a possibilidade de trazer essa mentalidade de resolução de problemas, organização e foco no negócio para o time da Doity.

---
