<?php
function create_page($data)
{
    db_insert('tbl_pages', $data);
}

function update_page($data, $where)
{
    db_update('tbl_pages', $data, $where);
}

function get_pages($start = 1, $num_per_page = 10, $where = "")
{
    $list_user = db_fetch_array("SELECT * FROM `tbl_pages` {$where} LIMIT {$start}, {$num_per_page}");
    return $list_user;
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



function update_status($data, $where)
{
    db_update('tbl_pages', $data, "`page_id` = '{$where}'");
}


function num_status_pages($where)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_pages` WHERE `page_status` = '$where'");
    return $num_row;
}

function num_status_pages_query($status, $query)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_pages` WHERE (`page_id` LIKE '%{$query}%' OR 
     `page_title` LIKE '%{$query}%' OR `page_content` LIKE '%{$query}%' OR 
     `page_status` LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' 
     OR `created_at` LIKE '%{$query}%') AND `page_status` = '{$status}'");
    return $num_row;
}

function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        echo $result['fullname'];
    }
    echo "";
}


function get_pages_query($start, $num_per_page, $sql)
{
    $list_pages = db_fetch_array("{$sql} LIMIT {$start}, {$num_per_page}");
    return $list_pages;
}

function check_query($query)
{
    if (!empty($query)) {
        echo '&s=' . urlencode($query);
    }
}

function check_query_pages($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
    return false;
}

function set_value_update($label_field)
{
    global $data;
    if (isset($data['result'][$label_field])) {
        echo $data['result'][$label_field];
    }
}

function get_status_update($current_status, $option_value)
{
    if (isset($current_status)) {
        if ($current_status == $option_value) {
            echo "selected='selected'";
        }
    }

}

function set_option($page_status, $status_key = "")
{
    global $data, $status;
    if (isset($data['result'][$page_status])) {
        get_status_update($data['result']['page_status'], $status_key);
        // echo $data['result']['page_status'];
    }

    if (isset($data['status'])) {
        get_status_update($data['status'], $status_key);
    }
}


// if (isset($result['page_status'])) {

// }
// if (isset($status)) {

// }

?>