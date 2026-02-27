# Laravel 12 Todo List App

A simple CRUD Todo List application built with Laravel 12, PostgreSQL, Docker, and styled with Tailwind CSS. It is configured for seamless local development and easy production deployment on [Zeabur](https://zeabur.com).

## 🚀 Technology Stack

- **Core:** Laravel 12 (PHP 8.3)
- **Database:** PostgreSQL 15
- **Styling:** Tailwind CSS (via CDN for simplicity)
- **Local Dev:** Docker & Docker Compose (`compose.local.yml`), Apache
- **Production Hosting:** Zeabur natively (via Dockerfile)

---

## 🛠️ Local Development Setup (Using Docker)

The easiest way to run this application locally is by using Docker and Docker Compose. This ensures you don't need to manually configure PHP, Nginx, or PostgreSQL on your host machine.

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/) installed.
- [Docker Compose](https://docs.docker.com/compose/install/) installed (usually comes with Docker Desktop).
- [Git](https://git-scm.com/) installed.

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/bugkey24/laravel-todo-zeabur.git
   cd to-do-list-app
   ```

2. **Set up the Environment Variables**
   Copy the example `.env` file and configure it:
   ```bash
   cp .env.example .env
   ```
   *(Note: The default `.env.example` is already pre-configured to point to the Docker PostgreSQL container, so no manual database credential changes are needed for local dev).*

3. **Start the Docker Containers**
   Run the following command to build the images and start the services (Laravel App with Apache, PostgreSQL) in the background:
   ```bash
   docker compose -f compose.local.yml up -d --build
   ```

4. **Install Composer Dependencies & Generate App Key**
   Run these commands inside the `app` container:
   ```bash
   docker compose -f compose.local.yml exec app composer install
   docker compose -f compose.local.yml exec app php artisan key:generate
   ```

5. **Run Database Migrations**
   Create the database tables by running migrations inside the container:
   ```bash
   docker compose -f compose.local.yml exec app php artisan migrate
   ```

6. **Access the Application**
   Open your browser and navigate to:
   [http://localhost:8000](http://localhost:8000)

---

## 🛑 Stopping the Local Environment

To stop the running Docker containers without destroying your database data:
```bash
docker compose -f compose.local.yml stop
```

To totally remove the containers (your database volume will persist):
```bash
docker compose -f compose.local.yml down
```

---

## ☁️ Production Deployment on Zeabur

This repository is fully configured for zero-downtime deployments on **Zeabur**.

### Prerequisites
- A [Zeabur](https://zeabur.com) account.
- Your project pushed to a GitHub repository.

### Step-by-Step Deployment

1. **Create a New Project on Zeabur**
   - Log in to your Zeabur dashboard.
   - Click **Create Project** and choose a region closest to your users.

2. **Add a PostgreSQL Service**
   - Click **Add Service** -> **Prebuilt Service** -> **PostgreSQL**.
   - Wait for it to become healthy. Zeabur will automatically expose environment variables like `POSTGRES_HOST`, `POSTGRES_USER`, etc., in the internal network.

3. **Deploy the Laravel Application**
   - Click **Add Service** -> **Deploy from GitHub**.
   - Search for and select this repository (`to-do-list-app`).
   - Choose the branch you want to deploy (usually `main`).

4. **Configure Environment Variables in Zeabur**
   Before the deployment finishes, configure the required Environment Variables in the deployed Service Settings:
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: Generate a key locally (`php artisan key:generate --show`) and paste it here.
   - `DB_CONNECTION`: `pgsql`
   - **Database Credentials**: Map Zeabur's PostgreSQL variables to Laravel's expectation:
     - `DB_HOST`: `${POSTGRES_HOST}`
     - `DB_PORT`: `${POSTGRES_PORT}`
     - `DB_DATABASE`: `${POSTGRES_DB}`
     - `DB_USERNAME`: `${POSTGRES_USER}`
     - `DB_PASSWORD`: `${POSTGRES_PASSWORD}`

5. **Wait for Build & Deployment**
   - Zeabur will automatically build and deploy your application using the `Dockerfile` present in the repository root.
   - The Dockerfile bundles Apache, PHP, and runs `composer install --no-dev` automatically.
   
   *(Note: You can run `php artisan migrate --force` through Zeabur's dashboard console once the service is running, or set it as a startup command).*
   
6. **Assign a Domain**
   - Go to the **Domain** tab of your deployed application service on Zeabur.
   - Generate a `*.zeabur.app` sub-domain or bind your custom domain.
   - Your application is now live! 🚀

---

## 🔒 Security & Performance Configurations Included

- **PHP OpCache**: The included `Dockerfile` installs and configures PHP OpCache for massive raw performance gains in production environments.
- **Apache Web Server**: Replaced multi-service Nginx + PHP-FPM with a unified `php:8.3-apache` image for optimal compatibility with zero-config cloud deployment platforms like Zeabur.
- **Database Security**: The `compose.local.yml` ensures that your database port `5432` is securely bound to local interfaces (`127.0.0.1`) mitigating the risk of exposure to the public internet on VMs.
