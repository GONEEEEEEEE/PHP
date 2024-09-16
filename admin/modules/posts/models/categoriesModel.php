<?php
function create_post_categories($data)
{
    db_insert('tbl_post_categories', $data);
}

function has_child($data, $id)
{
    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            return true;
        }
    }

    return false;
}

function get_postt()
{
    global $status;
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    if ($status) {
        $data = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = '{$_GET['status']}'");
    } else {
        $data = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `category_status` = 'public'");
    }

    return $data;
}


function show_menu_categories($data, $parent_id = 0, $level = 0)
{
    $result = "";
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $parent = db_fetch_row("SELECT * FROM `tbl_post_categories` WHERE '{$id}' = `category_id`");
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

function show_menu_categories_update($data, $parent_id = 0, $level = 0)
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $result = "";
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $categories_name = str_repeat('|--- ', $level) . $v['category_name'];
            if ($id == $v['category_id']) {
                $result .= "<option value='{$v['category_id']}' selected='selected'>{$categories_name}</option>";
            } else {
                // echo $v['category_id'] ."--".$id;
                $result .= "<option value='{$v['category_id']}'>{$categories_name}</option>";
            }
            if (has_child($data, $v['category_id'])) {
                $result .= show_menu_categories_update($data, $v['category_id'], $level + 1);
            }
        }
    }
    return $result;
}

function get_post($status, $page, $num_per_page)
{
    $start = ($page - 1) * $num_per_page;
    if ($status) {
        $data = db_fetch_array("SELECT * FROM `tbl_post_categories`
         WHERE `category_status` = '{$status}' LIMIT {$start}, {$num_per_page}");
    }
    return $data;
}


function show_view_categories($data, $parent_id = 0, $level = 0, &$temp = 1)
{
    global $username;
    $username = $_SESSION['user_login'];
    $result = "";
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $name = category_status_name($v['category_status']);
            $categories_name = str_repeat('|--- ', $level) . $v['category_name'];
            $result .= "<tr>";
            $result .= "<td><span class='tbody-text'>{$temp}</span></td>";
            $result .= "<td class='clearfix'>";
            $result .= "<div class='tb-title fl-left'>";
            $result .= "<a href='?mod=posts&controller=categories&action=update&id={$v['category_id']}' title=''>{$categories_name}</a>";
            $result .= "</div>";
            $result .= "<ul class='list-operation fl-right'>";
            $result .= "<li><a href='?mod=posts&controller=categories&action=update&id={$v['category_id']}' title='Sửa' class='edit'><i class='fa fa-pencil' aria-hidden='true'></i></a></li>";
            $result .= "<li><a href='?mod=posts&controller=categories&action=delete&id={$v['category_id']}' title='Xóa' class='delete'><i class='fa fa-trash'aria-hidden='true'></i></a></li>";
            $result .= "</ul>";
            $result .= "</td>";
            $result .= "<td><span class='tbody-text'>{$username}</span></td>";
            $result .= "<td><span class='tbody-text'>{$name}</span></td>";
            $result .= "<td><span class='tbody-text'>{$v['created_at']}</span></td>";
            $result .= "</tr>";
            $temp++;
            if (has_child($data, $v['category_id'])) {
                $result .= show_view_categories($data, $v['category_id'], $level + 1, $temp);
            }
        }
    }
    return $result;
}

function show_view_categories_unpublic($data, $parent_id = 0, &$temp = 1)
{
    global $username;
    $username = $_SESSION['user_login'];
    $result = "";
    foreach ($data as $v) {
        $name = category_status_name($v['category_status']);
        $categories_name = str_repeat('|--- ', $v['parent_id']) . $v['category_name'];
        $result .= "<tr>";
        $result .= "<td><span class='tbody-text'>{$temp}</span></td>";
        $result .= "<td class='clearfix'>";
        $result .= "<div class='tb-title fl-left'>";
        $result .= "<a href='?mod=posts&controller=categories&action=update&id={$v['category_id']}' title=''>{$categories_name}</a>";
        $result .= "</div>";
        $result .= "<ul class='list-operation fl-right'>";
        $result .= "<li><a href='?mod=posts&controller=categories&action=update&id={$v['category_id']}' title='Sửa' class='edit'><i class='fa fa-pencil' aria-hidden='true'></i></a></li>";
        // $result .= "<li><a href='?mod=posts&controller=categories&action=delete&id={$v['parent_id']}' title='Xóa' class='delete'><i class='fa fa-trash'aria-hidden='true'></i></a></li>";
        $result .= "</ul>";
        $result .= "</td>";
        $result .= "<td><span class='tbody-text'>{$username}</span></td>";
        $result .= "<td><span class='tbody-text'>{$name}</span></td>";
        $result .= "<td><span class='tbody-text'>{$v['created_at']}</span></td>";
        $result .= "</tr>";
        $temp++;
    }
    return $result;
}
function category_status_name($status)
{
    if ($status == 'public') {
        return "Công khai";
    } elseif ($status == 'pending') {
        return "Chờ duyệt";
    } else {
        return "Thùng rác";
    }
}

function set_value_update($label_field)
{
    global $data;
    if (isset($data['result'][$label_field])) {
        echo $data['result'][$label_field];
    }
}

function get_category_status($label_field, $string)
{
    global $data, $status;
    $category_status = isset($data['category_status']) ? $data['category_status'] : "";
    if (isset($data['result'][$label_field]) && $data['result'][$label_field] == $string || $category_status == $string) {
        echo "selected='selected'";
    }
}


function update_category($data, $category_id)
{
    db_update("tbl_post_categories", $data, "`category_id` = {$category_id}");
}

// function update_category_status_by_category_id($category_id)
// {
//     $result = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `parent_id` = {$category_id}");
//     $data = [
//         'category_status' => "pending",
//         'parent_id' => 0,
//     ];
//     foreach ($result as $v) {
//         $category_id = $v["category_id"];
//         db_update('tbl_post_categories', $data, "`category_id` = {$category_id}");
//         $result2 = db_fetch_array("SELECT * FROM `tbl_post_categories` WHERE `parent_id` = {$category_id}");
//         foreach ($result2 as $v) {
//             $category_id = $v["category_id"];
//             db_update('tbl_post_categories', $data, "`category_id` = {$category_id}");
//         }
//     }
// }


function check_query_posts($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
    return false;
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
                'user_id' => $v['user_id'],
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

function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        return $result['fullname'];
    }
    return "";
}

function update_status($data, $where){
    db_update('tbl_post_categories',$data, "`category_id` = {$where}");
}
?>