# API Documentation - Sistem Manajemen Proyek dan Testing

## Base URL
```
http://localhost:3000
```

## Authentication

All protected endpoints require a JWT token in the Authorization header:
```
Authorization: Bearer <your_token>
```

## Response Format

### Success Response
```json
{
  "message": "Success message",
  "data": { ... }
}
```

### Error Response
```json
{
  "error": "Error message",
  "details": [ ... ]
}
```

## Endpoints

### 1. Authentication

#### 1.1 Register User
**POST** `/api/auth/register`

**Request Body:**
```json
{
  "username": "string (3-50 chars, required, unique)",
  "email": "string (valid email, required, unique)",
  "password": "string (required)",
  "role": "string (admin|manager|developer|tester, optional, default: developer)"
}
```

**Response:** `201 Created`
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "role": "developer"
  },
  "token": "jwt_token_here"
}
```

#### 1.2 Login
**POST** `/api/auth/login`

**Request Body:**
```json
{
  "email": "string (required)",
  "password": "string (required)"
}
```

**Response:** `200 OK`
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "role": "developer"
  },
  "token": "jwt_token_here"
}
```

#### 1.3 Get Profile
**GET** `/api/auth/profile`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`
```json
{
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "role": "developer"
  }
}
```

---

### 2. Projects

#### 2.1 Create Project
**POST** `/api/projects`

**Headers:** `Authorization: Bearer <token>`

**Access:** Admin, Manager

**Request Body:**
```json
{
  "name": "string (3-100 chars, required)",
  "description": "string (optional)",
  "status": "string (planning|active|on-hold|completed|cancelled, optional, default: planning)",
  "startDate": "string (YYYY-MM-DD, optional)",
  "endDate": "string (YYYY-MM-DD, optional)",
  "managerId": "integer (optional, defaults to current user)"
}
```

**Response:** `201 Created`
```json
{
  "message": "Project created successfully",
  "project": {
    "id": 1,
    "name": "E-Commerce Platform",
    "description": "Building a modern e-commerce platform",
    "status": "active",
    "startDate": "2024-01-01",
    "endDate": "2024-12-31",
    "managerId": 1,
    "createdAt": "2024-01-01T00:00:00.000Z",
    "updatedAt": "2024-01-01T00:00:00.000Z"
  }
}
```

#### 2.2 Get All Projects
**GET** `/api/projects`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`
```json
{
  "projects": [
    {
      "id": 1,
      "name": "E-Commerce Platform",
      "description": "Building a modern e-commerce platform",
      "status": "active",
      "startDate": "2024-01-01",
      "endDate": "2024-12-31",
      "managerId": 1,
      "manager": {
        "id": 1,
        "username": "manager1",
        "email": "manager@example.com"
      },
      "createdAt": "2024-01-01T00:00:00.000Z",
      "updatedAt": "2024-01-01T00:00:00.000Z"
    }
  ]
}
```

#### 2.3 Get Project by ID
**GET** `/api/projects/:id`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`
```json
{
  "project": {
    "id": 1,
    "name": "E-Commerce Platform",
    "description": "Building a modern e-commerce platform",
    "status": "active",
    "startDate": "2024-01-01",
    "endDate": "2024-12-31",
    "managerId": 1,
    "manager": {
      "id": 1,
      "username": "manager1",
      "email": "manager@example.com"
    },
    "tasks": [...],
    "testCases": [...],
    "createdAt": "2024-01-01T00:00:00.000Z",
    "updatedAt": "2024-01-01T00:00:00.000Z"
  }
}
```

#### 2.4 Update Project
**PUT** `/api/projects/:id`

**Headers:** `Authorization: Bearer <token>`

**Access:** Admin, Manager

**Request Body:** (all fields optional)
```json
{
  "name": "string",
  "description": "string",
  "status": "string",
  "startDate": "string",
  "endDate": "string"
}
```

**Response:** `200 OK`
```json
{
  "message": "Project updated successfully",
  "project": { ... }
}
```

#### 2.5 Delete Project
**DELETE** `/api/projects/:id`

**Headers:** `Authorization: Bearer <token>`

**Access:** Admin, Manager

**Response:** `200 OK`
```json
{
  "message": "Project deleted successfully"
}
```

---

### 3. Tasks

#### 3.1 Create Task
**POST** `/api/tasks`

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "title": "string (3-200 chars, required)",
  "description": "string (optional)",
  "status": "string (todo|in-progress|review|testing|done, optional, default: todo)",
  "priority": "string (low|medium|high|urgent, optional, default: medium)",
  "projectId": "integer (required)",
  "assignedTo": "integer (optional)",
  "dueDate": "string (YYYY-MM-DD, optional)"
}
```

**Response:** `201 Created`
```json
{
  "message": "Task created successfully",
  "task": {
    "id": 1,
    "title": "Implement user authentication",
    "description": "Develop JWT-based authentication system",
    "status": "todo",
    "priority": "high",
    "projectId": 1,
    "assignedTo": 2,
    "dueDate": "2024-03-15",
    "createdAt": "2024-01-01T00:00:00.000Z",
    "updatedAt": "2024-01-01T00:00:00.000Z"
  }
}
```

