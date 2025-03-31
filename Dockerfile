# Use Node.js v18.12 as base image with Git
FROM node:18.20.2-alpine

# Instalar Git e outras dependências necessárias no Alpine Linux
RUN apk add --no-cache git curl

# Instalar uma versão compatível do npm (npm 8.x para Node.js 18)
RUN npm install -g npm@8

# Definir diretório de trabalho
WORKDIR /app

# Clonar o repositório do GrapesJS e inicializar submódulos
RUN git clone --recurse-submodules https://github.com/GrapesJS/grapesjs.git

# Definir diretório de trabalho para o repositório clonado
WORKDIR /app/grapesjs

# Instalar pnpm e dependências do projeto
RUN npm install -g pnpm && pnpm install

# Instalar grapesjs-cli e outras dependências
RUN npm install --save-dev grapesjs-cli --legacy-peer-deps
RUN npm install ajv@8 ajv-keywords@5 --save --legacy-peer-deps

# Copiar arquivos necessários
COPY ./index /app/grapesjs-sqlserver-project/grapesjs/src/index
COPY ./index.html /app/grapesjs/node_modules/grapesjs-cli/index.html

# Expor a porta necessária para GrapesJS
EXPOSE 8001

# Comando de inicialização do GrapesJS
CMD ["npx", "grapesjs-cli", "serve"]
