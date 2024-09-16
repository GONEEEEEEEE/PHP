<?php
function create_images($data)
{
    db_insert('tbl_images', $data);
}

function get_id_last_image()
{
    $result = db_fetch_row("SELECT * FROM `tbl_images` ORDER BY `image_id` DESC LIMIT 1");
    return $result['image_id'];
}

function create_sliders($data)
{
    db_insert('tbl_sliders', $data);
}

function get_images($image_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = '{$image_id}'");
    return $result['file_name'];
}

function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        return $result['fullname'];
    }
}

function get_status_name($status)
{
    if ($status == 'public') {
        echo 'Công khai';
    } else if ($status == 'pending') {
        echo 'Chờ duyệt';
    } else if ($status == 'trash') {
        echo 'Thùng rác';
    } 
}


function get_pages($start = 1, $num_per_page = 10, $where = "")
{
    $list_sliders = db_fetch_array("SELECT * FROM `tbl_sliders` {$where} ORDER BY `slider_id` DESC LIMIT {$start}, {$num_per_page}");
    return $list_sliders;
}

function get_pages_query($start = 1, $num_per_page = 10, $sql)
{
    $list_sliders = db_fetch_array("$sql LIMIT {$start}, {$num_per_page}");
    return $list_sliders;
}

function num_status_sliders_query($status, $query)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_sliders` WHERE (`slider_id` LIKE '%{$query}%' OR 
        `slider_title` LIKE '%{$query}%' OR `slider_desc` LIKE '%{$query}%' OR `slider_url` 
        LIKE '%{$query}%' OR `created_at` LIKE '%{$query}%') AND `slider_status` = '{$status}' OR `display_order` LIKE '%{$query}%' ORDER BY `slider_id` DESC");
    return $num_row;
}

function check_query_sliders($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
}


function check_query($query)
{
    if (!empty($query)) {
        echo '&s=' . urlencode($query);
    }
}

function delete_slider($slider_id){
    $data = [
        'slider_status' => "trash",
    ];
    db_update("tbl_sliders", $data, "`slider_id` = '{$slider_id}'");
}

function update_status($data, $where){
    db_update('tbl_sliders',$data, "`slider_id` = {$where}");
}

function set_status($slider_status, $status_to_check)
{
    if ($slider_status == $status_to_check) {
        return "selected='selected'";
    }
    return "";
}

function update_images($data, $image_id)
{
    db_update('tbl_images', $data, "`image_id` = {$image_id}");
}

function update_slider($data, $slider_id)
{
    db_update('tbl_sliders', $data, "`slider_id` = {$slider_id}");
}
