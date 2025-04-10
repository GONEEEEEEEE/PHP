

<?php

function construct() {
//    echo "DÙng chung, load đầu tiên
    load_model('index');
    load('lib', 'validation');
    load('lib', 'email');
    // load('controllers','users' );
}

function regAction() {
    global $error, $username, $password, $email, $fullname;
    if (isset($_POST['btn-reg'])) {
        $error = array();
        if (empty($_POST['fullname'])) {
            $error['fullname'] = "Không được để trống họ tên";
        } else {
            $fullname = $_POST['fullname'];
        }
        #Kiểm tra username
        if (empty($_POST['username'])) {
            $error['username'] = "Không được để trống tên đăng nhập";
        } else {
            if (!is_username($_POST['username'])) {
                $error['username'] = "Tên đăng nhập không đúng định dạng";
            } else {
                $username = $_POST['username'];
            }
        }
        #Kiểm tra Password
        if (empty($_POST['password'])) {
            $error['password'] = "Không được để trống mật khẩu";
        } else {
            if (!is_password($_POST['password'])) {
                $error['password'] = "Mật khẩu không đúng định dạng";
            } else {
                $password = md5($_POST['password']);
            }
        }
        #Kiểm tra email
        if (empty($_POST['email'])) {
            $error['email'] = "Không được để trống email";
        } else {
            if (!is_email($_POST['email'])) {
                $error['email'] = "Email không đúng định dạng";
            } else {
                $email = $_POST['email'];
            }
        }

        #Kết luận
        if (empty($error)) {
            if (!user_exists($username, $email)) {
                $active_token = md5($username . time());
                $data = array(
                    'fullname' => $fullname,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'active_token' => $active_token,
                    'reg_date' => time()
                );
                add_user($data);
                
                // Hiển thị thông báo đăng ký thành công
                session_start();
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                
                // Chuyển hướng về trang đăng nhập
                header("Location: ?mod=users&action=login");
                exit();
            } else {
                $error['account'] = "Email hoặc username đã tồn tại trên hệ thống";
            }
        }
    }
    load_view('reg');
}

    


function loginAction(){
    global $error, $username, $password;
        if (isset($_POST['btn-login'])) {
        $error = array();

        // Kiểm tra username
        if (empty($_POST['username'])) {
            $error['username'] = "Không được để trống tên đăng nhập";
        } else {
            if (!is_username($_POST['username'])) {
                $error['username'] = "Tên đăng nhập không đúng định dạng";
            } else {
                $username = $_POST['username'];
            }
        }

        // Kiểm tra password
        if (empty($_POST['password'])) {
            $error['password'] = "Không được để trống mật khẩu";
        } else {
            if (!is_password($_POST['password'])) {
                $error['password'] = "Mật khẩu không đúng định dạng";
            } else {
                $password = md5($_POST['password']);
            }
        }

        // Kết luận
        if (empty($error)) {
            if(check_login($username, $password)){
                // Lưu dữ liệu phiên đăng nhập
                $_SESSION['is_login'] = true;
                $_SESSION['user_login'] = $username;
                // Chuyển hướng hệ thống
                redirect();
                exit; // Đảm bảo dừng xử lý sau chuyển hướng
            } else {
                $error['account'] = "Tên đăng nhập hoặc mật khẩu không tồn tại";
            }
        }
    } 
    load_view('login');
}

function  activeAction() {
    $link_login = base_url("?mod=users&action=login");
    $active_token = $_GET['active_token'];   
    echo $active_token;
    if (check_active_token($active_token)) {
        active_user($active_token);
        echo "Bạn đã kích hoạt thành công, vui lòng click vào link sau để đăng nhập: <a href='{$link_login}'>Đăng nhập</a>";
    } else {
        echo "Yêu cầu kích hoạt không hợp lệ! hoặc tài khoản đã được kích hoạt trước đó";
    }
}

function logoutAction(){
    unset($_SESSION['is_login']);
    unset($_SESSION['user_login']);
    redirect("?mod=users&action=login");
}

