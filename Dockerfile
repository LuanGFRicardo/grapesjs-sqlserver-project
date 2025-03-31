# Use Node.js v20 como base (Alpine para leveza)
FROM node:20-alpine

# Instalar dependências básicas
RUN apk add --no-cache git curl

# Instalar e fixar o `pnpm`
RUN npm install -g pnpm@latest-10

# Definir diretório de trabalho
WORKDIR /app

# Clonar o repositório do GrapesJS e inicializar submódulos
RUN git clone --recurse-submodules https://github.com/GrapesJS/grapesjs.git

# Definir diretório de trabalho para o repositório clonado
WORKDIR /app/grapesjs

# Instalar dependências usando `pnpm`
RUN pnpm install

# Instalar `grapesjs-cli` e outras dependências com flag --workspace-root
RUN pnpm add -D grapesjs-cli ajv@8 ajv-keywords@5 -w

# Copiar arquivos necessários
COPY ./index /app/grapesjs-sqlserver-project/grapesjs/src/index
COPY ./index.html /app/grapesjs/node_modules/grapesjs-cli/index.html

# Expor a porta necessária para GrapesJS
EXPOSE 8001

# Comando de inicialização do GrapesJS
CMD ["pnpm", "grapesjs-cli", "serve"]
