eduApp-API (Movato Backend)

eduApp-API is the backend service powering Movato, an AI-augmented educational platform for elementary school students to learn mathematics interactively.
This API serves both the Movato App (student-facing) and the Movato Admin Dashboard (management and content control). The system evaluates students' answers with an AI grader that classifies responses as understand, need revision, or not yet understand.

🚀 Features Overview
Core features

Authentication

Email/password

Google OAuth

Laravel Sanctum token authentication

User Management

Student accounts

Admin roles & permissions

Profile management

Course & Content Management

Courses, lessons, and learning materials tailored for elementary math

Quiz Management

Quizzes, multiple question types, attempts & results

AI Grading System

Prompt-based grader that returns classification and explanation

Admin Panel

FilamentPHP-based admin for content, user, and system management

API Documentation

L5-Swagger (OpenAPI)

Publicly deployed (Laravel Cloud) but restricted access: only Movato clients and admin users

🛠 Tech Stack

Framework: Laravel 12

Auth: Laravel Sanctum, Google OAuth

Admin: FilamentPHP

Database: Supabase (Postgres)

Docs: L5-Swagger (Swagger)

Pattern: Services, Repositories, Requests, Controllers (API versioned folders)

📂 Project Structure (recommended / simplified)
```
app/
  ├── Console/
  ├── Exceptions/
  ├── Http/
  │     ├── Controllers/
  │     │     ├── Api/
  │     │     │    └── V1/                # API v1 controllers
  │     │     └── Web/                    # Web / Filament controllers (if any)
  │     ├── Middleware/
  │     └── Requests/                     # Form Request validation classes
  ├── Models/
  ├── Repositories/                       # Data access layer (Repository pattern)
  ├── Services/                           # Business logic, AI grading, integrations
  ├── Policies/
  └── Providers/

config/
database/
routes/
  ├── api.php                             # API routes (v1)
  └── web.php

resources/
tests/
```

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
