# Projeto GrapesJS + PHP + SQL Server com Docker

## Como rodar o projeto na máquina local:

Versão Node.js v18.12 necessária

Clone o repositório `git clone --recurse-submodules https://github.com/GrapesJS/preset-newsletter` e acesse `cd preset-newsletter`

Modifique em preset-newsletter/package.json:

`"start": "concurrently \"node server.js\" \" grapesjs-cli serve\"",`

`  "devDependencies": {
    "express": "^5.1.0",
    "concurrently": "^8.0.1"
  },`

Execute `npm install` e `npm start`

## Como rodar o projeto no Docker:

1. Execute: `docker-compose up -d --build`.

2. Acesse o frontend (GrapesJS) em: `http://localhost:8080`.

3. O backend PHP roda em: `http://localhost:8000/exibir_dados.php`.

4. SQL Server estará disponível em `localhost:1433`.

5. O estilo (CSS) e comportamento (JS) estará disponível em `http://localhost:8081/assets/`.

## Observação:
- O backend PHP acessa o SQL Server diretamente via `sqlsrv`.
- O frontend consome o backend via **fetch()**.
- O arquivo server.js está configurado para servir arquivos estáticos atravpes do Express, apontando para o diretório `assets`.