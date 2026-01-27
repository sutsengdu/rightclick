# RightClick (Esports Management Software)

## Requirements
Make sure you have:
- PHP (recommended 8.x)
- Composer
- Node.js + npm
- MySQL / MariaDB
- Git

## Installation

### Clone the repository:

git clone https://github.com/sutsengdu/rightclick.git rightclick
cd rightclick


### Install backend dependencies:

composer install


### Install frontend dependencies and build assets:

npm install
npm run build


### Create environment file:

cp .env.example .env


### Generate app key:

php artisan key:generate


### Create storage symlink:

php artisan storage:link


## Database Setup

Create a database named:

rightclick


Import the SQL file into the database:

Find the file: rightclick.sql

Import it using phpMyAdmin or CLI:

mysql -u YOUR_DB_USER -p rightclick < rightclick.sql

Update your .env database credentials:

DB_DATABASE=rightclick

DB_USERNAME=YOUR_DB_USER

DB_PASSWORD=YOUR_DB_PASSWORD

Run the Project


## Start the Laravel development server:

php artisan serve


## Open in browser:

Main site: http://127.0.0.1:8000
Admin panel: http://127.0.0.1:8000/admin

## Admin Login
Use the following credentials:

Username: admin
Password: admin
