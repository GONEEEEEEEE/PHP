<?php

function construct()
{
    load_model("index");
    load('lib', 'validation');
}


// Giao diện hiển thị
function indexAction()
{
    global $num_products, $num_products_active, $num_products_inactive, $num_out_of_stock, $num_trash, $query, $num_page, $page, $num_per_page;

    $num_products = db_num_rows("SELECT * FROM `tbl_products`");
    $num_products_active = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = 'public'");
    $num_products_inactive = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = 'inactive'");
    $num_out_of_stock = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = 'out_of_stock'");
    $num_trash = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = 'trash'");
    $num_featured = db_num_rows("SELECT * FROM `tbl_products` WHERE `is_featured` = 'yes'");

    $query = isset($_GET['s']) ? $_GET['s'] : "";
    $status = isset($_GET['status']) ? $_GET['status'] : "all";
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;


    if ($status == "all") {
        $result = db_fetch_array("SELECT * FROM `tbl_products` ORDER BY `product_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_products`");
    } elseif ($status == 'featured') {
        $result = db_fetch_array("SELECT * FROM `tbl_products` WHERE `is_featured` = 'yes' ORDER BY `product_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_products` WHERE `is_featured` = 'yes' ORDER BY `product_id` DESC");
    } else {
        $result = db_fetch_array("SELECT * FROM `tbl_products` WHERE `product_status` = '{$status}' ORDER BY `product_id` DESC");
        $num_rows = db_num_rows("SELECT * FROM `tbl_products` WHERE `product_status` = '{$status}' ORDER BY `product_id` DESC");
    }

    $num_per_page = 10;
    $total_row = $num_rows;
    //Tổng số trang
    $num_page = ceil($total_row / $num_per_page);
    //Chỉ số bắt đầu
    $start = ($page - 1) * $num_per_page;

    if ($status == 'all') {
        $result = get_pages($start, $num_per_page);
    } elseif ($status == 'featured') {
        $result = get_pages($start, $num_per_page, " WHERE `is_featured` = 'yes'");
    } else {
        $result = get_pages($start, $num_per_page, " WHERE `product_status` = '{$status}'");
    }

    //==============SEARCH=============
    if ($query) {
        $sql = "SELECT * FROM `tbl_products` WHERE (`product_id` LIKE '%{$query}%' OR 
        `product_name` LIKE '%{$query}%' OR `product_price` LIKE '%{$query}%' OR `created_at` 
        LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%')";

        $num_products = db_num_rows($sql);
        $num_products_active = num_status_products_query("public", $query);
        $num_products_inactive = num_status_products_query("inactive", $query);
        $num_out_of_stock = num_status_products_query("out_of_stock", $query);
        $num_trash = num_status_products_query("trash", $query);
        $num_featured = db_num_rows($sql . "AND `is_featured` = 'yes'");

        if ($status == "all") {
            $result = db_fetch_array($sql);
            $num_rows = db_num_rows($sql);
        } else if ($status == "featured") {
            $result = db_fetch_array($sql . "AND `is_featured` = 'yes'");
            $num_rows = db_num_rows($sql . "AND `is_featured` = 'yes'");
        } else {
            $result = db_fetch_array($sql . " AND `product_status` = '{$status}'");
            $num_rows = db_num_rows($sql . " AND `product_status` = '{$status}'");
        }

        // $num_per_page = 2;
        $total_row = $num_rows;
        //Tổng số trang
        $num_page = ceil($total_row / $num_per_page);
        //Chỉ số bắt đầu
        $start = ($page - 1) * $num_per_page;

        if ($status == 'all') {
            $result = get_pages_query($start, $num_per_page, $sql);
        } else if ($status == "featured") {
            $sql .= "AND `is_featured` = 'yes'";
            $result = get_pages_query($start, $num_per_page, $sql);
        } else {
            $sql .= "AND `product_status` = '{$status}'";
            $result = get_pages_query($start, $num_per_page, $sql);
        }


    }


    $data['num_featured'] = $num_featured;
    $data['num_products'] = $num_products;
    $data['num_products_active'] = $num_products_active;
    $data['num_products_inactive'] = $num_products_inactive;
    $data['num_out_of_stock'] = $num_out_of_stock;
    $data['num_trash'] = $num_trash;
    $data['result'] = $result;
    $data['query'] = $query;
    $data['num_per_page'] = $num_per_page;
    $data['num_page'] = $num_page;
    $data['page'] = $page;
    $data['status'] = $status;


    load_view('index', $data);
}

