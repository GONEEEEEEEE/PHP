<?php
function status_users($where)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_users` WHERE `status` = '$where'");
    return $num_row;
}


function num_status_users($where)
{
    $num_row = db_fetch_array("SELECT * FROM `tbl_users` WHERE `status` = '$where'");
    return $num_row;
}

function update_status($data, $user_id)
{
    db_update('tbl_users', $data, "`user_id`= {$user_id}");
}

function get_user($start = 1, $num_per_page = 10, $where = "")
{
    $list_user = db_fetch_array("SELECT * FROM `tbl_users` {$where} LIMIT {$start}, {$num_per_page}");
    return $list_user;
}

function get_user_query($start = 1, $num_per_page = 10, $sql)
{
    $list_user = db_fetch_array("{$sql} LIMIT {$start}, {$num_per_page}");
    return $list_user;
}

function get_status_user($status)
{
    if ($status == "active") {
        echo "Hoạt đông";
    }

    if ($status == "inactive") {
        echo "Khóa tạm thời";
    }

    if ($status == "banned") {
        echo "Khóa vĩnh viễn";
    }
}
?>