<?php 
function get_user($user_id)
{
    $result = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = '{$user_id}'");
    if ($result) {
        echo $result['fullname'];
    }
    echo "";
}

function check_query_page($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
}