#### 3.2 Get All Tasks
**GET** `/api/tasks?projectId=1`

**Headers:** `Authorization: Bearer <token>`

**Query Parameters:**
- `projectId` (optional): Filter tasks by project

**Response:** `200 OK`
```json
{
  "tasks": [
    {
      "id": 1,
      "title": "Implement user authentication",
      "description": "Develop JWT-based authentication system",
      "status": "todo",
      "priority": "high",
      "projectId": 1,
      "assignedTo": 2,
      "dueDate": "2024-03-15",
      "assignee": {
        "id": 2,
        "username": "developer1",
        "email": "dev@example.com"
      },
      "project": {
        "id": 1,
        "name": "E-Commerce Platform"
      },
      "createdAt": "2024-01-01T00:00:00.000Z",
      "updatedAt": "2024-01-01T00:00:00.000Z"
    }
  ]
}
```

#### 3.3 Get Task by ID
**GET** `/api/tasks/:id`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`

#### 3.4 Update Task
**PUT** `/api/tasks/:id`

**Headers:** `Authorization: Bearer <token>`

**Request Body:** (all fields optional)
```json
{
  "title": "string",
  "description": "string",
  "status": "string",
  "priority": "string",
  "assignedTo": "integer",
  "dueDate": "string"
}
```

**Response:** `200 OK`

#### 3.5 Delete Task
**DELETE** `/api/tasks/:id`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`

---

### 4. Test Cases

#### 4.1 Create Test Case
**POST** `/api/test-cases`

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "title": "string (3-200 chars, required)",
  "description": "string (optional)",
  "steps": "string (optional)",
  "expectedResult": "string (optional)",
  "status": "string (pending|passed|failed|blocked, optional, default: pending)",
  "priority": "string (low|medium|high|critical, optional, default: medium)",
  "projectId": "integer (required)"
}
```

**Response:** `201 Created`
```json
{
  "message": "Test case created successfully",
  "testCase": {
    "id": 1,
    "title": "Login functionality test",
    "description": "Test user login with valid credentials",
    "steps": "1. Navigate to login page\n2. Enter valid credentials\n3. Click login button",
    "expectedResult": "User should be redirected to dashboard",
    "status": "pending",
    "priority": "high",
    "projectId": 1,
    "createdBy": 1,
    "lastTestedBy": null,
    "lastTestedAt": null,
    "createdAt": "2024-01-01T00:00:00.000Z",
    "updatedAt": "2024-01-01T00:00:00.000Z"
  }
}
```

#### 4.2 Get All Test Cases
**GET** `/api/test-cases?projectId=1`

**Headers:** `Authorization: Bearer <token>`

**Query Parameters:**
- `projectId` (optional): Filter test cases by project

**Response:** `200 OK`
```json
{
  "testCases": [
    {
      "id": 1,
      "title": "Login functionality test",
      "description": "Test user login with valid credentials",
      "steps": "1. Navigate to login page\n2. Enter valid credentials\n3. Click login button",
      "expectedResult": "User should be redirected to dashboard",
      "status": "pending",
      "priority": "high",
      "projectId": 1,
      "createdBy": 1,
      "creator": {
        "id": 1,
        "username": "tester1",
        "email": "tester@example.com"
      },
      "lastTester": null,
      "project": {
        "id": 1,
        "name": "E-Commerce Platform"
      },
      "createdAt": "2024-01-01T00:00:00.000Z",
      "updatedAt": "2024-01-01T00:00:00.000Z"
    }
  ]
}
```

#### 4.3 Get Test Case by ID
**GET** `/api/test-cases/:id`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`

#### 4.4 Update Test Case
**PUT** `/api/test-cases/:id`

**Headers:** `Authorization: Bearer <token>`

**Request Body:** (all fields optional)
```json
{
  "title": "string",
  "description": "string",
  "steps": "string",
  "expectedResult": "string",
  "status": "string",
  "priority": "string"
}
```

**Response:** `200 OK`

#### 4.5 Execute Test Case
**POST** `/api/test-cases/:id/execute`

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "status": "string (passed|failed|blocked, required)"
}
```

**Response:** `200 OK`
```json
{
  "message": "Test case executed successfully",
  "testCase": {
    "id": 1,
    "status": "passed",
    "lastTestedBy": 3,
    "lastTestedAt": "2024-01-15T10:30:00.000Z",
    ...
  }
}
```

#### 4.6 Delete Test Case
**DELETE** `/api/test-cases/:id`

**Headers:** `Authorization: Bearer <token>`

**Response:** `200 OK`

---

## Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Authentication required or failed
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

## Common Error Responses

### 401 Unauthorized
```json
{
  "error": "Authentication required"
}
```

### 403 Forbidden
```json
{
  "error": "Access denied"
}
```

### 404 Not Found
```json
{
  "error": "Resource not found"
}
```

### 400 Validation Error
```json
{
  "error": "Validation error",
  "details": [
    {
      "field": "email",
      "message": "Validation isEmail on email failed"
    }
  ]
}
```
