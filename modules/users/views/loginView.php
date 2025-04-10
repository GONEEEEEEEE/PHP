<!DOCTYPE html>

<html>
<head>
    <title>Trang đăng nhập</title>
</head>
<style>
    body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #wp-form-login {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #ff5722;
            outline: none;
        }

        #btn-login {
            width: 100%;
            padding: 10px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        #btn-login:hover {
            background-color: #e64a19;
        }

        #lost-pass {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #ff5722;
            text-decoration: none;
        }

        #lost-pass:hover {
            text-decoration: underline;
        }
</style>
<body>
    <div id="wp-form-login">
        <h1 class="page-title">ĐĂNG NHẬP</h1>
        <form id="form-login" action="" method="POST">
            <input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" placeholder="Username" />
            <?php echo form_error('username'); ?>
            
            <input type="password" name="password" id="password" value="" placeholder="Password" autocomplete="false" />
            <?php echo form_error('password'); ?>
            
            <input type="submit" id="btn-login" name="btn-login" value="ĐĂNG NHẬP" />
            <?php echo form_error('account'); ?>
        </form>
        
        <a href="<?php echo base_url('?mod=users&action=reset'); ?>" id="lost-pass">Quên mật khẩu</a>
        <a href="<?php echo base_url('?mod=users&action=reg'); ?>" id="lost-pass">Đăng ký</a>
    </div>
    
    <script>
        // JavaScript để tăng tính tương tác
        document.getElementById('form-login').addEventListener('submit', function (e) {
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();
            let isValid = true;

            // Kiểm tra username
            if (username === "") {
                document.getElementById('username-error').textContent = "Vui lòng nhập tên đăng nhập";
                isValid = false;
            } else {
                document.getElementById('username-error').textContent = "";
            }

            // Kiểm tra password
            if (password === "") {
                document.getElementById('password-error').textContent = "Vui lòng nhập mật khẩu";
                isValid = false;
            } else {
                document.getElementById('password-error').textContent = "";
            }

            // Nếu có lỗi, ngăn form submit
            if (!isValid) {
                e.preventDefault();
            } else {
                // Hiệu ứng loading khi submit
                document.getElementById('btn-login').value = "Đang đăng nhập...";
                document.getElementById('btn-login').disabled = true;
            }
        });

        // Hiệu ứng khi hover vào nút đăng nhập
        document.getElementById('btn-login').addEventListener('mouseover', function () {
            this.style.transform = "scale(1.05)";
        });

        document.getElementById('btn-login').addEventListener('mouseout', function () {
            this.style.transform = "scale(1)";
        });
    </script>
</body>
</html>
