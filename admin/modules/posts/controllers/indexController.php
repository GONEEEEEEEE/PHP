<?php

function construct()
{
    load_model('index');
    load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    global $result, $num_post, $num_post_draft, $num_post_published, $num_post_pending, $num_post_archived, $query, $num_page, $page;


    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $query = isset($_GET['s']) ? $_GET['s'] : "";
    //Số lưởng bản ghi trên trang
    $num_per_page = 20;
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    if ($query) {
        //Câu lệnh sql tìm kiếm
        $sql = "SELECT * FROM `tbl_posts` WHERE (`post_id` LIKE '%{$query}%' OR 
        `post_title` LIKE '%{$query}%' OR `post_slug` LIKE '%{$query}%' OR `post_status` LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' 
        OR `category_id` LIKE '%{$query}%' OR `created_at` LIKE '%{$query}%')";

        $num_post = db_num_rows($sql);

        //kiếm tra status có khác all và khác rỗng không
        if ($status != 'all' && $status != "") {
            $sql .= " AND `post_status` = '{$status}' ORDER BY `post_id` DESC";
        }
        //lấy kết quả có $sql
        $result = db_fetch_array($sql);

        //kiếm tra xem có phân trang không
        if ($page) {
            //hàm phân trang
            $num_rows = db_num_rows($sql);
            //Tổng số bản ghi
            $total_row = $num_rows;
            //Tổng số trang
            $num_page = ceil($total_row / $num_per_page);
            //Chỉ số bắt đầu
            $start = ($page - 1) * $num_per_page;
            //Lấy kết quả của hàm phân trang
            $result = get_post_query($start, $num_per_page, $sql);
        }
        //Đếm số pages actice

        $num_post_draft = num_status_post_query('draft', $query);
        $num_post_published = num_status_post_query('published', $query);
        $num_post_pending = num_status_post_query('pending', $query);
        $num_post_archived = num_status_post_query('archived', $query);

        $data['num_post'] = $num_post;
        $data['query'] = $query;
        $data['status'] = $status;
        $data['page'] = $page;
        $data['num_page'] = $num_page;
        $data['num_post_draft'] = $num_post_draft;
        $data['num_post_published'] = $num_post_published;
        $data['num_post_pending'] = $num_post_pending;
        $data['num_post_archived'] = $num_post_archived;
        $data['result'] = $result;

        load_view('post', $data);
        exit;
    }

    if ($status == '' || $status == 'all') {
        $result = db_fetch_array("SELECT * FROM `tbl_posts` ORDER BY `post_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_posts` ORDER BY `post_id` DESC");
    } else {
        $result = num_status_pages($status);
        $num_rows = db_num_rows("SELECT * FROM `tbl_posts` WHERE `post_status` = '$status' ");

    }

    if ($page) {
        //Tổng số bản ghi
        $total_row = $num_rows;
        //Tổng số trang
        $num_page = ceil($total_row / $num_per_page);
        //Chỉ số bắt đầu
        $start = ($page - 1) * $num_per_page;

        if ($status == '' || $status == 'all') {
            $result = get_pages($start, $num_per_page);
        } else {
            $result = get_pages($start, $num_per_page, "WHERE `post_status` = '$status'");
        }
    }


    // $result = db_fetch_array("SELECT * FROM `tbl_posts` ORDER BY `post_id` DESC");

    $num_post = db_num_rows("SELECT * FROM `tbl_posts`");

    $num_post_draft = get_num_post('draft');
    $num_post_published = get_num_post('published');
    $num_post_pending = get_num_post('pending');
    $num_post_archived = get_num_post('archived');




    $data['num_post'] = $num_post;
    $data['query'] = $query;
    $data['status'] = $status;
    $data['page'] = $page;
    $data['num_page'] = $num_page;
    $data['num_post_draft'] = $num_post_draft;
    $data['num_post_published'] = $num_post_published;
    $data['num_post_pending'] = $num_post_pending;
    $data['num_post_archived'] = $num_post_archived;
    $data['result'] = $result;
    load_view('post', $data);
}

