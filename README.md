# 📦 Laravel + GrapesJS + Tailwind CSS + Filament (Backend e Frontend)

Este projeto é uma aplicação fullstack desenvolvida em **Laravel** com **Blade** para o frontend, e **MySQL** como banco de dados. Ele implementa autenticação, controle de acesso baseado em papéis (RBAC), e gerenciamento de usuários.

## ✅ Requisitos

- **PHP** (>= 8.1)
- **Composer**
- **MySQL**
- **Node.js** (caso o frontend utilize assets com Vite)
- **Docker** (opcional, para ambiente containerizado)

---

## ⚙️ Configuração do Ambiente

### 1. Clone o repositório

```bash
git clone https://github.com/LuanGFRicardo/grapesjs-sqlserver-project
cd grapesjs-laravel
```

### 2. Configure as variáveis de ambiente

Copie o `.env.example` para `.env` e edite conforme necessário:

> 💡 Se estiver usando Docker, ajuste `DB_HOST` ou conforme sua configuração de container.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gpsjs
DB_USERNAME=gpsjs-user
DB_PASSWORD="Vf8mw436^"
```

### 3. Crie as tabelas

Acesse o arquivo `grapesjs-sqlserver-project\docker\mysql\script.db` e execute o script.

### 4. Instale as dependências do Laravel

```bash
cd grapesjs-sqlserver-project\docker
composer install
```

### 5. Gere a chave da aplicação

```bash
cd grapesjs-sqlserver-project\grapesjs-laravel
php artisan key:generate
```

### 6. Rode as migrações e seeders (opcional)

```bash
cd grapesjs-sqlserver-project\grapesjs-laravel
php artisan migrate --seed
```

> 🗃️ Isso criará as tabelas e um administrador padrão (ajuste os seeders conforme sua regra de negócio).

### 7. Suba o servidor local

```bash
cd grapesjs-sqlserver-project\grapesjs-laravel
php composer run-script dev
```

A aplicação estará disponível em: `http://localhost:8000`

---

## 🎨 Compilando os assets (caso use Vite)

### 1. Instale as dependências do frontend

```bash
cd grapesjs-sqlserver-project\grapesjs-laravel
npm install
```

### 2. Compile os assets

```bash
npm run dev
```

---

## 🧪 Testando o Sistema

Você pode usar o **Postman** ou testar diretamente no frontend via browser.

### Exemplo de requisição

- **URL**: `http://127.0.0.1:8000/api/dados/lista-registros`
- **Método**: `POST`
- **Payload**:

---

## 🔐 Controle de Acesso (RBAC)

O Laravel possui middleware e políticas para controle baseado em papéis. Papéis disponíveis:

- `admin`: Acesso total (aprova usuários, gerencia tudo)
- `gerente`: Acesso parcial (vê e gerencia usuários da sua empresa)
- `operador`: Acesso restrito (realiza operações básicas)

---

## 🧱 Estrutura do Banco de Dados

- **users**: informações dos usuários
- **roles**: papéis
- **role_user**: relação usuário-papel
- **companies**: empresas associadas
- **permissions**: permissões específicas por rota

---

## 🛠️ Comandos úteis

- Garantir carregamento de todas as classes:
  ```bash
  composer dump-autload
  ```
- Limpar cache de configuração:
  ```bash
  php artisan optimize:clear
  ```

---

## 🚀 Docker (Opcional)

Caso deseje rodar via Docker:

```bash
docker-compose up -d
```

Ajuste o `.env` para refletir os containers (`DB_HOST`).

---

## TODO

### 📌 Funcionalidades Planejadas

- [ ] Automatizar criação de tabelas utilizadas