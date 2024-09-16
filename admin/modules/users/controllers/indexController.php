<?php

function construct()
{
    //    echo "Dùng chung, load đầu tiên";
    load_model('index');
    load_model('team');
    load('lib', 'email');
    load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    redirect_to("?mod=users&controller=team&action=index");
    // global $num_users, $num_user_active, $num_user_inactive, $num_user_banned;

    // global $query, $data;
    // $query = isset($_GET['s']) ? $_GET['s'] : '';
    // $status = isset($_GET['status']) ? $_GET['status'] : '';

    // $num_users = 0;
    // $num_user_active = 0;
    // $num_user_inactive = 0;
    // $num_user_banned = 0;
    // if ($query) {
    //     $sql = "SELECT * FROM `tbl_users` WHERE (`username` LIKE '%{$query}%' OR 
    //     `fullname` LIKE '%{$query}%' OR `email` LIKE '%{$query}%' OR 
    //     `tel` LIKE '%{$query}%' OR `address` LIKE '%{$query}%' OR `status` LIKE '%{$query}%')";

    //     if ($status) {
    //         $sql .= " AND `status` = '{$status}'";
    //     }


    //     $result = db_fetch_array($sql);
    //     $num_users = db_num_rows($sql);
    //     $num_user_active = status_users_query($query, 'active');
    //     $num_user_inactive = status_users_query($query, 'inactive');
    //     $num_user_banned = status_users_query($query, 'banned');

    //     $data['result'] = $result;
    //     $data['query'] = $query;
    //     $data['num_users'] = $num_users;
    //     $data['num_user_active'] = $num_user_active;
    //     $data['num_user_inactive'] = $num_user_inactive;
    //     $data['num_user_banned'] = $num_user_banned;
    //     load_view('index', $data);
    //     exit;
    // }
    // $query = "";
    // $result = db_fetch_array("SELECT * FROM `tbl_users`");
    // $num_users = db_num_rows("SELECT * FROM `tbl_users`");
    // $num_user_active = status_users('active');
    // $num_user_inactive = status_users('inactive');
    // $num_user_banned = status_users('banned');

    // $data['query'] = $query;
    // $data['result'] = $result;
    // $data['num_users'] = $num_users;
    // $data['num_user_active'] = $num_user_active;
    // $data['num_user_inactive'] = $num_user_inactive;
    // $data['num_user_banned'] = $num_user_banned;

    // load_view('index', $data);
    // exit;
}

function updateAction()
{
    global $username, $email, $tel, $address, $fullname, $error;
    $data = array();
    $error = array();
    $username = escape_string($_SESSION['username']);
    $info = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username}'");

    if (isset($info['fullname'])) {
        $fullname = $info['fullname'];
        $data['fullname'] = $fullname;
    }

    if (isset($info['email'])) {
        $email = $info['email'];
        $data['email'] = $email;
    }

    if (isset($info['tel'])) {
        $tel = $info['tel'];
        $data['tel'] = $tel;
    }

    if (isset($info['address'])) {
        $address = $info['address'];
        $data['address'] = $address;
    }

    $data['username'] = $username;
    // show_array($info);

    if (isset($_POST['btn-update'])) {

        if (empty($_POST['fullname'])) {
            $error['fullname'] = 'Tên hiển thị không được để trống';
        } else {
            $fullname_new = escape_string($_POST['fullname']);
        }

        if (empty($_POST['address'])) {
            $error['address'] = 'Địa chỉ không được để trống';
        } else {
            $address_new = escape_string($_POST['address']);
        }

        if (empty($_POST['tel'])) {
            $error['tel'] = 'Số điện thoại không được để trống';
        } else {
            if (!is_tel($_POST['tel'])) {
                $error['tel'] = 'Số điện thoại sai định dạng';
            }
            $tel_new = escape_string($_POST['tel']);
        }
        if (empty($error)) {
            $data = array(
                'fullname' => $fullname_new,
                'address' => $address_new,
                'tel' => $tel_new,
            );
            update_user($data, $username);
            redirect_to('?mod=users&action=update');
        } else {
            $data['error'] = $error;
            load_view('update', $data);
            exit;
        }


    }
    load_view('update', $data);
}

// function pass_ajaxAction()
// {
//     global $error, $username;
//     $username = $_SESSION['username'];
//     //===================================
//     #CHECK OLD PASS
//     //===================================
//     if (empty($_POST['pass_old'])) {
//         $error['pass_old'] = 'Mật khẩu cũ không được để trống';
//     } else {
//         if (!is_password($_POST['pass_old'])) {
//             $error['pass_old'] = 'Mật khẩu cũ sai định dạng';
//         } else {

