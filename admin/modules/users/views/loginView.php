<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/reset.css" />
    <link rel="stylesheet" href="public/login.css" />
    <script src="public/js/jquery-2.2.4.min.js"></script>
    <script src="public/js/main.js"></script>
    <title>Đăng nhập</title>
</head>

<body>
    <div id="wp-form-login">
        <h1 id="page-title">ĐĂNG NHẬP</h1>
        <form action="" method="post" id="form-login">
            <input type="text" name="username" id="username" placeholder="Username" />
            <?php form_error('username'); ?>
            <input type="password" name="password" id="password" placeholder="Password" />
            <?php form_error('password'); ?>
            <input type="submit" name="btn-login" id="btn-login" value="Đăng nhập" />
            <?php form_error('account'); ?>
        </form>
        <a href="?mod=users&action=reset" id="lost-pass">Quên mật khẩu</a>
    </div>

</body>

</html>