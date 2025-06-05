# AIColl API

Simple Laravel API for managing company records.

## 🚀 Requirements

- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 10

## 🛠️ Installation

1. **Clone the repository**

```bash
git clone https://github.com/AmandaQuintana/aicoll-api.git
cd aicoll-api
```

2. **Install dependencies**

```bash
composer install
```

3. **Create environment file**

```bash
cp .env.example .env
```

4. **Generate application key**

```bash
php artisan key:generate
```

5. **Configure your `.env` file**

Set your MySQL credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aicoll_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

> Make sure that the `aicoll_db` database already exists on your MySQL server.

6. **Run migrations**

```bash
php artisan migrate
```

7. **(Optional) Seed data**

```bash
php artisan db:seed
```

## ▶️ Running the server

```bash
php artisan serve
```

The app will be available at [http://localhost:8000](http://localhost:8000)

---

## ✅ Running tests

Run all tests (Feature and Unit):

```bash
php artisan test
```

Or run specific test classes:

```bash
php artisan test --filter=CompanyControllerTest
php artisan test --filter=CompanyRequestTest
```

> The tests use an in-memory SQLite database. They will not affect your real MySQL database.

---

## 📦 API Endpoints

| Method | Endpoint                | Description                   |
|--------|-------------------------|-------------------------------|
| GET    | /api/companies          | List all companies            |
| POST   | /api/companies          | Create new company            |
| GET    | /api/companies/{tax_id} | Get company by Tax ID         |
| PUT    | /api/companies/{tax_id} | Update company by Tax ID      |
| DELETE | /api/companies/{tax_id} | Delete inactive company by ID |

---

## 🧪 Tests Covered

- **Feature tests** for `CompanyController`:
  - Create, Read, Update, Delete companies
- **Unit tests** for `CompanyRequest`:
  - Validation logic for `active`, `tax_id`
  - Custom validation messages
  - Business rule: cannot delete active companies
