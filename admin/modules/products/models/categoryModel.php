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

function show_menu_categories($data, $parent_id = 0, $level = 0)
{
    $result = "";
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $parent = db_fetch_row("SELECT * FROM `tbl_product_categories` WHERE '{$id}' = `category_id`");
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $categories_name = str_repeat('|--- ', $level) . $v['category_name'];
            if (($parent['parent_id'] != 0 && $parent['parent_id'] == $v['category_id'])) {
                // get category_name UPDATE
                $result .= "<option value='{$v['category_id']}' selected='selected'>{$categories_name}</option>";
            } else {
                // echo $v['category_id'] ."--".$id;
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


function array_categories($data, $parent_id = 0, $level = 0)
{
    $result = [];

    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            // Lấy tên người dùng dựa trên user_id của danh mục
            $user_name = get_user($v['user_id']);

            // Tạo tên danh mục với cấp độ
            $categories_name = str_repeat('|---- ', $level) . $v['category_name'];

            $result[] = [
                'category_id' => $v['category_id'],
                'category_name' => $categories_name,
                'category_desc' => $v['category_desc'],
                'category_status' => $v['category_status'],
                'parent_id' => $v['parent_id'],
                'user_name' => $user_name,
                'user_id' =>$v['user_id'],
                'created_at' => $v['created_at'],
            ];

            // Nếu có phần tử con, gọi đệ quy và gộp kết quả vào mảng kết quả chính
            if (has_child($data, $v['category_id'])) {
                $result = array_merge($result, array_categories($data, $v['category_id'], $level + 1));
            }
        }
    }
    return $result;
}


function create_categoryProducts($data)
{
    db_insert('tbl_product_categories', $data);
}

function category_status_name($status)
{
    if ($status == 'public') {
        return "Công khai";
    }
    if ($status == 'pending') {
        return "Chờ duyệt";
    } else {
        return "Không công khai";
    }
}

function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        return $result['fullname'];
    }
    return "";
}

function get_parent_id($parent_id)
{
    if ($parent_id == 0) {
        return "";
    } else {
        $parent_name = db_fetch_row("SELECT * FROM `tbl_product_categories` WHERE '{$parent_id}' = `category_id`");
        return $parent_name['category_name'];
    }

}

function get_category_status($label_field, $string)
{
    global $data;
    $category_status = isset($data['category_status']) ? $data['category_status'] : "";
    if (isset($data['result'][$label_field]) && $data['result'][$label_field] == $string || $category_status == $string) {
        echo "selected='selected'";
    }
}

function update_category($data, $category_id)
{
    db_update("tbl_product_categories", $data, "`category_id` = {$category_id}");
}

function update_category_status_by_category_id($category_id)
{
    $result = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `parent_id` = {$category_id}");
    $data = [
        'category_status' => "pending",
        'parent_id' => 0,
    ];
    foreach ($result as $v) {
        $category_id = $v["category_id"];
        db_update('tbl_product_categories', $data, "`category_id` = {$category_id}");
        $result2 = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `parent_id` = {$category_id}");
        foreach ($result2 as $v) {
            $category_id = $v["category_id"];
            db_update('tbl_product_categories', $data, "`category_id` = {$category_id}");
        }
    }
}

function update_status($data, $where)
{
    db_update('tbl_product_categories', $data, "`category_id` = {$where}");
}
?>