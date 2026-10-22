MINI MART POS - PHP + MySQL + Bootstrap 5
==========================================

FEATURES
- Login / logout (admin)
- Dashboard with product, category, order, and revenue totals
- Category create and delete
- Product create, edit, delete, and search
- Point of Sale (sell products with quantities)
- Stock validation and automatic stock reduction after sale
- Order history and printable receipt
- Uses PDO prepared statements and Bootstrap CDN
- Database relationships match the supplied diagram

INSTALLATION (XAMPP)
1. Extract the mini_mart_pos folder into:
   C:\xampp\htdocs\mini_mart_pos
2. Start Apache and MySQL in XAMPP.
3. Check your MySQL port in config/db.php and setup.php:
   - Use 3307 if your MySQL runs on port 3307
   - Use 3306 if your MySQL runs on port 3306
4. If your root account has a password, update $password in both files.
5. Open:
   http://localhost/mini_mart_pos/setup.php
6. Login at:
   http://localhost/mini_mart_pos/login.php
   Username: admin
   Password: admin123
7. Delete setup.php after setup is successful.

IMPORTANT
- Add categories first, then products.
- The database name is point_of_sale.
- This starter project stores prices in USD. You can change the currency display in includes/functions.php.
- Bootstrap and Font Awesome load from CDNs, so internet access is needed for those styles/icons.
- Change the default password before using this project beyond local practice.
- This is a learning starter project; add CSRF protection, user management, and stricter production security before deployment.
