A simple PHP-based REST API for task management, built with PHP, MySQL and JWT Authentication. This API allows users to create, read, update, and delete tasks with secure authentication.

Features
CRUD Operations: Create, Read, Update, and Delete tasks.
Database Integration: Store tasks and user information in MySQL.
User Authentication: Register and login with JWT tokens.
Jest Testing 

API Endpoints:
POST /register - Register a new user
POST /login - User login (returns JWT token)
GET /tasks - Get all tasks (requires authentication)
GET /tasks/{id} - Get a single task by ID (requires authentication)
POST /tasks - Create a new task (requires authentication)
PUT /tasks/{id} - Update a task (requires authentication)
DELETE /tasks/{id} - Delete a task (requires authentication)

Installation
Prerequisites
PHP >= 7.4
MySQL
Postman (for API testing)
Jest (for testing)
