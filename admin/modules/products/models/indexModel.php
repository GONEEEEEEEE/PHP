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

function check_category($product_id, $category_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_products` WHERE `product_id` = '{$product_id}'");
    if ($result['category_id'] == $category_id) {
        return "selected='selected'";
    } else {
        return "";
    }
}

function show_menu_categories($data, $parent_id = 0, $level = 0)
{
    $result = "";
    $product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $categories_name = str_repeat('|--- ', $level) . $v['category_name'];
            $check = check_category($product_id, $v['category_id']);
            $result .= "<option value='{$v['category_id']}' {$check}>{$categories_name}</option>";

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

function create_product($data)
{
    db_insert('tbl_products', $data);
}

function get_id_last_image()
{
    $result = db_fetch_row("SELECT * FROM `tbl_images` ORDER BY `image_id` DESC LIMIT 1");
    return $result['image_id'];
}


function get_id_last_product()
{
    $result = db_fetch_row("SELECT * FROM `tbl_products` ORDER BY `product_id` DESC LIMIT 1");
    return $result['product_id'];
}

function create_product_image($data)
{
    db_insert('tbl_product_images', $data);
}

function check_query($query)
{
    if (!empty($query)) {
        echo '&s=' . urlencode($query);
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
    $list_products = db_fetch_array("SELECT * FROM `tbl_products` {$where} ORDER BY `product_id` DESC LIMIT {$start}, {$num_per_page}");
    return $list_products;
}

function get_pages_query($start = 1, $num_per_page = 10, $sql)
{
    $list_products = db_fetch_array("$sql LIMIT {$start}, {$num_per_page}");
    return $list_products;
}

function num_status_products_query($status, $query)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_products` WHERE (`product_id` LIKE '%{$query}%' OR 
    `product_name` LIKE '%{$query}%' OR `product_price` LIKE '%{$query}%' OR `created_at` 
    LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%') AND `product_status` = '{$status}' ORDER BY `product_id` DESC");
    return $num_row;
}

function get_products_query($start, $num_per_page, $sql)
{
    $list_products = db_fetch_array("{$sql} LIMIT {$start}, {$num_per_page}");
    return $list_products;
}



function check_query_products($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
}

function delete_product($prodcut_id)
{
    $date = date('Y-m-d H:i:s', time());
    $data = [
        'product_status' => 'trash',
        'updated_at' => $date,
    ];
    db_update("tbl_products", $data, "`product_id` = '{$prodcut_id}'");
}

function get_status($status)
{
    if ($status == "public") {
        return "Hoạt động";
    } elseif ($status == "inactive") {
        return "Chờ duyệt";
    } elseif ($status == "out_of_stock") {
        return "Hết hàng";
    } elseif ($status == "trash") {
        return "Thùng rác";
    }
}

function get_featured($featured)
{
    if ($featured == "yes") {
        return "Nổi bật";
    } elseif ($featured == "no") {
        return "Không";
    }
}

function update_status($data, $where)
{
    db_update('tbl_products', $data, "`product_id` = {$where}");
}

function get_product_category($category_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_product_categories` WHERE `category_id` = '{$category_id}'");
    return $result['category_name'];
}

function get_category_status($label_field, $string)
{
    global $data;
    $category_status = isset($data['category_status']) ? $data['category_status'] : "";
    if (isset($data['result'][$label_field]) && $data['result'][$label_field] == $string || $category_status == $string) {
        echo "selected='selected'";
    }
}

function get_images($product_id)
{
    $images = [];
    $result = db_fetch_array("SELECT * FROM `tbl_product_images` WHERE `product_id` = {$product_id}");
    foreach ($result as $v) {
        $get_info_images = db_fetch_row("SELECT * FROM `tbl_images` WHERE `image_id` = {$v['image_id']}");
        $images[$v['image_id']]['image_id'] = $v['image_id'];
        $images[$v['image_id']]['product_image_id'] = $v['product_image_id'];
        $images[$v['image_id']]['pin'] = $v['pin'];
        $images[$v['image_id']]['file_name'] = $get_info_images['file_name'];
    }

    return $images;
}

function update_products($data, $prodcut_id)
{
    db_update("tbl_products", $data, "`product_id` = {$prodcut_id}");
}

function is_featured($featured, $string)
{
    if ($featured == $string) {
        echo "checked";
    } else {
        echo "";
    }
}
