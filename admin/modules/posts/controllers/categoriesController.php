<?php

use Aws\Api\TimestampShape;

function construct()
{
    load_model('categories');
    load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    global $num_public, $num_unpublic, $status, $num_page, $page, $num_per_page, $num_pending, $num_all, $page, $num_per_page;
    // Lấy trạng thái từ query string
    $status = isset($_GET['status']) ? $_GET['status'] : 'all';

    // Đếm số lượng danh mục theo trạng thái
    $num_public = db_num_rows("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'public'");
    $num_unpublic = db_num_rows("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'unpublic'");
    $num_pending = db_num_rows("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'pending'");
    $num_all = db_num_rows("SELECT * FROM `tbl_post_categories`");
    // Lấy dữ liệu theo trạng thái
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;


    $num_per_page = 10; // Số lượng bản ghi trên trang
    $start = ($page - 1) * $num_per_page;
    // Tổng số trang


    if ($status == "all") {
        $resultt = db_fetch_array("SELECT * FROM `tbl_post_categories` ORDER BY `category_id` DESC");
        $total_row = db_num_rows("SELECT * FROM `tbl_post_categories` ORDER BY `category_id` DESC");
        $result = array_slice(array_categories($resultt), $start, $num_per_page);
    } else {
        $resultt = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = '{$status}' ORDER BY `category_id` DESC");
        $total_row = db_num_rows("SELECT * FROM `tbl_post_categories` WHERE `category_status` = '{$status}' ORDER BY `category_id` DESC");
        $result = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = '{$status}' ORDER BY `category_id` DESC LIMIT {$start}, {$num_per_page}");

    }
    $num_page = ceil($total_row / $num_per_page);
    // Chuẩn bị dữ liệu cho view
    $data['page'] = $page;
    $data['num_page'] = $num_page;
    $data['num_per_page'] = $num_per_page;
    $data["num_public"] = $num_public;
    $data["num_pending"] = $num_pending;
    $data["num_all"] = $num_all;
    $data["num_unpublic"] = $num_unpublic;
    $data["status"] = $status;
    $data["result"] = $result;
    load_view("categories", $data);
}

function createAction()
{
    global $error, $category_name, $category_slug, $category_desc, $parent_cat, $success;
    $data = array();
    if (isset($_POST['btn-create'])) {
        $error = array();

        //====================
        //check title
        //====================

        if (empty($_POST['title'])) {
            $error['title'] = "Tên danh mục không được để trống";
        } else {
            $category_name = $_POST['title'];
        }

        //====================
        //check slug
        //====================

        if (empty($_POST['slug'])) {
            $error['slug'] = "Slug không được để trống";
        } else {
            $category_slug = $_POST['slug'];
        }

        //====================
        //check content
        //====================

        if (empty($_POST['desc'])) {
            $category_desc = "";
        } else {
            $category_desc = $_POST['desc'];
        }

        //====================
        //check content
        //====================

        if (empty($_POST['parent-Cat'])) {
            $parent_cat = 0;
        } else {
            $parent_cat = $_POST['parent-Cat'];
        }

        //====================
        //check error
        //====================

        if (empty($error)) {
            $user_id = $_SESSION['user_id'];
            $data_pages = [
                'category_name' => $category_name,
                'category_slug' => $category_slug,
                'category_desc' => $category_desc,
                'parent_id' => $parent_cat,
                'user_id ' => $user_id
            ];
            create_post_categories($data_pages);
            $success = "<div class='success'>
                          <p>Tạo danh mục mới thành công</p>
                      </div>";

            $data['success'] = $success;
            $category_name = "";
            $category_slug = "";
            $category_desc = "";
            $parent_cat = "";
            redirect_to("?mod=posts&controller=categories&action=index");
        } else {
            $data['error'] = $error;
        }

    }
    load_view('createCategories', $data);
}

function updateAction()
{
    global $title, $slug, $desc, $parent, $status, $category_id, $error, $success, $category_status;

    $category_id = $_GET['id'];
    $result = db_fetch_row("SELECT * FROM `tbl_post_categories` WHERE `category_id` = '{$category_id}'");
    $title = $result["category_name"];
    $slug = $result["category_slug"];
    $desc = $result["category_desc"];
    $desc = $result["category_desc"];
    $parent = $result["parent_id"];
    $status = $result["category_status"];

    $parent = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'public'");

    $data["parent"] = $parent;
    $data["status"] = $status;
    $data["result"] = $result;
    if (isset($_POST['btn-update'])) {
        $error = array();

        //====================
        //check title
        //====================

        if (empty($_POST['title'])) {
            $error['title'] = "Tên danh mục không được để trống";
        } else {
            $category_name = $_POST['title'];
        }

        //====================
        //check slug
        //====================

        if (empty($_POST['slug'])) {
            $error['slug'] = "Slug không được để trống";
        } else {
            $category_slug = $_POST['slug'];
        }

        //====================
        //check content
        //====================

        if (empty($_POST['desc'])) {
            $category_desc = "";
        } else {
            $category_desc = $_POST['desc'];
        }

        //====================
        //check content
        //====================

        if (empty($_POST['parent-Cat'])) {
            $parent_cat = 0;
        } else {
            $parent_cat = $_POST['parent-Cat'];
        }


        if (empty($_POST['category_status'])) {
            $error['category_status'] = "Trạng thái không được để trống";
        } else {
            $category_status = $_POST['category_status'];
        }


        if (empty($error)) {
            $date = date('Y-m-d H:i:s', time());
            $data_pages = [
                'category_name' => $category_name,
                'category_slug' => $category_slug,
                'category_desc' => $category_desc,
                'category_status' => $category_status,
                'parent_id' => $parent_cat,
                'updated_at' => $date,
            ];

            update_category($data_pages, $category_id);
            $success = "<div class='success'>
            <p>Cập nhật danh mục thành công</p></div>";
            $data['success'] = $success;
            $data['category_status'] = $category_status;
            redirect_to("?mod=posts&controller=categories&action=index");
        } else {
            $data['error'] = $error;
        }
    }
    load_view('updateCategories', $data);
}

function deleteAction()
{
    $date = date('Y-m-d H:i:s', time());
    $data = [
        'category_status' => 'unpublic',
         'updated_at' => $date,
    ];

    $category_id = $_GET['id'];
    update_category($data, $category_id);
    redirect_to("?mod=posts&controller=categories&action=index");
}

function updateStatusAction()
{
    if (isset($_POST['btn_update'])) {
        if (isset($_POST['checkItem'])) {
            $checkedItems = $_POST['checkItem'];
            $action = $_POST['actions'];
            $date = date('Y-m-d H:i:s', time());
            if ($action != "") {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'category_status' => $action,
                         'updated_at' => $date,
                    );
                    update_status($data, $id);
                }
            }

        }
    }
    redirect_to("?mod=posts&controller=categories&action=index");
}