<?php 
function set_value_update($label_field)
{
    global $data;
    if (isset($data['result'][$label_field])) {
        echo $data['result'][$label_field];
    }
}

function update_customer($customer_id, $data)
{
    db_update("tbl_customer", $data, "`customer_id` = {$customer_id}");
}
?>