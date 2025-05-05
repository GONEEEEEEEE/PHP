# ISMART - Hệ Thống Bán Hàng Điện Tử

## 🛒 Giới thiệu

ISMART là một website bán hàng điện tử mô phỏng, được xây dựng nhằm mục tiêu học tập và thực hành các kỹ thuật lập trình web sử dụng PHP theo mô hình MVC và cơ sở dữ liệu MySQL. Dự án cho phép người dùng xem sản phẩm, đặt hàng, quản lý giỏ hàng và quản lý đơn hàng thông qua giao diện người dùng trực quan.

---

## 🧰 Công nghệ sử dụng

- **Ngôn ngữ lập trình:** PHP (thuần)
- **Mô hình kiến trúc:** MVC (Model - View - Controller)
- **Cơ sở dữ liệu:** MySQL
- **HTML/CSS/JavaScript** cho phần giao diện người dùng
- **Thư viện hỗ trợ:** jQuery, Bootstrap (tuỳ chọn)

---

## Cài đặt và cách chạy dự án
### 🧾 Yêu cầu hệ thống

- ✅ PHP >= 7.2
- ✅ MySQL hoặc MariaDB
- ✅ Apache (hoặc XAMPP, Laragon, WAMP,...)
- ✅ Trình duyệt web (Chrome, Firefox, ...)
- ✅ Trình quản lý cơ sở dữ liệu (phpMyAdmin, DBeaver, ...)

---
### 🛠️ Các bước cài đặt
1. **Tải mã nguồn về máy**
   - Clone repository hoặc tải file ZIP:
     ```bash
     git clone https://github.com/GONEEEEEEEE/PHP
     ```
   - Hoặc giải nén file và đặt vào thư mục `htdocs` (nếu dùng XAMPP) hoặc `www` (nếu dùng WAMP).
   - 
2. **Tạo cơ sở dữ liệu**
   - Truy cập `phpMyAdmin` tại `http://localhost/phpmyadmin`
   - Tạo một cơ sở dữ liệu mới với tên: `ismart`
   - Import file `database/ismart.sql` có sẵn trong dự án
  
3. **Cấu hình kết nối CSDL**
   - Mở file: `config/database.php`
   - Thay đổi thông tin cấu hình cho phù hợp:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');       // Tên người dùng MySQL
     define('DB_PASS', '');           // Mật khẩu MySQL (để trống nếu dùng XAMPP mặc định)
     define('DB_NAME', 'ismart');     // Tên database vừa tạo
     ```
4. **Bật mod_rewrite (nếu chưa bật)**
   - Với XAMPP:
     - Mở file `httpd.conf` và đảm bảo đã bỏ comment dòng:  
       ```
       LoadModule rewrite_module modules/mod_rewrite.so
       ```
     - Kiểm tra file `.htaccess` trong thư mục gốc đã tồn tại với nội dung:
       ```apacheconf
       RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       Rewrite
  5. **Khởi chạy dự án**
   - Mở trình duyệt và truy cập:
     ```
     http://localhost/ismart/
     ```

---

## 📁 Cấu trúc thư mục dự án ISMART

- **config/**: chứa các file cấu hình kết nối với cơ sở dữ liệu và các hằng số hệ thống  
- **controllers/**: xử lý các request từ người dùng và điều hướng dữ liệu đến view hoặc model  
- **models/**: định nghĩa các hàm thao tác với cơ sở dữ liệu (select, insert, update, delete)  
- **views/**: chứa các file giao diện (HTML, PHP hiển thị ra trình duyệt)  
- **public/**: chứa các tài nguyên tĩnh như ảnh, CSS, JavaScript  
- **routes/** (nếu có): định nghĩa các đường dẫn (URL) ánh xạ đến controller tương ứng  
- **libs/**: chứa các thư viện xử lý như database, session, validation, upload, …  
- **helpers/**: chứa các hàm tiện ích dùng lại nhiều nơi trong dự án  
- **.htaccess**: cấu hình URL thân thiện (rewrite)  
- **index.php**: điểm bắt đầu của ứng dụng, định tuyến request đến controller phù hợp  
- **database/**: chứa file SQL sao lưu cơ sở dữ liệu  
- **README.md**: mô tả chi tiết dự án  
