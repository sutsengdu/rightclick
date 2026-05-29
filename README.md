# RightClick (Esports Management Software)

RightClick is a comprehensive Esports Cafe & Gaming Arena Management Software built with Laravel. It helps venue administrators manage play sessions, track PC/console seat availability, handle food & beverage inventories, log expenses, and coordinate bookings/reservations from a unified administrative dashboard.

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.1-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL Database](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)

---

## Key Features

- **Session Records & Billing**: Track active gaming sessions, calculate rental costs dynamically, and manage member charges.
- **Seat & Station Management**: Configure PC/console seats, monitor status (online/offline), and manage layouts.
- **Inventory & F&B POS**: Sell snacks, beverages, or gaming peripherals with automatic stock depletion and inventory replenishment controls.
- **Outcomes (Expenses)**: Log daily cafe operational expenses for financial transparency.
- **Reservations & Booking**: Manage seat bookings and reservations for tournaments or casual sessions.
- **Admin Dashboard**: A robust, built-in control panel powered by `encore/laravel-admin` to manage all business aspects.
- **RESTful API**: Ready-to-use API endpoints for custom frontend interfaces. See [API Documentation](API_DOCUMENTATION.md).

---

## Requirements

Before installing, make sure you have:
- **PHP** >= 8.1 (recommended 8.x)
- **Composer** (PHP dependency manager)
- **Node.js** & **npm** (frontend assets compilation)
- **MySQL** / **MariaDB**
- **Git**

---

## Installation & Setup

Follow these steps to set up RightClick locally:

### 1. Clone the Repository
```bash
git clone https://github.com/sutsengdu/rightclick.git rightclick
cd rightclick
```

### 2. Install Dependencies
**Backend Dependencies:**
```bash
composer install
```

**Frontend Dependencies:**
```bash
npm install
```

### 3. Environment Configuration
Copy the sample environment file to create your `.env` configuration:
```bash
cp .env.example .env
```

Generate the unique application encryption key:
```bash
php artisan key:generate
```

### 4. Create Storage Symlink
Link the public storage directory to enable file/image uploads:
```bash
php artisan storage:link
```

---

## Database Setup

1. Create a blank database in MySQL/MariaDB named:
   ```sql
   CREATE DATABASE rightclick;
   ```

2. Import the initial database schema and seed data. Using command line (or phpMyAdmin):
   ```bash
   mysql -u YOUR_DB_USER -p rightclick < rightclick.sql
   ```

3. Update the database credentials inside your `.env` file:
   ```env
   DB_DATABASE=rightclick
   DB_USERNAME=YOUR_DB_USER
   DB_PASSWORD=YOUR_DB_PASSWORD
   ```

---

## Running the Application

### 1. Compile Assets
Build the frontend assets using Vite:
```bash
npm run build
```
*(Or run `npm run dev` if you are modifying frontend files)*

### 2. Start the Development Server
```bash
php artisan serve
```

### 3. Open in Browser
- **Main Site**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Dashboard**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

### Admin Credentials
Log in to the administrator control panel with:
- **Username**: `admin`
- **Password**: `admin`
