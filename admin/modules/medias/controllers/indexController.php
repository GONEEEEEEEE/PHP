<?php

function construct()
{
    load_model("index");
}

// Giao diện hiển thị
function indexAction()
{
    global $result, $num_page, $page, $num_rows, $num_per_page;
    $result = db_fetch_array("SELECT * FROM `tbl_images` ORDER BY `created_at` DESC");
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $query = isset($_GET['s']) ? $_GET['s'] : "";

    $num_rows = db_num_rows("SELECT * FROM `tbl_images`");

    $num_per_page = 10;
    $total_row = $num_rows;
    //Tổng số trang
    $num_page = ceil($total_row / $num_per_page);
    //Chỉ số bắt đầu
    $start = ($page - 1) * $num_per_page;
    $result = db_fetch_array("SELECT * FROM `tbl_images` ORDER BY `image_id` DESC LIMIT {$start}, {$num_per_page}");
    if ($query) {
        $sql = "SELECT * FROM `tbl_images` WHERE (`image_id` LIKE '%{$query}%' OR 
        `image_url` LIKE '%{$query}%' OR `file_name` LIKE '%{$query}%' OR `created_at` 
        LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' OR `file_size` LIKE '%{$query}%')";

        
        

        $num_rows = db_num_rows("SELECT * FROM `tbl_images` WHERE (`image_id` LIKE '%{$query}%' OR 
        `image_url` LIKE '%{$query}%' OR `file_name` LIKE '%{$query}%' OR `created_at` 
        LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' OR `file_size` LIKE '%{$query}%')");
        // $num_per_page = 2;
        $total_row = $num_rows;
        //Tổng số trang
        $num_page = ceil($total_row / $num_per_page);
        //Chỉ số bắt đầu
        $start = ($page - 1) * $num_per_page;

        $result = db_fetch_array("SELECT * FROM `tbl_images` WHERE (`image_id` LIKE '%{$query}%' 
        OR `image_url` LIKE '%{$query}%' OR `file_name` LIKE '%{$query}%' OR `created_at` 
        LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' OR `file_size` LIKE '%{$query}%') 
        ORDER BY `image_id` DESC LIMIT {$start}, {$num_per_page}");
    }


    $data['num_rows'] = $num_rows;
    $data['num_per_page'] = $num_per_page;
    $data['query'] = $query;
    $data['num_page'] = $num_page;
    $data['page'] = $page;
    $data['result'] = $result;
    load_view("index", $data);
}

function detailAction()
{
    $image_id = (int) $_GET['image_id'];
    $numPost = db_num_rows("SELECT * FROM `tbl_posts` WHERE `image_id` = {$image_id}");
    $numProduct = db_num_rows("SELECT * FROM `tbl_product_images` WHERE `image_id` = {$image_id}");
    $numSlider = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `image_id` = {$image_id}");

    if ($numPost > 0) {
        $result = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `image_id` = {$image_id}");
        redirect_to("?mod=posts&action=update&post_id={$result['post_id']}");
    }

    if ($numProduct > 0) {
        $result = db_fetch_row("SELECT * FROM `tbl_product_images` WHERE `image_id` = {$image_id}");
        redirect_to("?mod=products&action=update&product_id={$result['product_id']}");
    }

    if ($numSlider > 0) {
        $result = db_fetch_row("SELECT * FROM `tbl_sliders` WHERE `image_id` = {$image_id}");
        redirect_to("?mod=sliders&action=update&slider_id={$result['slider_id']}");
    }

    if ($numPost == 0 && $numProduct == 0 && $numSlider == 0) {
        redirect_to("?mod=medias&action=index");
    }
}