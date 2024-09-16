<?php

function construct()
{
    load_model("index");
    load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    load_view("index");
}

function checkOutAction()
{
    load_view("checkOut");
}

function orderAction()
{
    $error = array();
    if (isset($_SESSION['cart'])) {
        $product_quantity = $_SESSION['cart']['info']['num_order'];
        $total_amount = $_SESSION['cart']['info']['total'];
    }

    if (empty($_POST['fullName'])) {
        $error['fullName'] = "Họ và tên không được để trống";
    } else {
        $fullName = $_POST['fullName'];
    }
    if (empty($_POST['email'])) {
        $error['email'] = "Email không được để trống";
    } else {
        if (!is_email($_POST['email'])) {
            $error['email'] = "Email không đúng định dạng";
        }
        $email = $_POST['email'];
    }

    if (empty($_POST['phone'])) {
        $error['phone'] = "Số điện thoại không được để trống";
    } else {
        if (!is_tel($_POST['phone'])) {
            $error['phone'] = "Số điện thoại không đúng định dạng";
        }
        $phone = $_POST['phone'];
    }

    if (empty($_POST['tinh'])) {
        $error['tinh'] = "Tỉnh/Thành phố không được để trống";
    } else {
        $tinh = $_POST['tinh_name'];
    }

    if (empty($_POST['quan'])) {
        $error['quan'] = "Quận/Huyện không được để trống";
    } else {
        $quan = $_POST['quan_name'];
    }

    if (empty($_POST['phuong'])) {
        $error['phuong'] = "Phường/Xã không được để trống";
    } else {
        $phuong = $_POST['phuong_name'];
    }

    if (empty($_POST['address'])) {
        $error['address'] = "Địa chỉ không được để trống";
    } else {
        $address = $_POST['address'];
    }

    if (empty($_POST['notes'])) {
        $notes = "";
    } else {
        $notes = $_POST['notes'];
    }

    $pay = $_POST['pay'];

    //====================
    //check error
    //====================

    if (empty($error)) {
        $data['check'] = "0";

        $date = date('Y-m-d H:i:s', time());
        $address_detail = $address . ", " . $phuong . ", " . $quan . ", " . $tinh;

        $customer = [
            "fullname" => $fullName,
            "email" => $email,
            "phone_number" => $phone,
            "address" => $address_detail,
            "created_at" => $date,
        ];

        createCustomer($customer);

        $customer_id = get_id_customer_last();

        $order = [
            'product_quantity' => $product_quantity,
            'total_amount' => $total_amount,
            'order_date' => $date,
            'payment_method' => $pay,
            'shipping_address' => $address_detail,
            'status' => "pending",
            'customer_id ' => $customer_id,
            'created_at ' => $date,
        ];

        createOrder($order);

       

        $order_id = get_id_order_last();

        foreach ($_SESSION['cart']['buy'] as $v) {
            $orderItem = [
                'order_id' => $order_id,
                'product_id' => $v['product_id'],
                'quantity' => $v['qty'],
                'price' => $v['sub_total'],
            ];
            createOrderItems($orderItem);

            $qty_old = db_fetch_row("SELECT `stock_quantity` FROM `tbl_products` WHERE `product_id` = {$v['product_id']}");
            $qty_new = $qty_old['stock_quantity'] - $v['qty'];
            $product_item = [
                'stock_quantity' => $qty_new,
            ];
            db_update("tbl_products", $product_item, "`product_id` = {$v['product_id']}");
        }
        $_SESSION['customer']['order_id'] = $order_id;
        $_SESSION['customer']['fullname'] = $fullName;
        $_SESSION['customer']['phone'] = $phone;
        $_SESSION['customer']['email'] = $email;
        $_SESSION['customer']['address'] = $address_detail;
        $_SESSION['customer']['pay'] = $pay;
        $_SESSION['customer']['date'] = $date;
        include __DIR__ . '/../views/detailOrderView.php';
        $content = ob_get_clean();
        $data['check'] = "1";
        if(send_mail($email, "ISMART", "Xác Nhận Đơn Hàng", $content)){
            $data['success'] = 1;
            unset($_SESSION['cart']);
            unset($_SESSION['customer']);
        }else{
            $data['success'] = 0;
        }
    } else {
        $data['error'] = $error;
    }
    echo json_encode($data);
}

function showAction(){
    load_view("detailOrder");
}

function addCartAction()
{
    $product_id = $_GET['product_id'];
    add_cart($product_id);
    redirect_to(base_url() . "gio-hang");
}

function addCartAjaxAction()
{
    $product_id = $_POST['product_id'];
    $num_order = $_POST['num_order'];
    add_cart($product_id, $num_order);
    $data['count'] = $_SESSION['cart']['count'];
    $data['buy'] = $_SESSION['cart']['buy'];
    $data['total'] = currency_format($_SESSION['cart']['info']['total']);
    echo json_encode($data);
}

function deleteCartAction()
{
    $product_id = $_GET['product_id'];
    detele_cart($product_id);
    // unset($_SESSION['cart']);
    // show_array($_SESSION);
    redirect_to(base_url() . "gio-hang");
}

function deleteAllAction()
{
    detele_cart();
    redirect_to(base_url() . "gio-hang");
}


function updateAjaxAction()
{
    $qty = $_POST['qty'];
    $product_id = $_POST['product_id'];
    update_cart($qty, $product_id);

    $data['sub_total'] = currency_format($_SESSION['cart']['buy'][$product_id]['sub_total']);
    $data['total'] = currency_format($_SESSION['cart']['info']['total']);
    echo json_encode($data);
}