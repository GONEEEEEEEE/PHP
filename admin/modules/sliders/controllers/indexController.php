<?php

function construct()
{
    load_model("index");
    load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    global $num_slider, $num_active, $num_pending, $num_trash, $query, $status, $page;
    $num_slider = db_num_rows("SELECT * FROM `tbl_sliders`");
    $num_active = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `slider_status` = 'public'");
    $num_pending = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `slider_status` = 'pending'");
    $num_trash = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `slider_status` = 'trash'");

    $query = isset($_GET['s']) ? $_GET['s'] : "";
    $status = isset($_GET['status']) ? $_GET['status'] : "all";
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;


    if ($status == "all") {
        $result = db_fetch_array("SELECT * FROM `tbl_sliders` ORDER BY `slider_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_sliders`");
    } else {
        $result = db_fetch_array("SELECT * FROM `tbl_sliders` WHERE `slider_status` = '{$status}' ORDER BY `slider_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `slider_status` = '{$status}' ORDER BY `slider_id` DESC");
    }

    $num_per_page = 20;
    $total_row = $num_rows;
    //Tổng số trang
    $num_page = ceil($total_row / $num_per_page);
    //Chỉ số bắt đầu
    $start = ($page - 1) * $num_per_page;

    if ($status == 'all') {
        $result = get_pages($start, $num_per_page);
    } else {
        $result = get_pages($start, $num_per_page, " WHERE `slider_status` = '{$status}'");
    }

    //==============SEARCH=============
    if ($query) {
        $sql = "SELECT * FROM `tbl_sliders` WHERE (`slider_id` LIKE '%{$query}%' OR 
        `slider_title` LIKE '%{$query}%' OR `slider_desc` LIKE '%{$query}%' OR `slider_url` 
        LIKE '%{$query}%' OR `created_at` LIKE '%{$query}%' OR `display_order` LIKE '%{$query}%')";

        $num_slider = db_num_rows($sql);
        $num_active = num_status_sliders_query("public", $query);
        $num_pending = num_status_sliders_query("pending", $query);
        $num_trash = num_status_sliders_query("trash", $query);

        if ($status == "all") {
            $result = db_fetch_array($sql);
            $num_rows = db_num_rows($sql);
        } else {
            $result = db_fetch_array($sql . " AND `slider_status` = '{$status}'");
            $num_rows = db_num_rows($sql . " AND `slider_status` = '{$status}'");
        }

        // $num_per_page = 2;
        $total_row = $num_rows;
        //Tổng số trang
        $num_page = ceil($total_row / $num_per_page);
        //Chỉ số bắt đầu
        $start = ($page - 1) * $num_per_page;

        if ($status == 'all') {
            $result = get_pages_query($start, $num_per_page, $sql);
        } else {
            $sql .= "AND `slider_status` = '{$status}'";
            $result = get_pages_query($start, $num_per_page, $sql);
        }


    }


    $data['num_slider'] = $num_slider;
    $data['num_active'] = $num_active;
    $data['num_pending'] = $num_pending;
    $data['num_trash'] = $num_trash;
    $data['result'] = $result;
    $data['query'] = $query;
    $data['num_page'] = $num_page;
    $data['page'] = $page;
    $data['status'] = $status;
    load_view("index", $data);
}

