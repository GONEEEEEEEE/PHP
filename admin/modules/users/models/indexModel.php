<?php

function active_user($active_token)
{
    return db_update('tbl_users', array('is_active' => 1), "active_token = '{$active_token}'");

}

function check_active_token($active_token)
{
    $check_user = db_num_rows("SELECT * FROM `tbl_users` WHERE `active_token` = '{$active_token}' AND `is_active` = '0'");
    if ($check_user > 0) {
        return true;
    }
    return false;
}

function update_user($data, $username)
{
    db_update('tbl_users', $data, "`username` = '{$username}'");
}

function check_pass($username, $password)
{
    $pass_md5 = escape_string(md5($password));
    $check_pass = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username}' AND  `password` = '{$pass_md5}'");
    if ($check_pass > 0) {
        return true;
    }
    return false;
}

function confirm_pass($pass_new, $confirm_pass)
{
    if ($pass_new == $confirm_pass) {
        return true;
    }
}

function status_users_query($query, $where)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_users` WHERE (`username` LIKE '%{$query}%' OR 
        `fullname` LIKE '%{$query}%' OR `email` LIKE '%{$query}%' OR 
        `tel` LIKE '%{$query}%' OR `address` LIKE '%{$query}%' OR `status` LIKE '%{$query}%') AND `status` = '$where'");
    return $num_row;
}


function status_users_all($query)
{
    $num_row = db_num_rows("SELECT * FROM `tbl_users` WHERE (`username` LIKE '%{$query}%' OR 
        `fullname` LIKE '%{$query}%' OR `email` LIKE '%{$query}%' OR 
        `tel` LIKE '%{$query}%' OR `address` LIKE '%{$query}%' OR `status` LIKE '%{$query}%')");
    return $num_row;
}

// function check_num($num)
// {
//     if (isset($num)) {
//         return true;
//     }
//     return false;
// }

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
