<html>
    <head>
        <title>Khôi phục mật khẩu</title>
        <link href="public/css/reset.css" rel="stylesheet" type="text/css"/>
        <link href="public/css/login.css" rel="stylesheet" type="text/css"/>
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
            width: 400px;
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
            <h1 class="page-title">KHÔI PHỤC MẬT KHẨU</h1>
            <form id="form-login" action="" method="POST">
                <input type="text" name="email" id="username" value="<?php echo set_value('email') ?>" placeholder="Email">
                <?php echo form_error('email'); ?>
                <input type="submit" name="btn-reset" id="btn-login" value="GỬI YÊU CẦU">
                <?php echo form_error('account'); ?>
            </form>
            <a href="<?php echo base_url("?mod=users&action=login"); ?>" id="lost-pass">Đăng nhập</a>
            <a href="<?php echo base_url("?mod=users&action=reg"); ?>" id="lost-pass">Đăng ký</a>
        </div>
    </body>
</html>