//             if (check_pass($username, $_POST['pass_old'])) {
//                 $passOld = escape_string($_POST['pass_old']);
//             } else {
//                 $error['pass'] = 'Mật khẩu không chính xác';
//             }
//         }

//     }
//     //===================================
//     #CHECK NEW PASS
//     //===================================
//     if (empty($_POST['pass_new'])) {
//         $error['pass_new'] = 'Mật khẩu mới không được để trống';
//     } else {
//         if (!is_password($_POST['pass_new'])) {
//             $error['pass_new'] = 'Mật khẩu mới sai định dạng';
//         }
//         $passNew = escape_string($_POST['pass_new']);
//     }
//     //===================================
//     #CHECK CONFIRM PASS
//     //===================================
//     if (empty($_POST['confirm_pass'])) {
//         $error['confirm_pass'] = 'Xác nhật mật khẩu không được để trống';
//     } else {
//         if (!is_password($_POST['confirm_pass'])) {
//             $error['confirm_pass'] = 'Xác nhật mật khẩu sai định dạng';
//         }
//         $confirmPass = escape_string($_POST['confirm_pass']);
//     }


//     if (empty($error)) {
//         if (confirm_pass($passNew, $confirmPass)) {
//             $pass_md5 = escape_string(md5($confirmPass));
//             $data = array(
//                 'password' => $pass_md5,
//             );

//             // update_user($data, $username);

//             $success = "Cập nhật mật khẩu thành công";
//             $error['success'] = $success;
//             echo json_encode($error);
//             exit;
//         } else {
//             $error['confirm_pass'] = 'Xác nhật mật khẩu không trùng khớp';
//             echo json_encode($error);
//             exit;
//         }
//     } else {
//         echo json_encode($error);
//         exit;
//     }
// }

function passAction()
{
    global $error, $success;
    $error = array();
    if (isset($_POST['btn-change-pass'])) {
        $username = $_SESSION['username'];
        //===================================
        #CHECK OLD PASS
        //===================================
        if (empty($_POST['pass-old'])) {
            $error['pass-old'] = 'Mật khẩu cũ không được để trống';
        } else {
            if (!is_password($_POST['pass-old'])) {
                $error['pass-old'] = 'Mật khẩu cũ sai định dạng';
            } else {

                if (check_pass($username, $_POST['pass-old'])) {
                    $passOld = escape_string($_POST['pass-old']);
                } else {
                    $error['pass-old'] = 'Mật khẩu không chính xác';
                }
            }

        }
        //===================================
        #CHECK NEW PASS
        //===================================
        if (empty($_POST['pass-new'])) {
            $error['pass-new'] = 'Mật khẩu mới không được để trống';
        } else {
            if (!is_password($_POST['pass-new'])) {
                $error['pass-new'] = 'Mật khẩu mới sai định dạng';
            }
            $passNew = escape_string($_POST['pass-new']);
        }
        //===================================
        #CHECK CONFIRM PASS
        //===================================
        if (empty($_POST['confirm-pass'])) {
            $error['confirm-pass'] = 'Xác nhật mật khẩu không được để trống';
        } else {
            if (!is_password($_POST['confirm-pass'])) {
                $error['confirm-pass'] = 'Xác nhật mật khẩu sai định dạng';
            }
            $confirmPass = escape_string($_POST['confirm-pass']);
        }

        if (empty($error)) {
            if (confirm_pass($passNew, $confirmPass)) {
                $pass_md5 = escape_string(md5($confirmPass));
                $data = array(
                    'password' => $pass_md5,
                );

                update_user($data, $username);

                $success = "<div class='success'>
                        <p>Cập nhật mật khẩu thành công</p>
                    </div>";
                $data['success'] = $success;
                load_view('change_pass', $data);
                exit;
            } else {
                $error['confirm-pass'] = 'Xác nhật mật khẩu không trùng khớp';
            }
        } else {
            $data['error'] = $error;
            load_view('change_pass', $data);
            exit;
        }
    }
    load_view('change_pass');
}

