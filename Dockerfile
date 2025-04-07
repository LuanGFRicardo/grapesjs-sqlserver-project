# Use Node.js v20 como base (Alpine para leveza)
FROM node:20-alpine

# Instalar dependências básicas
RUN apk add --no-cache git curl

# Instalar e fixar o pnpm
RUN npm install -g pnpm@latest-10

# Definir diretório de trabalho principal
WORKDIR /app

# Clonar o repositório do GrapesJS e inicializar submódulos
RUN git clone --recurse-submodules https://github.com/GrapesJS/grapesjs.git

# Definir diretório de trabalho para o repositório clonado
WORKDIR /app/grapesjs

# Instalar dependências usando pnpm
RUN pnpm install

# Instalar grapesjs-cli e outras dependências com flag --workspace-root
RUN pnpm add -D grapesjs-cli ajv@8 ajv-keywords@5 -w

# Copiar arquivos do projeto local para dentro da imagem Docker
COPY ./index /app/grapesjs-sqlserver-project/grapesjs/src/index
COPY ./index.html /app/grapesjs/public/index.html
# COPY ./index.html /app/grapesjs/packages/core/node_modules/grapesjs-cli/index.html
# COPY ./webpack.config.js /app/grapesjs/webpack.config.js

# Copiar o arquivo server.js para a raiz do projeto
COPY ./server.js /app/grapesjs/server.js

# Substituir o package-lock.json pela versão local
COPY ./package-lock.json /app/grapesjs/package-lock.json

# Copiar a pasta "assets/CRCPR" dentro do diretório principal
COPY ./assets/CRCPR /app/grapesjs/assets/CRCPR

# Expor a porta necessária para o servidor (ajuste se necessário)
EXPOSE 8081

# Comando de inicialização com server.js
CMD ["node", "server.js"]