function createAction()
{
    global $parent, $images, $error, $title, $price, $stock, $slug, $desc, $content, $status, $category_id, $success, $img, $category, $file_name_active;
    $parent = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");
    $pin = 0;

    if (isset($_POST['btn-create'])) {
        $error = array();
        $images = array();
        if (empty($_POST['title'])) {
            $error['title'] = 'Tiêu đề không được để trống';
        } else {
            if (strlen($_POST['title']) > 120) {
                $error['title'] = 'Tiêu đề không được dài hơn 120 ký tự';
            } else {
                $title = escape_string($_POST['title']);
            }
        }

        if (empty($_POST['slug'])) {
            $error['slug'] = 'Slug không được để trống';
        } else {
            $slug = escape_string($_POST['slug']);
        }

        if (empty($_POST['desc'])) {
            $error['desc'] = 'Mô tả ngắn không được để trống';
        } else {
            $desc = $_POST['desc'];
        }

        if (empty($_POST['content'])) {
            $error['content'] = 'Nội dung không được để trống';
        } else {
            $content = $_POST['content'];
        }

        if (empty($_POST['status'])) {
            $error['status'] = 'Trạng thái không được để trống';
        } else {
            $status = escape_string($_POST['status']);
        }

        if (empty($_POST['price'])) {
            $error['price'] = 'Giá không được để trống';
        } else {
            $price = escape_string($_POST['price']);
        }

        if (empty($_POST['stock'])) {
            $error['stock'] = 'Số lượng trong kho không được để trống';
        } else {
            $stock = $_POST['stock'];
        }

        if (!empty($_POST['featured'])) {
            $featured = $_POST['featured'];
        }

        if (empty($_POST['category_id'])) {
            $error['category_id'] = 'Danh mục không được để trống';
        } else {
            $category_id = escape_string($_POST['category_id']);
        }

        if (isset($_FILES['file'])) {
            // Kiểm tra xem có nhiều file được tải lên hay không
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            if (is_array($_FILES['file']['name'])) {
                $totalFiles = count($_FILES['file']['name']);

                for ($i = 0; $i < $totalFiles; $i++) {
                    // Lưu thông tin của từng file vào mảng $images
                    $images[] = array(
                        'name' => $_FILES['file']['name'][$i],
                        'type' => $_FILES['file']['type'][$i],
                        'tmp_name' => $_FILES['file']['tmp_name'][$i],
                        'error' => $_FILES['file']['error'][$i],
                        'size' => $_FILES['file']['size'][$i],
                    );

                    $type = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
                    if ($_FILES['file']['error'][$i] == 4) {
                        $error['img'] = "Hình ảnh không được để trống";
                    } else if (!in_array(strtolower($type), $type_allow)) {
                        $error['img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
                    } else {
                        //Kiểm tra kích thước file upload
                        $file_size = $_FILES['file']['size'][$i];
                        if ($file_size > 22000000) {
                            $error['img'] = "Chỉ được upload file bé hơn 20 MB";
                        }
                    }
                }
            }
            $pin = $_POST['pin'];
        }

        if (empty($error)) {
            $user_id = $_SESSION['user_id'];
            $data_product = [
                'product_name' => $title,
                'product_slug' => $slug,
                'product_desc' => $desc,
                'product_details' => $content,
                'product_price' => $price,
                'stock_quantity' => $stock,
                'is_featured' => $featured,
                'product_status' => $status,
                'user_id' => $user_id,
                'category_id' => $category_id,
            ];
            create_product($data_product);
            $product_id = get_id_last_product();
            $count = 0;
            foreach ($images as $v) {
                $file_tmp = $v['tmp_name'];
                $target_dir = "../public/images/";
                $target_file = $target_dir . $v['name'];
                $size_file = $v['size'];
                $file_name = pathinfo($v['name'], PATHINFO_FILENAME);
                $type = pathinfo($v['name'], PATHINFO_EXTENSION);
                $file_name_new = $file_name . '.';
                // Kiểm tra trùng file trên hệ thống
                if (file_exists($target_file)) {
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
                }

                $image_id = get_id_last_image();
                if ($pin == $count) {
                    $data_product_image = [
                        'product_id' => $product_id,
                        'image_id' => $image_id,
                        'pin' => 1,
                    ];
                } else {
                    $data_product_image = [
                        'product_id' => $product_id,
                        'image_id' => $image_id,
                    ];
                }
                $count++;
                create_product_image($data_product_image);
            }
            // exit;
            $success = "<div class='success'>
            <p>Thêm sản phẩm thành công</p> </div>";
            $data["success"] = $success;
            redirect_to("?mod=products&action=index");
        }
    }

    $data["parent"] = $parent;
    load_view('createProduct', $data);
}

function deleteAction()
{
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        delete_product($product_id);
    }
    redirect_to("?mod=products&action=index");
}