function createAction()
{
    global $error, $title, $desc, $success, $num_order, $link, $status;

    if (isset($_POST['btn-create'])) {
        $error = array();

        if (empty($_POST['title'])) {
            $error['title'] = 'Tiêu đề không được để trống';
        } else {
            if (strlen($_POST['title']) >= 120) {
                $error['title'] = 'Tiêu đề không được dài hơn 120 ký tự';
            }
            $title = escape_string($_POST['title']);
        }

        if (empty($_POST['link'])) {
            $link = "";
        } else {
            $link = escape_string($_POST['link']);
        }

        if (empty($_POST['desc'])) {
            $error['desc'] = 'Mô tả ngắn không được để trống';
        } else {
            if (strlen($_POST['desc']) >= 120) {
                $error['desc'] = 'Mô tả ngắn không được dài hơn 120 ký tự';
            }
            $desc = $_POST['desc'];
        }

        if (!isset($_POST['num_order']) || $_POST['num_order'] == "") {
            $error['num_order'] = 'Thứ tự không được để trống';
        } else {
            $num_order = $_POST['num_order'];
        }


        if (empty($_POST['status'])) {
            $error['status'] = 'Trạng thái không được để trống';
        } else {
            $status = escape_string($_POST['status']);
        }

        if (isset($_FILES['file'])) {
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($_FILES['file']['error'] == 4) {
                $error['slider_img'] = "Hình ảnh không được để trống";
            } else if (!in_array(strtolower($type), $type_allow)) {
                $error['slider_img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                //Kiểm tra kích thước file upload
                $file_size = $_FILES['file']['size'];
                if ($file_size > 22000000) {
                    $error['slider_img'] = "Chỉ được upload file bé hơn 20 MB";
                }
            }
        }

        if (empty($error)) {
            $user_id = $_SESSION['user_id'];
            //create images
            $file_tmp = $_FILES['file']['tmp_name'];
            $target_dir = "../public/images/";
            $target_file = $target_dir . $_FILES['file']['name'];
            $size_file = $_FILES['file']['size'];
            $file_name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $file_name_new = $file_name . '.';
            // Kiểm tra trùng file trên hệ thống
            if (file_exists($target_file)) {
                // $error['file_exists'] = "File đã tồn tại trên hệ thống";
                //======================

                //Xử lý đổi tên file tự động
                //======================

                $new_file_name = $file_name . ' - Copy.';
                $new_upload_file = $target_dir . $new_file_name . $type;
                $k = 1;
                while (file_exists($new_upload_file)) {
                    $new_file_name = $file_name . " - Copy({$k}).";
                    $k++;
                    $new_upload_file = $target_dir . $new_file_name . $type;
                    $file_name_new = $new_file_name;
                }
                $file_name_new = $new_file_name;
                $target_file = $new_upload_file;
            }

            $file_name_new .= $type;
            if (move_uploaded_file($file_tmp, $target_file)) {
                $data_img = [
                    'file_name' => $file_name_new,
                    'file_size' => $file_size,
                    'user_id' => $user_id,
                ];

                create_images($data_img);
                $image_id = get_id_last_image();

                $data_sliders = [
                    'slider_title' => $title,
                    'slider_desc' => $desc,
                    'slider_url' => $link,
                    'display_order' => $num_order,
                    'slider_status' => $status,
                    'user_id' => $user_id,
                    'image_id ' => $image_id,
                ];
                create_sliders($data_sliders);
            }

            $success = "<div class='success'>
            <p>Thêm sliders thành công</p> </div>";
            $data["success"] = $success;
            redirect_to("?mod=sliders&action=index");
        } else {
            $data['error'] = $error;
        }
    }
    $data[''] = '';
    load_view("create", $data);
}

function deleteAction()
{
    $slider_id = $_GET['slider_id'];
    delete_slider($slider_id);
    redirect_to("?mod=sliders");
}

function updateStatusAction()
{
    if (isset($_POST['sm_action'])) {
        if (isset($_POST['checkItem'])) {
            $checkedItems = $_POST['checkItem'];
            $action = $_POST['actions'];
            if ($action != "") {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'slider_status' => $action,
                    );
                    update_status($data, $id);
                }
            }

        }
    }
    redirect_to("?mod=sliders");
}

