<?php
function add_cart($id, $num_order = 1)
{
    $item = db_fetch_row("SELECT p.*, i.file_name, pi.pin
                                       FROM tbl_products p
                                       INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
                                       INNER JOIN tbl_images i ON pi.image_id = i.image_id
                                       INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
                                       WHERE p.product_status = 'public'
                                        AND pi.pin = 1 AND pc.category_status = 'public'
                                        AND p.product_id = {$id}
                                       GROUP BY p.product_id
                                       ORDER BY p.product_id DESC;");

    $qty = $num_order;
    $count = 0;
    if (isset($_SESSION['cart']) && array_key_exists($id, $_SESSION['cart']['buy'])) {
        $qty = $_SESSION['cart']['buy'][$id]['qty'] + $num_order;

    } else {
        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [
                'buy' => [],
                'count' => 0,
            ];
        }
    }

    $_SESSION['cart']['buy'][$id] = array(
        'product_id' => $item['product_id'],
        'product_slug' => $item['product_slug'],
        'product_name' => $item['product_name'],
        'product_price' => $item['product_price'],
        'product_price_fomart' => currency_format($item['product_price']),
        'file_name' => $item['file_name'],
        'stock_quantity' => $item['stock_quantity'],
        'qty' => $qty,
        'sub_total' => $qty * $item['product_price'],


    );

    foreach ($_SESSION['cart']['buy'] as $v) {
        $count++;
    }
    $_SESSION['cart']['count'] = $count;
    //Cập nhật hóa đơn
    update_info_cart();
}


function update_info_cart()
{
    if (isset($_SESSION['cart'])) {
        $num_order = 0;
        $total = 0;
        foreach ($_SESSION['cart']['buy'] as $item) {
            $num_order += $item['qty'];
            $total += $item['sub_total'];
        }

        $_SESSION['cart']['info'] = array(
            'num_order' => $num_order,
            'total' => $total,
        );
    }

}



function detele_cart($id = '')
{
    if (isset($_SESSION['cart'])) {
        // Xóa sản phẩm có $id trong giỏ hàng
        if (!empty($id)) {
            $_SESSION['cart']['info']['num_order'] = $_SESSION['cart']['info']['num_order'] - $_SESSION['cart']['buy'][$id]['qty'];
            $_SESSION['cart']['info']['total'] = $_SESSION['cart']['info']['total'] -  $_SESSION['cart']['buy'][$id]['sub_total'];
            $_SESSION['cart']['count'] = $_SESSION['cart']['count'] - 1;
            unset($_SESSION['cart']['buy'][$id]);
            update_info_cart();
        } else {
            unset($_SESSION['cart']);
            update_info_cart();
        }
       
    }
    // update_info_cart();
}

function update_cart($qty, $id)
{
    $_SESSION['cart']['buy'][$id]['qty'] = $qty;
    $_SESSION['cart']['buy'][$id]['sub_total'] = $qty * $_SESSION['cart']['buy'][$id]['product_price'];
    update_info_cart();
}

function createOrder($data){
    db_insert("tbl_orders", $data);
}

function createOrderItems($data){
    db_insert("tbl_order_items", $data);
}


function createCustomer($data){
    db_insert("tbl_customer", $data);
}


function get_id_order_last(){
    $id = db_fetch_row("SELECT `order_id` FROM `tbl_orders` ORDER BY `order_id` DESC LIMIT 1");
    return $id['order_id'];
}

function get_id_customer_last(){
    $id = db_fetch_row("SELECT `customer_id` FROM `tbl_customer` ORDER BY `customer_id` DESC LIMIT 1");
    return $id['customer_id'];
}