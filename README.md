RightClick (Laravel Project)



Requirements - PHP (recommended 8.x) - Composer - Node.js + npm - MySQL

/ MariaDB - Git



Installation



Clone the repository: git clone

https://github.com/sutsengdu/rightclick.git rightclick cd rightclick



Install backend dependencies: composer install



Install frontend dependencies and build assets: npm install \&\& npm run build



Create environment file: cp .env.example .env



Generate app key: php artisan key:generate



Create storage symlink: php artisan storage:link



Database Setup



1\.  Create a database named: rightclick



2\.  Import the SQL file: mysql -u YOUR\_DB\_USER -p rightclick < rightclick.sql



3\.  Update .env: DB\_DATABASE=rightclick DB\_USERNAME=YOUR\_DB\_USER DB\_PASSWORD=YOUR\_DB\_PASSWORD



Run the Project: php artisan serve



Open in browser: Main site: http://127.0.0.1:8000 Admin panel: http://127.0.0.1:8000/admin



Admin Login: Username: admin Password: admin

