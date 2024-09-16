<?php
function payment_method($payment_method)
{
    if ($payment_method == "COD") {
        return "Thanh Toán Tại Nhà";
    } else {
        return "Thanh Toán Online";
    }
}

function status($status)
{

    if ($status == "pending") {
        return "Chờ xử lý";
    } elseif ($status == "processing") {
        return "Đã xác nhận";
    } elseif ($status == "shipped") {
        return "Đang giao hàng";
    } elseif ($status == "delivered") {
        return "Đã Giao Hàng";
    } elseif ($status == "canceled") {
        return "Đã Hủy Đơn Hàng";
    }
}

function check_query($query)
{
    if (!empty($query)) {
        return '&s=' . urlencode($query);
    }
}

function get_num_rows_status($status, $query = "")
{
    $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id` WHERE `status` = '{$status}' $query");
    return $num_rows;
}

function update_order_status($data, $where)
{
    db_update("tbl_orders", $data, "`order_id` = '{$where}'");
}

function check_status($status, $result_status)
{
    if ($status == $result_status) {
        echo "selected='selected'";
    }
}
?>