function updateStatusAction()
{
    if (isset($_POST['btn_action'])) {
        if (isset($_POST['checkItem'])) {
            $checkedItems = $_POST['checkItem'];
            $action = $_POST['actions'];
            $date = date('Y-m-d H:i:s', time());
            if ($action == 'yes_featured') {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'is_featured' => "yes",
                        'updated_at' => $date,
                    );
                    update_status($data, $id);
                }
            } else if ($action == 'no_featured') {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'is_featured' => "no",
                        'updated_at' => $date,
                    );
                    update_status($data, $id);
                }
            } else {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'product_status' => $action,
                        'updated_at' => $date,
                    );
                    update_status($data, $id);
                }
            }
        }
    }
    redirect_to("?mod=products&action=index");
}

function updateAction()
{
    global $title, $slug, $desc, $content, $price, $stock, $status, $category_id, $featured, $product_id, $error, $success;
    $product_id = (int) $_GET['product_id'];

    $result = db_fetch_row("SELECT * FROM `tbl_products` WHERE `product_id` = {$product_id}");
    $parent = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");
    $title = $result['product_name'];
    $slug = $result['product_slug'];
    $desc = $result['product_desc'];
    $content = $result['product_details'];
    $price = $result['product_price'];
    $stock = $result['stock_quantity'];
    $featured = $result['is_featured'];
    $status = $result['product_status'];
    $category_id = $result['category_id'];

    $pin = 0;


    if (isset($_POST['btn-update'])) {
        $error = array();
        $images = array();
        if (empty($_POST['title'])) {
            $error['title'] = 'Tiêu đề không được để trống';
        } else {
            if (strlen($_POST['title']) >= 120) {
                $error['title'] = 'Tiêu đề không được dài hơn 120 ký tự';
            }
            $title = escape_string($_POST['title']);
        }

        if (empty($_POST['slug'])) {
            $error['slug'] = 'Slug không được để trống';
        } else {
            $slug = escape_string($_POST['slug']);
        }

        if (empty($_POST['desc'])) {
            $error['desc'] = 'Mô tả ngắn không được để trống';
        } else {
            $desc = $_POST['desc'];
        }

        if (empty($_POST['content'])) {
            $error['content'] = 'Nội dung không được để trống';
        } else {
            $content = $_POST['content'];
        }

        if (empty($_POST['status'])) {
            $error['status'] = 'Trạng thái không được để trống';
        } else {
            $status = escape_string($_POST['status']);
        }

        if (empty($_POST['price'])) {
            $error['price'] = 'Giá không được để trống';
        } else {
            $price = escape_string($_POST['price']);
        }

        if (empty($_POST['stock'])) {
            $error['stock'] = 'Số lượng trong kho không được để trống';
        } else {
            $stock = $_POST['stock'];
        }

        if (!empty($_POST['featured'])) {
            $featured = $_POST['featured'];
        }

        if (empty($_POST['category_id'])) {
            $error['category_id'] = 'Danh mục không được để trống';
        } else {
            $category_id = escape_string($_POST['category_id']);
        }
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'][0])) {
            // Kiểm tra xem có nhiều file được tải lên hay không
            $type_allow = array('png', 'jpg', 'gif', 'jpeg');
            if (is_array($_FILES['file']['name'])) {
                $totalFiles = count($_FILES['file']['name']);

                for ($i = 0; $i < $totalFiles; $i++) {
                    // Lưu thông tin của từng file vào mảng $images
                    $images[] = array(
                        'name' => $_FILES['file']['name'][$i],
                        'type' => $_FILES['file']['type'][$i],
                        'tmp_name' => $_FILES['file']['tmp_name'][$i],
                        'error' => $_FILES['file']['error'][$i],
                        'size' => $_FILES['file']['size'][$i],
                    );

                    $type = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
                    if ($_FILES['file']['error'][$i] == 4) {
                        $error['img'] = "Hình ảnh không được để trống";
                    } else if (!in_array(strtolower($type), $type_allow)) {
                        $error['img'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
                    } else {
                        //Kiểm tra kích thước file upload
                        $file_size = $_FILES['file']['size'][$i];
                        if ($file_size > 22000000) {
                            $error['img'] = "Chỉ được upload file bé hơn 20 MB";
                        }
                    }
                }
            }
            
        }

        if (empty($error)) {
            $pin = $_POST['pin'];
            $user_id = $_SESSION['user_id'];
            $date = date('Y-m-d H:i:s', time());
            $data_product = [
                'product_name' => $title,
                'product_slug' => $slug,
                'product_desc' => $desc,
                'product_details' => $content,
                'product_price' => $price,
                'stock_quantity' => $stock,
                'is_featured' => $featured,
                'product_status' => $status,
                'user_id' => $user_id,
                'category_id' => $category_id,
                'updated_at' => $date,
            ];
            update_products($data_product, $product_id);
            // $product_id_l = get_id_last_product();
            //$count kiểm tra xem id
            $count = 0;
            if (!empty($images)) {
                // Xóa ở bảng tnl_images trước xong xóa product_images
                $get_image_id = db_fetch_array("SELECT * FROM `tbl_product_images` WHERE `product_id` = '{$product_id}'");
                $image_id = array();
                foreach ($get_image_id as $v) {
                    $image_id[$v["image_id"]] = $v["image_id"];
                }
                db_delete("tbl_product_images", "`product_id` = '{$product_id}'");
                foreach ($image_id as $v => $key) {
                    //xóa hình ảnh
                    $images_id = $v;
                    $result_file_name = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = '{$images_id}'");

                    $tager_file_current = "../public/images/{$result_file_name['file_name']}";

                    // echo $tager_file_current;
                    unlink($tager_file_current);
                    // xóa hình ảnh ở db 
                    db_delete("tbl_images", "`image_id` = '{$images_id}'");
                }
                // show_array($image_id);
                // show_array($get_image_id);
                // show_array($result_file_name);
                // exit;

                foreach ($images as $v) {
                    $count++;
                    $file_tmp = $v['tmp_name'];
                    $target_dir = "../public/images/";
                    $target_file = $target_dir . $v['name'];
                    $size_file = $v['size'];
                    $file_name = pathinfo($v['name'], PATHINFO_FILENAME);
                    $type = pathinfo($v['name'], PATHINFO_EXTENSION);
                    $file_name_new = $file_name . '.';
                    // Kiểm tra trùng file trên hệ thống
                    if (file_exists($target_file)) {
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
                    }

                    $image_id = get_id_last_image();
                    if ($pin == $count) {
                        $data_product_image = [
                            'product_id' => $product_id,
                            'image_id' => $image_id,
                            'pin' => '1',
                        ];
                    } else {
                        $data_product_image = [
                            'product_id' => $product_id,
                            'image_id' => $image_id,
                        ];
                    }
                    create_product_image($data_product_image);
                }

            }else{
                $list_images = db_fetch_array("SELECT * FROM `tbl_product_images` WHERE `product_id` = '{$product_id}' ORDER BY `product_image_id` ASC");
                $data_product_image = [
                    'pin' => '0',
                ];
                db_update('tbl_product_images', $data_product_image, "`product_id` = '{$product_id}'");
                foreach($list_images as $i){
                    $count ++;
                    if($count == $pin){
                        $data_product_image = [
                            'pin' => '1',
                        ];
                        // echo "---------------";
                        // echo $count;
                        // echo $pin;
                        // echo $i['image_id'];
                        // echo "<br>";
                        db_update('tbl_product_images', $data_product_image,"`image_id` = '{$i['image_id']}'");
                    }
                    // echo $count;
                    // echo $pin;
                    // echo "<br>";
                }
                // exit;


            }

            $success = "<div class='success'>
            <p>Cập nhật sản phẩm thành công</p> </div>";
            $data["success"] = $success;
            redirect_to("?mod=products&action=index");
        } else {

            $data['error'] = $error;
        }
    }
    $data["product_id"] = $product_id;
    $data["result"] = $result;
    $data["error"] = $error;
    $data["parent"] = $parent;
    load_view("updateProduct", $data);
}

function getImagesUpdateAjaxAction()
{
    $product_id = $_POST['product_id'];
    $result = get_images($product_id);
    echo json_encode($result);
}