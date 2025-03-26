# Use a imagem oficial do Node.js
FROM node:18

# Instale o pnpm globalmente
RUN npm install -g pnpm

# Defina o diretório de trabalho dentro do contêiner
WORKDIR /app

# Copie o package.json e o pnpm-lock.yaml (caso tenha) para o diretório /app
COPY package.json pnpm-lock.yaml* /app/

# Instale as dependências do projeto (de desenvolvimento e de produção)
RUN pnpm install --frozen-lockfile

# Copie o restante dos arquivos do projeto
COPY . /app/

# Exponha a porta 80
EXPOSE 80

# Defina o comando para iniciar o servidor
CMD ["pnpm", "start"]