function updateAction()
{
    global $error, $title, $desc, $success, $num_order, $link, $status, $result;
    $slider_id = $_GET['slider_id'];
    
    $result = db_fetch_row("SELECT * FROM `tbl_sliders` WHERE `slider_id` = '{$slider_id}'");
    $image_id = $result['image_id'];
    $image_name = get_images($image_id);

    if (isset($_POST['btn-update'])) {
        $error = array();

        if (empty($_POST['title'])) {
            $error['title'] = 'Tiêu đề không được để trống';
        } else {
            if (strlen($_POST['title']) >= 120) {
                $error['title'] = 'Tiêu đề không được dài hơn 120 ký tự';
            }
            $title = escape_string($_POST['title']);
        }

        if (empty($_POST['link'])) {
            $link = "";
        } else {
            $link = escape_string($_POST['link']);
        }

        if (empty($_POST['desc'])) {
            $error['desc'] = 'Mô tả ngắn không được để trống';
        } else {
            if (strlen($_POST['desc']) >= 120) {
                $error['desc'] = 'Mô tả ngắn không được dài hơn 120 ký tự';
            }
            $desc = $_POST['desc'];
        }

        if (!isset($_POST['num_order']) || $_POST['num_order'] == "") {
            $error['num_order'] = 'Thứ tự không được để trống';
        } else {
            $num_order = $_POST['num_order'];
        }


        if (empty($_POST['status'])) {
            $error['status'] = 'Trạng thái không được để trống';
        } else {
            $status = escape_string($_POST['status']);
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($_FILES['file']['error'] == 4) {
                $error['slider_img'] = "Hình ảnh không được để trống";
            } else if (!in_array(strtolower($type), $type_allow)) {
                $error['slider_img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                //Kiểm tra kích thước file upload
                $file_size = $_FILES['file']['size'];
                if ($file_size > 22000000) {
                    $error['slider_img'] = "Chỉ được upload file bé hơn 20 MB";
                }
            }
        }

        if (empty($error)) {
            $user_id = $_SESSION['user_id'];
            //create images
            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                //========================
                // Delete file images old
                $tager_file_current = "../public/images/{$image_name}";
                // echo $tager_file_current;
                unlink($tager_file_current);
                //========================
                $file_tmp = $_FILES['file']['tmp_name'];
                $target_dir = "../public/images/";

                $target_file = $target_dir . $_FILES['file']['name'];

                $file_name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
                $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $file_name_new = $file_name . '.';
                // Kiểm tra trùng file trên hệ thống
                if (file_exists($target_file)) {
                    // $error['file_exists'] = "File đã tồn tại trên hệ thống";
                    //======================

                    //Xử lý đổi tên file tự động
                    //======================

                    $new_file_name = $file_name . ' - Copy.';
                    $new_upload_file = $target_dir . $new_file_name . $type;
                    $k = 1;
                    while (file_exists($new_upload_file)) {
                        $new_file_name = $file_name . " - Copy({$k}).";
                        $k++;
                        $new_upload_file = $target_dir . $new_file_name . $type;
                        $file_name_new = $new_file_name;
                    }
                    $file_name_new = $new_file_name;
                    $target_file = $new_upload_file;
                }

                $file_name_new .= $type;

                if (move_uploaded_file($file_tmp, $target_file)) {
                    $file_size = $_FILES['file']['size'];
                    $data_img = [
                        'file_name' => $file_name_new,
                        'file_size' => $file_size,
                        'user_id' => $user_id,
                    ];
                    update_images($data_img, $image_id);
                }
            }

            $data_sliders = [
                'slider_title' => $title,
                'slider_desc' => $desc,
                'slider_url' => $link,
                'display_order' => $num_order,
                'slider_status' => $status,
                'user_id' => $user_id,
            ];
            
            // create_sliders($data_sliders);
            update_slider($data_sliders, $slider_id);
            $success = "<div class='success'>
            <p>Cập nhập sliders thành công</p> </div>";
            $data["success"] = $success;
            redirect_to("?mmod=sliders&action=index");
        } else {
            $data['error'] = $error;
        }
    }

    $result = db_fetch_row("SELECT * FROM `tbl_sliders` WHERE `slider_id` = '{$slider_id}'");
    $title = $result['slider_title'];
    $desc = $result['slider_desc'];
    $link = $result['slider_url'];
    $num_order = $result['display_order'];
    $status = $result['slider_status'];
    $image_id = $result['image_id'];
    $image_name = get_images($image_id);

    $data['result'] = $result;
    $data['status'] = $status;
    $data['image_name'] = $image_name;
    load_view('update', $data);
}