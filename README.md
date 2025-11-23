# 🎓 eduApp-API (Movato Backend)

**eduApp-API** is the robust backend service powering **Movato**, an AI-augmented educational platform designed to help elementary school students learn mathematics interactively.

This API serves as the central hub for:
* 📱 **Movato App:** The student-facing mobile/web application.
* 🛡️ **Movato Admin Dashboard:** The management interface for content, users, and system logs.

The core differentiator is the **AI Grading System**, which evaluates student answers in real-time, classifying them as *understood*, *needs revision*, or *not yet understood*.

---

## 🚀 Features Overview

### Core & Authentication
* **Secure Auth:** Email/Password login & Google OAuth integration.
* **Token Management:** Powered by Laravel Sanctum.
* **RBAC:** Distinct roles for Students and Admins.

### 📚 Course & Content Management
* **Curriculum:** Structured hierarchy of Courses, Lessons, and Materials tailored for elementary math.
* **Quiz Engine:** Support for quizzes with multiple question types, attempt tracking, and result analytics.

### 🤖 AI Grading System
* **Smart Evaluation:** Prompt-based AI grader using OpenAI (GPT-4o-mini).
* **Feedback:** Returns classification and detailed explanations for student answers.

### 🛡️ Admin Panel
* **Built with FilamentPHP:** A sleek, intuitive dashboard for managing content, users, and system configurations.
* **Monitoring:** System logs and AI configuration parameters.

---

## 🛠 Tech Stack

| Component | Technology |
| :--- | :--- |
| **Framework** | Laravel 12 |
| **Database** | Supabase (PostgreSQL) |
| **Authentication** | Laravel Sanctum, Google OAuth |
| **Admin Panel** | FilamentPHP |
| **API Docs** | L5-Swagger (OpenAPI 3.0) |
| **AI Integration** | OpenAI API |
| **Deployment** | Laravel Cloud |

---

## 📂 Project Architecture

This project follows a structured **Service-Repository Pattern** to ensure scalability and testability.

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/        # API Endpoints (Versioned)
│   │   └── Web/           # Web/Filament Controllers
│   ├── Middleware/
│   └── Requests/          # FormRequests (Validation)
├── Models/
├── Repositories/          # Data Access Layer (DB Abstraction)
├── Services/              # Business Logic (AI Grading, Integrations)
└── Routes/
    ├── api.php            # Routes prefixed with /api/v1
    └── web.php
```

##🧭 Development Patterns

    Controllers: Kept thin. They delegate validation to Requests and logic to Services.

    Services: Handle complex business logic (e.g., GradingService.php) and external API calls.

    Repositories: Isolate database queries to simplify testing and swapping data sources.

    Versioning: All API routes are grouped under v1 to ensure future backward compatibility.

---

##⚙️ Installation & Setup

Prerequisites

    PHP 8.2+

    Composer

    PostgreSQL (or Supabase credentials)

1. Clone the Repository
   ```
   git clone [https://github.com/NaufalArdian12/eduApp-API.git](https://github.com/NaufalArdian12/eduApp-API.git)
   cd eduApp-API
   ```
2. Install Dependencies
   ```
   composer install
   npm install  # If assets need building
   ```
3. Environment Configuration
   Copy the example environment file and generate the app key
   ```
   cp .env.example .env
   php artisan key:generate
   ```
Configure your .env variables:
```
# Database (Supabase)
DB_CONNECTION=pgsql
DB_URL=postgres://user:password@host:port/database

# API & Swagger
APP_URL=http://localhost:8000
L5_SWAGGER_CONST_HOST=http://localhost:8000

# AI Configuration
OPENAI_API_KEY=sk-proj-...
OPENAI_MODEL=gpt-4o-mini

# OAuth
GOOGLE_CLIENT_ID=your-client-id
```

4. Database Setup
Run migrations and seed the database with initial data:
```
php artisan migrate
php artisan db:seed
```

5. Generate Documentation
Generate the Swagger OpenAPI documentation:
```
php artisan l5-swagger:generate
```