function createAction()
{
    global $error, $post_title, $post_slug, $post_desc, $post_content, $post_status, $category_id, $success, $post_img, $category, $file_name_active;
    $category = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'public'");
    $post_status = "";
    $data['category'] = $category;
    if (isset($_POST['btn-create'])) {
        $error = array();
        if (empty($_POST['post_title'])) {
            $error['post_title'] = 'Tiêu đề không được để trống';
        } else {
            if (strlen($_POST['post_title']) >= 220) {
                $error['post_title'] = 'Tiêu đề không được dài hơn 220 ký tự';
            }
            $post_title = escape_string($_POST['post_title']);
        }

        if (empty($_POST['post_slug'])) {
            $error['post_slug'] = 'Slug không được để trống';
        } else {
            $post_slug = escape_string($_POST['post_slug']);
        }

        if (empty($_POST['post_desc'])) {
            $error['post_desc'] = 'Mô tả ngắn không được để trống';
        } else {
            if (strlen($_POST['post_desc']) >= 1000) {
                $error['post_desc'] = 'Mô tả ngắn không được dài hơn 1000 ký tự';
            }
            $post_desc = $_POST['post_desc'];
        }

        if (empty($_POST['post_content'])) {
            $error['post_content'] = 'Nội dung không được để trống';
        } else {
            $post_content = $_POST['post_content'];
        }

        if (empty($_POST['post_status'])) {
            $error['post_status'] = 'Trạng thái không được để trống';
        } else {
            $post_status = escape_string($_POST['post_status']);
        }

        if (empty($_POST['category_id'])) {
            $error['category_id'] = 'Danh mục không được để trống';
        } else {
            $category_id = escape_string($_POST['category_id']);
        }


        if (isset($_FILES['file'])) {
            // show_array($_FILES);
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($_FILES['file']['error'] == 4) {
                $error['post_img'] = "Hình ảnh không được để trống";
            } else if (!in_array(strtolower($type), $type_allow)) {
                $error['post_img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                //Kiểm tra kích thước file upload
                $file_size = $_FILES['file']['size'];
                if ($file_size > 22000000) {
                    $error['post_img'] = "Chỉ được upload file bé hơn 20 MB";
                }
            }
        } else {
            $error['post_img'] = "Hình ảnh không được để trốngg";
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
                $data_post = [
                    'post_title' => $post_title,
                    'post_slug' => $post_slug,
                    'post_desc' => $post_desc,
                    'post_content' => $post_content,
                    'post_status' => $post_status,
                    'user_id' => $user_id,
                    'category_id' => $category_id,
                    'image_id ' => $image_id,
                ];
                create_post($data_post);
                redirect_to("?mod=posts&action=index");
            }
        } else {
            $data['error'] = $error;
        }
    }
    $data['post_status'] = $post_status;
    load_view('createPost', $data);
}

function updateStatusAction()
{
    if (isset($_POST['btn_update'])) {
        if (isset($_POST['checkItem'])) {
            $checkedItems = $_POST['checkItem'];
            $action = $_POST['actions'];
            if ($action != "") {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'post_status' => $action,
                    );
                    update_status($data, $id);
                }
            }

        }
    }
    redirect_to("?mod=posts&action=index");
}

function updateAction()
{

    global $category_id, $success, $post_title, $post_desc, $post_content, $post_slug, $post_status, $category_id, $error;
    $post_id = $_GET['post_id'];
    $category = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'public'");
    $result = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `post_id` = {$post_id}");
    $category_id = $result['category_id'];
    $image_id = $result['image_id'];
    $image_name = get_images($image_id);
    if (isset($_POST['btn-update'])) {
        $error = array();
        if (empty($_POST['post_title'])) {
            $error['post_title'] = 'Tiêu đề không được để trống';
        } else {
            $post_title = escape_string($_POST['post_title']);
        }

        if (empty($_POST['post_slug'])) {
            $error['post_slug'] = 'Slug không được để trống';
        } else {
            $post_slug = escape_string($_POST['post_slug']);
        }

        if (empty($_POST['post_desc'])) {
            $error['post_desc'] = 'Mô tả ngắn không được để trống';
        } else {
            $post_desc = $_POST['post_desc'];
        }

        if (empty($_POST['post_content'])) {
            $error['post_content'] = 'Nội dung không được để trống';
        } else {
            $post_content = $_POST['post_content'];
        }

        if (empty($_POST['post_status'])) {
            $error['post_status'] = 'Trạng thái không được để trống';
        } else {
            $post_status = escape_string($_POST['post_status']);
        }

        if (empty($_POST['category_id'])) {
            $error['category_id'] = 'Danh mục không được để trống';
        } else {
            $category_id = escape_string($_POST['category_id']);
        }



        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($type), $type_allow)) {
                $error['post_img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            } else {
                $file_size = $_FILES['file']['size'];
                if ($file_size > 22000000) {
                    $error['post_img'] = "Chỉ được upload file bé hơn 20 MB";
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

            $data_post = [
                'post_title' => $post_title,
                'post_slug' => $post_slug,
                'post_desc' => $post_desc,
                'post_content' => $post_content,
                'post_status' => $post_status,
                'user_id' => $user_id,
                'category_id' => $category_id,
            ];
            // create_post($data_post);
            update_post($data_post, $post_id);
            $success = "<div class='success'>
                        <p>Cập nhật bài viết thành công</p>
                    </div>";
            $data['success'] = $success;
        } else {

            $data['error'] = $error;
            // load_view('updatePost', $data);
            // exit;
        }
    }
    $result = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `post_id` = '{$post_id}'");
    $data['category'] = $category;
    $data['images_id'] = $image_id;
    $data['post_status'] = $result['post_status'];
    $data['category_id'] = $category_id;
    $data['result'] = $result;
    load_view('updatePost', $data);
}

function deleteAction()
{
    $post_id = $_GET['post_id'];
    $result = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `post_id` = '{$post_id}'");
    $image_id = $result['image_id'];
    $image_name = get_images($image_id);
    if (unlink("../public/images/{$image_name}"))
        echo "";
    db_delete('tbl_posts', "`post_id` = '{$post_id}'");
    db_delete('tbl_images', "`image_id` = '{$image_id}'");

    redirect_to("?mod=posts&action=index");
}