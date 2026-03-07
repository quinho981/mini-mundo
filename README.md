# Mini-mundo

📊 Sistema full stack para gerenciamento de projetos e tarefas com autenticação JWT.
Desenvolvido com Laravel no backend e Vue 3 no frontend, seguindo boas práticas de arquitetura e ambiente Dockerizado.

🚀 Tecnologias
- PHP 8.2+
- Laravel
- MySQL
- Docker
- PHPUnit (testes)
- Dockerização:
    - Padronização, facilita o onboarding e previne problemas de compatibilidade de versões.
- CI/CD:
    - Automação para build e push da imagem no docker hub

📦 Requisitos
Antes de começar, você precisa ter instalado:
- Docker
- Docker Compose
- Git

📁 Estrutura Docker
```bash
docker/
 ├── nginx/
 │   └── default.conf
 ├── php/
 │   └── Dockerfile
 ├── vue
 │    └── Dockerfile
 └── mysql/
 docker-compose.yml
```

```bash
git clone https://github.com/quinho981/mini-mundo.git
```
```bash
cd mini-mundo
```

2️⃣ Suba os containers
```bash
docker compose up -d --build
```

Isso irá subir:
- PHP-FPM
- Nginx
- MySQL
- Vue3

Acesse o diretório backend e rode o comando para copiar o .env.example
```
cd backend
cp .env.example .env
```

3️⃣ Instalar dependências do Laravel
```bash
docker compose exec app composer install
```

4️⃣ Gerar a key da aplicação
```bash
docker compose exec app php artisan key:generate
```

5️⃣ Gerar a key JWT
```bash
docker compose exec app php artisan jwt:secret
```

6️⃣ Rodar as migrations e seeders
```bash
docker compose exec app php artisan migrate --seed
```

---

Acessando a aplicação
- http://localhost:5173/auth/login
- MySQL: localhost:3306