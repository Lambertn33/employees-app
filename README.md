# Employees Attendance & Reporting System

A lightweight, API-driven employee attendance management system with daily reporting in PDF and Excel formats.

## Overview

The Employees Attendance & Reporting System is a backend-focused web application designed to help organizations track employee attendance (arrival and departure), manage employee records, and generate daily attendance reports.

The system emphasizes clean architecture, strong validation, role-based authorization, and testability. It supports real-world workflows such as preventing duplicate attendances, daily presence tracking, and exporting attendance data for administrative use.

## Features

- **Authentication & Authorization**
    - Secure API authentication using Laravel Sanctum
    - Role-based access control (Admin vs authenticated users)
- **Employee Management**
    - Create, update, list, and delete employees (admin-only actions)
    - Unique employee codes (auto-generated)
    - Phone number validation with strict formatting
- **Attendance Tracking**
    - Employee arrival (check-in)
    - Employee departure (check-out)
    - Prevention of duplicate open attendances
    - Daily attendance enforcement
- **Attendance Reports**
    - Daily attendance report generation
    - PDF export using wkhtmltopdf (Snappy)
    - Excel export using Laravel Excel
- **Filtering**
    - Reports by date
    - Optional employee-specific filtering
- **Testing**
    - Feature tests for authentication, attendance, and reports
    - Fake PDF and Excel generation for reliable testing

## Technology Stack

### Backend

- **Laravel 12**
- **PHP 8.4**
- **MySQL 8**
- **Laravel Sanctum** – Authentication
- **Laravel Policies** – Authorization

### Reporting

- **barryvdh/laravel-snappy** – PDF generation
- **wkhtmltopdf** – PDF rendering engine
- **maatwebsite/excel** – Excel exports

### Infrastructure

- **Docker & Docker Compose**
- **Redis** – Cache, sessions, queues
- **Mailpit** – Email testing

### Testing

- **PHPUnit**
- **Laravel HTTP & Database testing utilities**
- **PDF::fake() & Excel::fake()**

## Prerequisites

- Docker and Docker Compose
- Git

> ⚠️ `wkhtmltopdf` is installed inside the Docker container and is **not required on the host machine**.

## Quick Start

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd employees-app
    ```

2. \*\*Start the application

````bash
    docker-compose up --build
    ```

3. **Access the API

  API Base URL: http://localhost:8000
  Mailpit UI: http://localhost:8025
````