// function resetAction(){
//     global $error, $username, $password;
//     $reset_token = $_GET['reset_token'];
//     if(!empty($reset_token)){
//         if(check_reset_token($reset_token)){
//             if (isset($_POST['btn-new-pass'])) {
//                 $error = array();
//                 // Kiểm tra password
//         if (empty($_POST['password'])) {
//             $error['password'] = "Không được để trống mật khẩu";
//         } else {
//             if (!is_password($_POST['password'])) {
//                 $error['password'] = "Mật khẩu không đúng định dạng";
//             } else {
//                 $password = md5($_POST['password']);
//             }
//         }
//         if(empty($error)){
//             $data = array(
//                 'password' => $password
//             );
//             update_pass($data, $reset_token);
//             redirect("?mod=users&action=resetOk");
//         }
//         }
//         load_view('newPass');
//         }else{
//             echo "Yêu cầu lấy lại mật khẩu không hợp lệ";
//         }
//     }else{
//     if (isset($_POST['btn-reset'])) {
//         $error = array();

//         #Kiểm tra email
//         if (empty($_POST['email'])) {
//             $error['email'] = "Không được để trống email";
//         } else {
//             if (!is_email($_POST['email'])) {
//                 $error['email'] = "Email không đúng định dạng";
//             } else {
//                 $email = $_POST['email'];
//             }
//         }

//         // Kết luận
//         if (empty($error)) {
//             if(check_email($email)){
//                 $reset_token = md5($email.time());
//                 $data = array(
//                     'reset_token' => $reset_token
//                 );
//                 //Cập nhật mã reset pass cho user cần khôi phục mật khẩu
//                 update_reset_token($data, $email);
//                 //Gửi link khôi phục vào email của người dùng
//                 $link = base_url("?mod=users&action=reset&reset_token={$reset_token}");
//                 $content = "<p>Bạn vui lòng click vào link sau để khôi phục mật khẩu: {$link}</p>
//                 <p>Nếu không phải yêu cầu của bạn, bạn vui lòng bỏ qua email này</p>
//                 <p>JuunD All Team Support</p>";

//                 send_mail($email,'', 'Khôi phục mật khẩu PHP MASTER', $content);
//             } else {
//                 $error['account'] = "Email không tồn tại";
//             }
//         }
//     } 
//     load_view('reset');
//     }
// }

function resetAction() {
    global $error, $username, $password;
    $reset_token = $_GET['reset_token'] ?? null;

    if (!empty($reset_token)) {
        if (check_reset_token($reset_token)) {
            if (isset($_POST['btn-new-pass'])) {
                $error = array();

                // Kiểm tra mật khẩu
                if (empty($_POST['password'])) {
                    $error['password'] = "Không được để trống mật khẩu";
                } else {
                    if (!is_password($_POST['password'])) {
                        $error['password'] = "Mật khẩu không đúng định dạng";
                    } else {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }
                }

                if (empty($error)) {
                    $data = array(
                        'password' => $password
                    );
                    update_pass($data, $reset_token);
                    redirect("?mod=users&action=resetOk");
                }
            }
            load_view('newPass');
        } else {
            echo "Yêu cầu lấy lại mật khẩu không hợp lệ";
        }
    } else {
        if (isset($_POST['btn-reset'])) {
            $error = array();

            // Kiểm tra email
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            if (empty($email)) {
                $error['email'] = "Không được để trống email";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error['email'] = "Email không đúng định dạng";
            }

            // Kết luận
            if (empty($error)) {
                if (check_email($email)) {
                    $reset_token = md5($email . time());
                    $data = array(
                        'reset_token' => $reset_token
                    );
                    update_reset_token($data, $email);

                    // Gửi email
                    $link = base_url("?mod=users&action=reset&reset_token={$reset_token}");
                    $content = "<p>Bạn vui lòng click vào link sau để khôi phục mật khẩu: {$link}</p>
                                <p>Nếu không phải yêu cầu của bạn, bạn vui lòng bỏ qua email này</p>
                                <p>JuunD All Team Support</p>";

                    try {
                        send_mail($email, '', 'Khôi phục mật khẩu PHP MASTER', $content);
                    } catch (Exception $e) {
                        $error['email'] = "Không thể gửi email: " . $e->getMessage();
                    }
                } else {
                    $error['account'] = "Email không tồn tại";
                }
            }
        }
        load_view('reset');
    }
}






function resetOkAction(){
    load_view('resetOk');
}