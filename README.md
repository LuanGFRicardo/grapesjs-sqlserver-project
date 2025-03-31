# Projeto GrapesJS + PHP + SQL Server com Docker

## Como rodar o projeto:

Versão Node.js v18.12 necessária

Clone o repositório `git clone --recurse-submodules https://github.com/GrapesJS/grapesjs.git` e acesse `cd grapesjs`

Execute `npm install -g pnpm`, `pnpm install`, `npm install --save-dev grapesjs-cli --legacy-peer-deps` e`npm install ajv@8 ajv-keywords@5 --save --legacy-peer-deps` 

Copie o arquivo arquivo index para grapesjs-sqlserver-project\grapesjs\src\index e copie index.html para grapesjs\node_modules\grapesjs-cli\index.html 

Execute `npx grapesjs-cli serve`

1. Execute: `docker-compose up -d --build`.

2. Acesse o frontend (GrapesJS) em: `http://localhost:8080`.

3. O backend PHP roda em: `http://localhost:8000/exibir_dados.php`.

4. SQL Server estará disponível em `localhost:1433`.

## Observação:
- O backend PHP acessa o SQL Server diretamente via `sqlsrv`.
- O frontend consome o backend via **fetch()**.