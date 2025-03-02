# PHP Flight API with JWT Authentication

This project is a RESTful API built using the **Flight PHP framework**. It provides user authentication and authorization using **JSON Web Tokens (JWT)**. The API supports user registration, login, token refresh, and role-based access control.

## Features

- **User Authentication**:
  - Register new users.
  - Login with email and password.
  - Generate JWT access tokens.
  - Refresh access tokens using refresh tokens.

- **Role-Based Authorization**:
  - Users are assigned roles (e.g., `user`, `admin`).
  - Access to certain endpoints is restricted based on roles.

- **Security**:
  - Password hashing using `password_hash`.
  - Refresh tokens stored in HTTP-only cookies.
  - Protection against CSRF and XSS attacks.

- **Database**:
  - MySQL database for storing users, roles, and refresh tokens.
  - Example SQL schema provided.

- **Real-Time Features** (Soon):
  - WebSocket support for real-time communication (e.g., chat).
  - Notifications for user status (online/offline).

## Getting Started

### Prerequisites

- PHP 7.4 or higher.
- MySQL database.
- Composer (for dependency management).

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/ladislavbelov/php-flight-messenger.git
   cd your-repo-name
2. Install dependencies:
   composer install
3. Set up the database:
   Create a MySQL database.
   Import the SQL schema from database/schema.sql.
4. Configure environment variables:
  Update the .env file with your database credentials and JWT secret
5. Start the development server:
   php -S localhost:8000

### API Endpoints

  POST /register: Register a new user.
  POST /login: Authenticate a user and generate tokens.
  POST /refresh-tokens: Refresh access and refresh tokens.
  POST /logout: Invalidate the refresh token.
  GET /protected: Example of a protected endpoint (requires valid JWT).
