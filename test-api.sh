#!/bin/bash

# API Sistem Manajemen Proyek dan Testing - API Test Script
# This script demonstrates how to interact with the API

API_URL="http://localhost:3000"

echo "=== API Sistem Manajemen Proyek dan Testing - Test Script ==="
echo ""

# 1. Register a manager
echo "1. Registering a manager..."
MANAGER_RESPONSE=$(curl -s -X POST "$API_URL/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "manager1",
    "email": "manager@example.com",
    "password": "password123",
    "role": "manager"
  }')
echo "$MANAGER_RESPONSE" | grep -o '"token":"[^"]*' | sed 's/"token":"//' > /tmp/manager_token.txt
MANAGER_TOKEN=$(cat /tmp/manager_token.txt)
echo "Manager registered. Token: ${MANAGER_TOKEN:0:20}..."
echo ""

# 2. Register a developer
echo "2. Registering a developer..."
DEV_RESPONSE=$(curl -s -X POST "$API_URL/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "developer1",
    "email": "dev@example.com",
    "password": "password123",
    "role": "developer"
  }')
echo "$DEV_RESPONSE" | grep -o '"token":"[^"]*' | sed 's/"token":"//' > /tmp/dev_token.txt
DEV_TOKEN=$(cat /tmp/dev_token.txt)
echo "Developer registered. Token: ${DEV_TOKEN:0:20}..."
echo ""

# 3. Register a tester
echo "3. Registering a tester..."
TESTER_RESPONSE=$(curl -s -X POST "$API_URL/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "tester1",
    "email": "tester@example.com",
    "password": "password123",
    "role": "tester"
  }')
echo "Tester registered"
echo ""

# 4. Create a project (as manager)
echo "4. Creating a project..."
PROJECT_RESPONSE=$(curl -s -X POST "$API_URL/api/projects" \
  -H "Authorization: Bearer $MANAGER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "E-Commerce Platform",
    "description": "Building a modern e-commerce platform",
    "status": "active",
    "startDate": "2024-01-01",
    "endDate": "2024-12-31"
  }')
echo "$PROJECT_RESPONSE"
echo ""

# 5. Get all projects
echo "5. Getting all projects..."
curl -s -X GET "$API_URL/api/projects" \
  -H "Authorization: Bearer $MANAGER_TOKEN"
echo ""
echo ""

# 6. Create a task
echo "6. Creating a task..."
TASK_RESPONSE=$(curl -s -X POST "$API_URL/api/tasks" \
  -H "Authorization: Bearer $MANAGER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Implement user authentication",
    "description": "Develop JWT-based authentication system",
    "status": "todo",
    "priority": "high",
    "projectId": 1,
    "dueDate": "2024-03-15"
  }')
echo "$TASK_RESPONSE"
echo ""

# 7. Get all tasks
echo "7. Getting all tasks..."
curl -s -X GET "$API_URL/api/tasks?projectId=1" \
  -H "Authorization: Bearer $MANAGER_TOKEN"
echo ""
echo ""

# 8. Create a test case
echo "8. Creating a test case..."
TEST_CASE_RESPONSE=$(curl -s -X POST "$API_URL/api/test-cases" \
  -H "Authorization: Bearer $MANAGER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Login functionality test",
    "description": "Test user login with valid credentials",
    "steps": "1. Navigate to login page\n2. Enter valid credentials\n3. Click login button",
    "expectedResult": "User should be redirected to dashboard",
    "priority": "high",
    "projectId": 1
  }')
echo "$TEST_CASE_RESPONSE"
echo ""

# 9. Get all test cases
echo "9. Getting all test cases..."
curl -s -X GET "$API_URL/api/test-cases?projectId=1" \
  -H "Authorization: Bearer $MANAGER_TOKEN"
echo ""
echo ""

# 10. Execute test case
echo "10. Executing test case..."
curl -s -X POST "$API_URL/api/test-cases/1/execute" \
  -H "Authorization: Bearer $MANAGER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "passed"
  }'
echo ""
echo ""

echo "=== Test Script Completed ==="
echo "Tokens saved in /tmp/ directory for further testing"

# Clean up
rm -f /tmp/manager_token.txt /tmp/dev_token.txt
