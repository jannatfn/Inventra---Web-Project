Project Overview: Inventra is a simple web-based Inventory Management System built using PHP, MySQL, and JavaScript.
It helps users manage products, track stock levels, and view important inventory data from a dashboard.

This project was developed as part of a Web Programming course to practice full-stack development including frontend, backend, and database integration.

Key Features
User Registration & Login (Authentication system)
Secure password hashing
Dashboard with inventory overview
Add new products
View all products in a table
Edit product details (name, price, quantity)
Delete products
Low stock alert system
Responsive UI (works on mobile & desktop)

Tech Stack
Frontend: HTML, CSS, Bootstrap, JavaScript (Fetch API)
Backend: PHP (OOP)
Database: MySQL
Server: XAMPP


Setup Instructions :

Follow these steps to run the project:
Install XAMPP and start Apache and MySQL
Copy project folder (Inventra) into:
                              C:\xampp\htdocs\

Open phpMyAdmin
Create a database:

                                 inventra_db

Import the SQL file:
Go to Import
          Select schema.sql
Click Go

Open:

                               config/config.php
Update credentials:
                            define('DB_HOST', 'localhost');
                            define('DB_NAME', 'inventra_db');
                            define('DB_USER', 'root');
                            define('DB_PASS', '');

Run the project in browser:

 Now open your browser and go to:
               ->  http://localhost/Inventra/public/login.php
Then press Ctrl + F5 to do a hard refresh and clear any cached errors.

Common Errors & Fixes
Unknown database error
 → Make sure you created the database exactly as inventra_db
Access denied for user 'root'
 → You probably set a password for MySQL
 → Add it in config.php
Connection refused
 → MySQL is not running
 → Start it from XAMPP






