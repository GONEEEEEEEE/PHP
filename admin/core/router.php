<?php

session_start();
ob_start();
//Triệu gọi đến file xử lý thông qua request

$request_path = MODULESPATH . DIRECTORY_SEPARATOR . get_module() . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . get_controller().'Controller.php';

if (file_exists($request_path)) {
    require $request_path;
} else {
    echo "Không tìm thấy:$request_path ";
}

$action_name = get_action().'Action';

call_function(array('construct', $action_name));


if(!is_login() && get_controller() != 'users' && get_action() != 'login'){
    redirect_to("?mod=users&action=login");
}



// Xóa tài khoản khi không kích hoạt tài khoản sau 24H

// db_delete("tbl_users", "`created_at` < NOW() - INTERVAL 1 DAY AND `is_active` = '0'");