function loginAction()
{
    if (isset($_POST['btn-login'])) {
        global $error, $warning, $username, $password;
        $error = array();
        //======USERNAME===============
        if (empty($_POST['username'])) {
            $error['username'] = 'Username không được để trống';
        } else {
            if (!is_username($_POST['username'])) {
                $error['username'] = 'Username sai định dạng';
            }
            $username = escape_string($_POST['username']);
        }

        //======PASSWORD===============
        if (empty($_POST['password'])) {
            $error['password'] = 'Password không được để trống';
        } else {
            if (!is_password($_POST['password'])) {
                $error['password'] = 'Password sai định dạng';
            }
            $password = escape_string($_POST['password']);
        }
        //======EMAIL===============//======EMAIL===============


        if (empty($error)) {
            $password_md5 = md5($password);
            $username_new = escape_string($username);
            $check = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username_new}' AND `password` = '{$password_md5}' AND `status` = 'active'");
            if ($check) {
                // show_array($check);
                // echo $check['fullname'];
                $_SESSION['is_login'] = true;
                $_SESSION['user_login'] = $check['fullname'];
                $_SESSION['username'] = $check['username'];
                $_SESSION['user_id'] = $check['user_id'];
                redirect_to('?');
            } else {
                $error['account'] = "Tài khoản không tồn tại trong hệ thống";
                $data['error'] = $error;
                load_view('login', $data);
                exit;
            }
        } else {
            $data['error'] = $error;
            $data['username'] = $_POST['username'];
            load_view('login', $data);
            exit;
        }
    }
    load_view('login');
}


function createAction()
{
    global $error, $fullname, $email, $username, $success;
    if (isset($_POST['btn-create'])) {
        $error = array();
        if (empty($_POST['fullname'])) {
            $error['fullname'] = 'Fullname không được để trống';
        } else {
            if ((strlen($_POST['fullname'])) <= 6 && strlen($_POST['fullname']) >= 50) {
                $error['fullname'] = "Fullname có độ dài từ 6 đến 50 kí tự";
            }
            $fullname = escape_string($_POST['fullname']);
        }

        if (empty($_POST['username'])) {
            $error['username'] = 'Username không được để trống';
        } else {
            if (!is_username($_POST['username'])) {
                $error['username'] = 'Username sai định dạng';
            }
            $username = escape_string($_POST['username']);
        }

        //======PASSWORD===============
        if (empty($_POST['password'])) {
            $error['password'] = 'Password không được để trống';
        } else {
            if (!is_password($_POST['password'])) {
                $error['password'] = 'Password sai định dạng';
            }
            $password = escape_string($_POST['password']);
        }

        if (empty($_POST['email'])) {
            $error['email'] = 'Email không được để trống';
        } else {
            $email_new = escape_string($_POST['email']);
            $check_email = db_fetch_row("SELECT * FROM `tbl_users` WHERE `email` = '{$email_new}'");
            if ($check_email) {
                $error['email'] = 'Email đã tồn tại';
            }
            $email = escape_string($_POST['email']);
        }

        if (empty($error)) {
            $active_token = md5($username . time());
            $password_md5 = md5($password);
            $data = array(
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password_md5,
                'active_token' => $active_token,
            );
            db_insert('tbl_users', $data);
            // global $config;
            $link_active = base_url("?mod=users&action=active&active_token={$active_token}");
            $content = "<p>Chào bạn {$fullname}</p>
            <p>Bạn vui lòng click vào đường link này để kích hoạt tài khoản: <a href='{$link_active}'>KÍCH HOẠT</a></p>
            <p>Nếu không phải bạn đăng ký tài khoản vui lòng bỏ qua email này</p>
            <p>Hệ thống sẽ tự động xóa tài khoản nếu chưa được kích hoạt sau 24H</p>
            <p>TeamSuport Unitop</p>";
            // send_mail("{$email}", "{$fullname}", "Kích hoạt tài khoản Php Master", "{$content}");
            $success = "<div class='success'>
                        <p>Tạo mới tài khoản thành công</p>
                    </div>";
            $data['success'] = $success;
            load_view('create', $data);
            exit;
        } else {
            $data['error'] = $error;
            $data['fullname'] = $_POST['fullname'];
            $data['email'] = $_POST['email'];
            $data['username'] = $_POST['username'];
            load_view('create', $data);
            exit;
        }

    }
    load_view('create');
}

function activeAction()
{
    $active_token = $_GET['active_token'];
    $link_login = base_url("?mod=users&action=login");
    if (check_active_token($active_token)) {
        active_user($active_token);
        echo "Bạn đã kích hoạt thành công vui lòng click vào link sau để đăng nhập: <a href='{$link_login}'>LINK</a>";
    } else {
        echo "Yêu cầu kích hoạt không hợp lệ hoăc tài khoản đã được kích hoạt trước đó vui lòng click vào link sau để đăng nhập: <a href='{$link_login}'>LINK</a>";
    }



}

function logoutAction()
{
    unset($_SESSION['is_login']);
    unset($_SESSION['user_login']);
    unset($_SESSION['username']);
    redirect_to("?mod=users&action=login");
    // load_view('login');
}

