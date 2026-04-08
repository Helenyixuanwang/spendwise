# SpendWise 💰

A personal expense tracker built with the LAMP stack (Linux, Apache, MySQL, PHP),
containerized with Docker. Features both a traditional procedural PHP implementation
and a clean MVC refactor branch.

## Features

- User registration and login with bcrypt password hashing
- Add, edit, and delete personal expenses
- Categorize expenses (Food, Transport, Housing, Health, Entertainment, Other)
- Monthly summary by category using a MySQL stored procedure
- Month-by-month navigation
- Responsive UI with Bootstrap 5
- Dockerized LAMP stack — runs with a single command

## Tech Stack

| Layer    | Technology             |
|----------|------------------------|
| Backend  | PHP 8.2                |
| Database | MySQL 8.0              |
| Server   | Apache (via Docker)    |
| Frontend | Bootstrap 5, HTML      |
| DevOps   | Docker, Docker Compose |

## Branches

| Branch | Description |
|--------|-------------|
| `main` | Traditional procedural PHP — mirrors real-world legacy LAMP codebases |
| `feature/mvc-refactor` | MVC structure with separate Models, Views, and Controllers |

## Getting Started

### Prerequisites
- Docker Desktop installed and running

### Run locally

```bash
git clone https://github.com/Helenyixuanwang/spendwise.git
cd spendwise
docker compose up -d
```

Visit `http://localhost:8080/auth/register.php` to create an account.

### Switch to MVC branch

```bash
git checkout feature/mvc-refactor
```

## Project Structure

### Main branch (procedural)
spendwise/
├── docker-compose.yml
├── Dockerfile
└── src/
├── auth/         # Login, register, logout
├── expenses/     # Dashboard, add, edit, delete
├── config/       # Database connection
└── sql/          # Schema and stored procedure

### MVC branch
spendwise/
├── docker-compose.yml
├── Dockerfile
└── src/
├── core/         # Base Database and Controller classes
├── models/       # User and Expense models
├── controllers/  # AuthController and ExpenseController
├── views/        # Pure HTML view files
└── sql/          # Schema and stored procedure

## Database Schema

- `users` — stores registered users with hashed passwords
- `expenses` — stores expense records per user
- `GetMonthlySummary` — stored procedure returning totals per category for a given month

## Key Learning Points

- Traditional PHP mixes logic and HTML — common in legacy LAMP codebases
- MVC refactor separates concerns: models handle data, controllers handle logic, views handle HTML
- Docker eliminates "works on my machine" — anyone can run this with `docker compose up -d`
- MySQL stored procedures encapsulate reusable database logic
- PDO prepared statements prevent SQL injection