# API Sistem Manajemen Proyek dan Testing

A comprehensive REST API for Project Management and Testing System built with Node.js, Express, and Sequelize.

## Features

- **User Management**: User registration, authentication, and authorization with JWT
- **Project Management**: CRUD operations for projects with manager assignment
- **Task Management**: Task creation, assignment, and tracking within projects
- **Test Case Management**: Create, execute, and track test cases for projects
- **Role-Based Access Control**: Admin, Manager, Developer, and Tester roles
- **RESTful API**: Clean and intuitive API endpoints

## Technology Stack

- **Node.js** - JavaScript runtime
- **Express.js** - Web framework
- **Sequelize** - ORM for database management
- **SQLite** - Database (easily switchable to PostgreSQL/MySQL)
- **JWT** - Authentication
- **bcryptjs** - Password hashing

## Installation

1. Clone the repository:
```bash
git clone https://github.com/JopelSimarmata/API-Sistem-Manajemen-Proyek-dan-Testing.git
cd API-Sistem-Manajemen-Proyek-dan-Testing
```

2. Install dependencies:
```bash
npm install
```

3. Configure environment variables:
```bash
cp .env.example .env
```

Edit `.env` and set your configuration:
```
PORT=3000
JWT_SECRET=your_secure_jwt_secret_key
NODE_ENV=development
```

4. Start the server:
```bash
# Development mode with auto-reload
npm run dev

# Production mode
npm start
```

The server will start on `http://localhost:3000`

## API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "role": "developer"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Get Profile
```http
GET /api/auth/profile
Authorization: Bearer <token>
```

### Project Endpoints

#### Create Project (Manager/Admin only)
```http
POST /api/projects
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Project Name",
  "description": "Project description",
  "status": "planning",
  "startDate": "2024-01-01",
  "endDate": "2024-12-31"
}
```

#### Get All Projects
```http
GET /api/projects
Authorization: Bearer <token>
```

#### Get Project by ID
```http
GET /api/projects/:id
Authorization: Bearer <token>
```

#### Update Project (Manager/Admin only)
```http
PUT /api/projects/:id
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Updated Project Name",
  "status": "active"
}
```

#### Delete Project (Manager/Admin only)
```http
DELETE /api/projects/:id
Authorization: Bearer <token>
```

### Task Endpoints

#### Create Task
```http
POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{
  "title": "Task Title",
  "description": "Task description",
  "status": "todo",
  "priority": "high",
  "projectId": 1,
  "assignedTo": 2,
  "dueDate": "2024-12-31"
}
```

#### Get All Tasks
```http
GET /api/tasks?projectId=1
Authorization: Bearer <token>
```

#### Get Task by ID
```http
GET /api/tasks/:id
Authorization: Bearer <token>
```

#### Update Task
```http
PUT /api/tasks/:id
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "in-progress",
  "priority": "urgent"
}
```

#### Delete Task
```http
DELETE /api/tasks/:id
Authorization: Bearer <token>
```

### Test Case Endpoints

#### Create Test Case
```http
POST /api/test-cases
Authorization: Bearer <token>
Content-Type: application/json

{
  "title": "Test Case Title",
  "description": "Test case description",
  "steps": "1. Step one\n2. Step two",
  "expectedResult": "Expected result description",
  "priority": "high",
  "projectId": 1
}
```

#### Get All Test Cases
```http
GET /api/test-cases?projectId=1
Authorization: Bearer <token>
```

#### Get Test Case by ID
```http
GET /api/test-cases/:id
Authorization: Bearer <token>
```

#### Update Test Case
```http
PUT /api/test-cases/:id
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "passed"
}
```

#### Execute Test Case
```http
POST /api/test-cases/:id/execute
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "passed"
}
```

#### Delete Test Case
```http
DELETE /api/test-cases/:id
Authorization: Bearer <token>
```

## Data Models

### User
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- role (admin, manager, developer, tester)

### Project
- id (Primary Key)
- name
- description
- status (planning, active, on-hold, completed, cancelled)
- startDate
- endDate
- managerId (Foreign Key to User)

### Task
- id (Primary Key)
- title
- description
- status (todo, in-progress, review, testing, done)
- priority (low, medium, high, urgent)
- projectId (Foreign Key to Project)
- assignedTo (Foreign Key to User)
- dueDate

### TestCase
- id (Primary Key)
- title
- description
- steps
- expectedResult
- status (pending, passed, failed, blocked)
- priority (low, medium, high, critical)
- projectId (Foreign Key to Project)
- createdBy (Foreign Key to User)
- lastTestedBy (Foreign Key to User)
- lastTestedAt

## User Roles

- **Admin**: Full access to all resources
- **Manager**: Can create/update/delete projects, full access to tasks and test cases
- **Developer**: Can view projects, manage tasks
- **Tester**: Can view projects, manage test cases

## Error Handling

The API returns appropriate HTTP status codes and error messages:

- `200 OK` - Successful GET/PUT requests
- `201 Created` - Successful POST requests
- `400 Bad Request` - Validation errors
- `401 Unauthorized` - Authentication required or failed
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server errors

## Development

```bash
# Install dependencies
npm install

# Run in development mode
npm run dev

# Run in production mode
npm start
```

## License

ISC

## Author

JopelSimarmata