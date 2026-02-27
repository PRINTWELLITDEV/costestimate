<p align="center">
<a href="#">
<!-- <img src="/public/uploads/img/logo-costestimate-transparent.png" width="400" alt="CostEstimate Logo"> -->
</a>

# CostEstimate - Cost Estimate Management System
</p>

CostEstimate is a web-based application designed to efficiently manage cost estimates for Printwell, Inc. and its affiliates.

## Features

- User management with profile pictures, roles, and access levels
- Project and client management with detailed information
- Responsive dashboard and data tables
- Modal-based CRUD operations
- Secure authentication and session management
- Real-time search and filtering
- Environment configuration via `.env.example`
- Mobile-friendly interface
- Temporary form caching for user convenience

## Technologies Used

- Laravel (PHP Framework)
- Bootstrap 5 & Bootstrap Icons
- AdminLTE (UI Theme)
- jQuery & DataTables
- SQL Server (with stored procedures)

## Getting Started

1. **Clone the repository**
2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Configure your `.env` file** for database and mail settings.
4. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
5. **Start the development server**
   ```bash
   php artisan serve
   ```

## Environment Configuration

Before running the application, copy the example environment file and update it with your settings:

```bash
cp .env.example .env
```

Open `.env` and configure the following:

- **App Key**:  
  Generate a new application key for security:
  ```bash
  php artisan key:generate
  ```
  This will automatically update the `APP_KEY` value in your `.env` file.

- **Database settings**:  
  Set your database host, port, name, username, and password for each connection (Main, PI-SP, FP-SP, PIGRP-SP).
- **Session and cache settings**:  
  Adjust session, cache, and queue drivers as needed.
- **Mail settings**:  
  Set up your mailer, host, port, username, password, and sender address.
- **Other environment variables**:  
  Update any other values to match your local or production environment.

Refer to `.env.example` for all available configuration options.

## Usage

- Access the dashboard at `/ce`
- Log in using your assigned credentials
- Configure your environment by copying `.env.example` to `.env` and updating the settings
- Add, edit, and delete records using modals
- Search and filter data in tables using the built-in DataTables features
- Use the responsive interface on desktop or mobile devices
- All changes are tracked for audit purposes

## Creators <br>

**Jhon Patrick M. Torres**  <br>
System Analyst Programmer <br>

<!-- ## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT). -->

---
<p align="center" style="font-family: 'Century Schoolbook', serif; font-weight: bold; font-style: italic; font-size: 2rem;">
    <a href="http://www.printwell.com.ph" style="text-decoration: none; color: inherit;">
        <img src="/public/uploads/img/printwell.png" width="400" alt="Printwell Logo">
    </a>
</p>
<br>
Cost Estimate &copy; 2026 Printwell, Inc