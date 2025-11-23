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
* **Curriculum:** structured hierarchy of Courses, Lessons, and Materials tailored for elementary math.
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

Notes:

Controllers/Api/V1/: All API endpoints for version 1 live here (keeps API versioning clear).
Requests/: Request validation classes (FormRequests) for each endpoint.
Repositories/: Encapsulate DB queries, external data sources, and abstract data access.
Services/: Business logic like AI grading, integrations with Supabase, mail, etc.

⚙️ Installation (Local Development)
1. Clone
```
git clone https://github.com/NaufalArdian12/eduApp-API.git
cd eduApp-API
```

2. Install
```
composer install
npm install        # only if there are JS assets to build
```

3. Environment
```
cp .env.example .env
php artisan key:generate
```

4. Required .env variables (example)
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
AUTH_SEND_VERIFY=false
L5_SWAGGER_CONST_HOST=http://localhost:8000
L5_SWAGGER_OPEN_API_SPEC_VERSION=3.0.0
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_URL=

APP_URL=http://127.0.0.1:8000
GOOGLE_CLIENT_ID=


SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

▶️ Run the app

Start the app:
```
php artisan migrate
php artisan serve
```
Optional seed:
```
php artisan db:seed
```

Swagger docs generate:
```
php artisan l5-swagger:generate
# then visit /api/documentation
```

🔐 Authentication Flow

Supported:
Email & password
Google OAuth
Sanctum tokens
Example login response:
```
{
  "token": "sanctum_personal_access_token_here",
  "user": { /* user object */ }
}
```
Use:
```
Authorization: Bearer <token>
```

🤖 AI Grading System

Grading service (implemented under app/Services/) accepts:
student answer / prompt
canonical/expected answer
acceptable alternatives
Returns:
```
{
  "result": "understand" | "revision_needed" | "not_understand",
  "score": 0.0,          // optional numeric score
  "explanation": "..."
}
```
Place AI logic inside a Service (e.g., app/Services/GradingService.php) and keep Controllers thin — controllers should call Services/Repositories, and Requests for validation.


🧭 API Versioning & Routing

Routes in routes/api.php should be grouped with prefix /api/v1 and use controllers under App\Http\Controllers\Api\V1.
Example:
```
Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    // ...
});
```

🛡 Admin Dashboard (Filament)

Filament is used to manage:
Courses, lessons, and quizzes
User accounts and roles (permissions)
AI configuration parameters
System logs
Admin UI path:
```
/admin
```

🌐 Deployment (Laravel Cloud)

Application is public (deployed) but access-restricted to Movato clients + admin roles.
Set .env variables in Laravel Cloud dashboard (DB, OAuth, Mail, SANCTUM_STATEFUL_DOMAINS).
Use queues for email and AI grading jobs (supervisor/worker recommended).
Use HTTPS and enable CORS only for trusted origins (Movato app domains and admin).


✅ Recommended Development Patterns

Controllers: keep thin — validate with Requests, delegate to Services.
Requests: use Laravel Form Requests for input validation/authorization.
Repositories: isolate DB logic to simplify testing.
Services: implement AI integration, external API calls, and complex business rules.
Versioning: keep API stable by using Api/V1 and introducing V2 when needed.

🤝 Contribution

Fork → create a branch → open PR.
Add tests for new features.
Update Swagger docs when endpoints change.


📄 License

MIT License (or change if desired).


📬 Contact

Author: Moch. Naufal Ardian Ramadhan (Naufal)
Repo: https://github.com/NaufalArdian12/eduApp-API
