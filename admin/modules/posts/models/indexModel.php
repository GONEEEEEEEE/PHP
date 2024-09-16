<?php
function has_child($data, $id)
{
    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            return true;
        }
    }

    return false;
}

function set_category($category_id_current = "", $category_id_check)
{
    if ($category_id_current == $category_id_check) {
        return true;
    }
}

function set_status($post_status, $status_to_check)
{
    if ($post_status == $status_to_check) {
        return "selected='selected'";
    }
}
function show_menu_categories($data, $parent_id = 0, $level = 0)
{
    global $category_id;
    $result = "";
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $categories_name = str_repeat('|--- ', $level) . $v['category_name'];
            if (set_category($category_id, $v['category_id'])) {
                $result .= "<option value='{$v['category_id']}' selected='selected'>{$categories_name}</option>";
            } else {
                $result .= "<option value='{$v['category_id']}'>{$categories_name}</option>";
            }

            // echo "1";
            if (has_child($data, $v['category_id'])) {
                $result .= show_menu_categories($data, $v['category_id'], $level + 1);
            }
        }
    }
    return $result;
}

function create_images($data)
{
    db_insert('tbl_images', $data);
}

function get_id_last_image()
{
    $result = db_fetch_row("SELECT * FROM `tbl_images` ORDER BY `image_id` DESC LIMIT 1");
    return $result['image_id'];
}

function create_post($data)
{
    db_insert('tbl_posts', $data);
}



function get_num_post($where = '')
{
    $where = escape_string($where);
    $num = db_num_rows("SELECT * FROM `tbl_posts` WHERE `post_status` = '{$where}'  ORDER BY `post_id` DESC");
    return $num;
}

function check_query($query)
{
    if (!empty($query)) {
        echo '&s=' . urlencode($query);
    }
}

function check_query_post($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
}

function get_status_name($status)
{
    if ($status == 'draft') {
        echo 'Bản nháp';
    } else if ($status == 'published') {
        echo 'Công khai';
    } else if ($status == 'pending') {
        echo 'Chờ duyệt';
    } else if ($status == 'archived') {
        echo 'Thùng rác';
    } else {
        echo "";
    }
}

function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        echo $result['fullname'];
    }
    echo "";
}

function get_category($category_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_post_categories` WHERE `category_id` = '{$category_id}'");
    if ($result) {
        echo $result['category_name'];
    }
    echo "";
}

function get_name_title($name_title)
{
    $max_length = 30; // Số ký tự tối đa

    if (mb_strlen($name_title, 'UTF-8') > $max_length) {
        $name_new = mb_substr($name_title, 0, $max_length, 'UTF-8');
        return $name_new . "...";
    } else {
        return $name_title;
    }
}

function get_pages($start = 1, $num_per_page = 10, $where = "")
{
    $list_user = db_fetch_array("SELECT * FROM `tbl_posts` {$where}  ORDER BY `post_id` DESC LIMIT {$start}, {$num_per_page}");
    return $list_user;
}

function num_status_pages($where)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_posts` WHERE `post_status` = '$where' ORDER BY `post_id` DESC");
    return $num_row;
}

function num_status_post_query($status, $query)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_posts` WHERE (`post_id` LIKE '%{$query}%' OR 
     `post_title` LIKE '%{$query}%' OR `post_desc` LIKE '%{$query}%' OR `post_content` LIKE '%{$query}%' OR
     `post_status` LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' OR `category_id` LIKE '%{$query}%' OR `image_id` LIKE '%{$query}%' 
     OR `created_at` LIKE '%{$query}%') AND `post_status` = '{$status}' ORDER BY `post_id` DESC");
    return $num_row;
}

function get_post_query($start, $num_per_page, $sql)
{
    $list_posts = db_fetch_array("{$sql} LIMIT {$start}, {$num_per_page}");
    return $list_posts;
}

function update_status($data, $where){
    db_update('tbl_posts',$data, "`post_id` = {$where} ORDER BY `post_id` DESC");
}

function get_images($image_id){
    $result = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = '{$image_id}'");
    return $result['file_name'];
}

function update_post($data, $post_id)
{
    db_update('tbl_posts', $data, "`post_id` = {$post_id}");
}

function update_images($data, $image_id)
{
    db_update('tbl_images', $data, "`image_id` = {$image_id}");
}

