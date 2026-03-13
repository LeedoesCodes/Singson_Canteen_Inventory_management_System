# Canteen Management System

A full-stack web application for managing school canteen operations, built with Laravel (backend) and React (frontend). The system supports three user roles: Admin, Cashier, and Customer.

## Features

- **Authentication & Role Management**: Secure login with Laravel Sanctum, role-based routing
- **Menu Management**: Browse menu with category filters, add/edit products with image upload
- **Order Processing**: POS interface, order tracking, status flow, order history
- **Inventory Management**: Stock tracking, low stock alerts, inventory logs, bulk restock
- **Sales Dashboard**: Interactive charts, date range filtering, best-sellers reports
- **User Management**: Admin-only CRUD for cashier/customer accounts

## Technology Stack

### Backend

- Laravel 11
- MySQL
- Laravel Sanctum (API authentication)
- PHP 8.2+

### Frontend

- React 18
- React Router DOM (routing)
- Tailwind CSS (styling)
- Recharts (data visualization)
- Heroicons (icons)
- Context API (state management)

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm (v16 or higher)
- MySQL (or XAMPP/WAMP with MySQL)

### Backend Setup

```bash
# Clone the repository (if using Git)
git clone <your-repo-url>
cd canteen-backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Open .env and update these lines:
# DB_DATABASE=canteen_db
# DB_USERNAME=root
# DB_PASSWORD=

# Create the database in MySQL first, then run:
php artisan migrate --seed

# Create storage link for images (so uploaded files are publicly accessible)
php artisan storage:link

# Start the Laravel server
php artisan serve
# The API will be available at http://localhost:8000
```
