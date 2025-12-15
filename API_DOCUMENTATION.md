# API Sistem Manajemen Proyek

Sistem manajemen proyek berbasis REST API yang dibangun dengan Laravel, dilengkapi dengan fitur authentication, CRUD operations, validasi, error handling, dan comprehensive testing.

## 📋 Daftar Isi
- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Instalasi](#-instalasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Testing](#-testing)
- [API Documentation](#-api-documentation)
- [Database Structure](#-database-structure)
- [Postman Collection](#-postman-collection)

## ✨ Fitur

### Bagian A - REST API Implementation
- ✅ RESTful API design sesuai best practices
- ✅ Token-based authentication (Laravel Sanctum)
- ✅ CRUD operations untuk Projects dan Tasks
- ✅ Input validation dengan custom error messages
- ✅ Comprehensive error handling
- ✅ Proper HTTP status codes
- ✅ User isolation & authorization

### Bagian B - API Testing
- ✅ 40 automated test cases
- ✅ Positive dan negative test scenarios
- ✅ Authentication testing
- ✅ Data consistency testing
- ✅ Error handling testing
- ✅ 100% test pass rate
- ✅ Postman collection dengan test scripts

## 🛠 Teknologi

- **Framework**: Laravel 12.42.0
- **PHP**: 8.1+
- **Database**: SQLite (Development), MySQL/PostgreSQL (Production)
- **Authentication**: Laravel Sanctum
- **Testing**: PHPUnit, Laravel HTTP Testing
- **API Testing**: Postman

## 📦 Instalasi

### Prerequisites
- PHP >= 8.1
- Composer
- Git

### Setup Steps

1. **Clone atau gunakan project yang sudah ada**
```bash
cd "C:\Users\Hp\Downloads\api laravel"
```

2. **Install dependencies** (jika belum)
```bash
composer install
```

3. **Copy environment file** (jika belum)
```bash
copy .env.example .env
```

4. **Generate application key** (jika belum)
```bash
php artisan key:generate
```

5. **Setup database**

File `.env` sudah dikonfigurasi menggunakan SQLite:
```env
DB_CONNECTION=sqlite
```

Database file akan otomatis dibuat di `database/database.sqlite`

6. **Run migrations**
```bash
php artisan migrate
```

7. **Ready to go!** 🚀

## 🚀 Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

Base URL untuk API: `http://localhost:8000/api`

### Contoh Penggunaan API

#### 1. Register User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### 2. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

Response akan berisi `access_token` yang digunakan untuk request berikutnya.

#### 3. Create Project (Protected)
```bash
curl -X POST http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Website Redesign",
    "description": "Complete website redesign project",
    "status": "planning",
    "start_date": "2025-01-15",
    "end_date": "2025-06-30"
  }'
```

#### 4. Create Task (Protected)
```bash
curl -X POST http://localhost:8000/api/projects/1/tasks \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Design Homepage",
    "description": "Create homepage mockup",
    "status": "pending",
    "priority": "high",
    "due_date": "2025-02-15"
  }'
```

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test File
```bash
php artisan test --filter=AuthTest
php artisan test --filter=ProjectTest
php artisan test --filter=TaskTest
```

### Test Results
```
Tests:    40 passed (164 assertions)
Duration: 1.55s
Success Rate: 100%
```

### Test Coverage
- **11 tests** - Authentication (Register, Login, Logout, Profile)
- **13 tests** - Project Management (CRUD + Authorization)
- **14 tests** - Task Management (CRUD + Authorization + Cascade Delete)
- **2 tests** - Additional (Unit & Feature)

## 📚 API Documentation

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/register` | Register new user | No |
| POST | `/api/login` | Login user | No |
| GET | `/api/me` | Get user profile | Yes |
| POST | `/api/logout` | Logout user | Yes |

### Project Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/projects` | Get all user's projects | Yes |
| POST | `/api/projects` | Create new project | Yes |
| GET | `/api/projects/{id}` | Get specific project | Yes |
| PUT | `/api/projects/{id}` | Update project | Yes |
| DELETE | `/api/projects/{id}` | Delete project | Yes |

### Task Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/projects/{projectId}/tasks` | Get all tasks in project | Yes |
| POST | `/api/projects/{projectId}/tasks` | Create new task | Yes |
| GET | `/api/projects/{projectId}/tasks/{taskId}` | Get specific task | Yes |
| PUT | `/api/projects/{projectId}/tasks/{taskId}` | Update task | Yes |
| DELETE | `/api/projects/{projectId}/tasks/{taskId}` | Delete task | Yes |

### Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        "id": 1,
        "name": "Project Name",
        ...
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Error details"]
    }
}
```

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success - Request completed successfully |
| 201 | Created - Resource created successfully |
| 401 | Unauthorized - Authentication required |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error |

## 🗄 Database Structure

### Users Table
```
- id (Primary Key)
- name
- email (Unique)
- password
- created_at
- updated_at
```

### Projects Table
```
- id (Primary Key)
- user_id (Foreign Key -> users.id)
- name
- description
- status (enum: planning, in_progress, completed, on_hold)
- start_date
- end_date
- created_at
- updated_at
```

### Tasks Table
```
- id (Primary Key)
- project_id (Foreign Key -> projects.id)
- title
- description
- status (enum: pending, in_progress, completed)
- priority (enum: low, medium, high)
- due_date
- created_at
- updated_at
```

### Relationships
- User **has many** Projects
- Project **belongs to** User
- Project **has many** Tasks
- Task **belongs to** Project

## 📮 Postman Collection

Import file `Project_Management_API.postman_collection.json` ke Postman untuk testing.

### Setup Postman

1. Import collection ke Postman
2. Create environment dengan variables:
   - `base_url`: `http://localhost:8000/api`
   - `access_token`: (akan di-set otomatis saat login)

3. Jalankan requests sesuai urutan:
   - Register / Login dulu untuk mendapatkan token
   - Token akan otomatis tersimpan di environment variable
   - Gunakan untuk request berikutnya

### Test Scripts

Collection sudah dilengkapi dengan test scripts yang akan:
- ✅ Verify HTTP status codes
- ✅ Check response structure
- ✅ Validate data consistency
- ✅ Auto-save tokens dan IDs untuk request berikutnya

## 📝 Validation Rules

### Project Validation
```php
'name' => 'required|string|max:255'
'description' => 'nullable|string'
'status' => 'nullable|in:planning,in_progress,completed,on_hold'
'start_date' => 'nullable|date'
'end_date' => 'nullable|date|after_or_equal:start_date'
```

### Task Validation
```php
'title' => 'required|string|max:255'
'description' => 'nullable|string'
'status' => 'nullable|in:pending,in_progress,completed'
'priority' => 'nullable|in:low,medium,high'
'due_date' => 'nullable|date'
```

### User Validation
```php
// Register
'name' => 'required|string|max:255'
'email' => 'required|string|email|max:255|unique:users'
'password' => 'required|string|min:8|confirmed'

// Login
'email' => 'required|email'
'password' => 'required'
```

## 🔐 Security Features

1. **Token-based Authentication**: Laravel Sanctum untuk secure API access
2. **Password Hashing**: Bcrypt hashing untuk passwords
3. **Authorization**: User hanya bisa akses project/task milik mereka
4. **Input Validation**: Comprehensive validation untuk semua input
5. **SQL Injection Prevention**: Eloquent ORM protection
6. **CSRF Protection**: Built-in Laravel protection

## 📊 Testing Report

Lihat file `LAPORAN_TESTING.md` untuk:
- Detailed test results
- Test case documentation
- Critical analysis
- Recommendations

## 🎯 Project Structure

```
api laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── AuthController.php
│   │   │   ├── ProjectController.php
│   │   │   └── TaskController.php
│   │   └── Requests/
│   │       ├── StoreProjectRequest.php
│   │       ├── UpdateProjectRequest.php
│   │       ├── StoreTaskRequest.php
│   │       └── UpdateTaskRequest.php
│   └── Models/
│       ├── User.php
│       ├── Project.php
│       └── Task.php
├── database/
│   ├── factories/
│   │   ├── ProjectFactory.php
│   │   └── TaskFactory.php
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_projects_table.php
│       └── create_tasks_table.php
├── routes/
│   └── api.php
├── tests/
│   └── Feature/
│       ├── AuthTest.php
│       ├── ProjectTest.php
│       └── TaskTest.php
├── Project_Management_API.postman_collection.json
├── LAPORAN_TESTING.md
└── API_DOCUMENTATION.md
```

## 🤝 Contributing

Untuk development lebih lanjut:

1. Create feature branch
2. Commit changes
3. Run tests: `php artisan test`
4. Ensure all tests pass
5. Submit pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Project**: API Sistem Manajemen Proyek dengan Testing
**Framework**: Laravel 12
**Date**: Desember 2025

## 📞 Support

Untuk pertanyaan atau issue:
1. Check documentation di `API_DOCUMENTATION.md` dan `LAPORAN_TESTING.md`
2. Review test cases di folder `tests/`
3. Import Postman collection untuk API examples

---

**Status**: ✅ Production Ready
**Tests**: 40 passed (164 assertions)
**Success Rate**: 100%
