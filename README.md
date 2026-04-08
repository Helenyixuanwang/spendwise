# SpendWise 💰

A personal expense tracker built with the LAMP stack (Linux, Apache, MySQL, PHP),
containerized with Docker.

## Features

- User registration and login with bcrypt password hashing
- Add, edit, and delete personal expenses
- Categorize expenses (Food, Transport, Housing, Health, Entertainment, Other)
- Monthly summary by category using a MySQL stored procedure
- Month-by-month navigation
- Responsive UI with Bootstrap 5

## Tech Stack

| Layer    | Technology        |
|----------|-------------------|
| Backend  | PHP 8.2           |
| Database | MySQL 8.0         |
| Server   | Apache (via Docker)|
| Frontend | Bootstrap 5, HTML |
| DevOps   | Docker, Docker Compose |

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

## Project Structure
```
spendwise/
├── docker-compose.yml
├── Dockerfile
└── src/
    ├── config/       # Database connection
    ├── auth/         # Login, register, logout
    ├── expenses/     # Dashboard, add, edit, delete
    └── sql/          # Schema and stored procedure
```

## Database Schema

- `users` — stores registered users with hashed passwords
- `expenses` — stores expense records per user
- `GetMonthlySummary` — stored procedure returning totals per category