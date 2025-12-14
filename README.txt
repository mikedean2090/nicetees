NiceTees (PHP + MySQL) - XAMPP Quick Run (Windows)

1) Copy the whole 'nicetees' folder to:
   C:\xampp\htdocs\nicetees

2) Start Apache + MySQL in XAMPP Control Panel.

3) Import database:
   - Open: http://localhost/phpmyadmin
   - Import 'database.sql'

4) Open site:
   - Customer: http://localhost/nicetees/public/index.php
   - Admin:    http://localhost/nicetees/admin/login.php
     Email: admin@nicetees.com
     Pass:  admin123

If your admin login fails, run this in phpMyAdmin SQL:
UPDATE users SET password_hash = '<new_hash>' WHERE email='admin@nicetees.com';
(Generate hash with: echo password_hash('admin123', PASSWORD_DEFAULT); in a temp php file.)
