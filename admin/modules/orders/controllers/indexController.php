<?php

function construct()
{
   load_model("index");
}

// Giao diện hiển thị
function indexAction()
{
   global $page, $num_page;
   $query = isset($_GET['s']) ? $_GET['s'] : "";
   $status = isset($_GET['status']) ? $_GET['status'] : "all";
   $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
   $sql = "";
   $num_per_page = 20;
   $start = ($page - 1) * $num_per_page;

   if ($query) {
      $sql = "AND (`order_id` LIKE '%{$query}%' OR c.`email` LIKE '%{$query}%' OR c.`fullname` LIKE '%{$query}%' OR c.`phone_number` LIKE '%{$query}%')";
   }

   if ($status == "all") {
      $sqll = "WHERE `order_id` LIKE '%{$query}%' OR c.`email` LIKE '%{$query}%' OR c.`fullname` LIKE '%{$query}%' OR c.`phone_number` LIKE '%{$query}%'";
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                               {$sqll}
                               ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                               {$sql}");
   } elseif ($status == "pending") {
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'pending' {$sql} ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'pending' {$sql}");
   } elseif ($status == "processing") {
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'processing' {$sql} ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'processing' {$sql}");
   } elseif ($status == "shipped") {
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'shipped' {$sql} ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'shipped' {$sql}");
   } elseif ($status == "delivered") {
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'delivered' {$sql} ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'delivered' {$sql}");
   } elseif ($status == "canceled") {
      $result = db_fetch_array("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'canceled' {$sql} ORDER BY `order_id` DESC LIMIT {$start}, {$num_per_page}");
      $num_rows = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                                WHERE `status` = 'canceled' {$sql}");
   }

   $num_all = db_num_rows("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id` {$sql}");
   $num_pending = get_num_rows_status("pending", $sql);
   $num_processing = get_num_rows_status("processing", $sql);
   $num_shipped = get_num_rows_status("shipped", $sql);
   $num_delivered = get_num_rows_status("delivered", $sql);
   $num_canceled = get_num_rows_status("canceled", $sql);

   $total = db_fetch_row("SELECT SUM(`total_amount`) as total FROM `tbl_orders` ");

   $total_row = $num_rows;
   //Chỉ số bắt đầu

   $num_page = ceil($total_row / $num_per_page);
   // $result = db_fetch_array("SELECT * FROM `tbl_orders` o 
   //                         INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id` ORDER BY `order_id` DESC");
   $data['result'] = $result;
   $data['num_page'] = $num_page;
   $data['page'] = $page;
   $data['status'] = $status;
   $data['query'] = $query;
   $data['num_rows'] = $num_rows;
   $data['num_pending'] = $num_pending;
   $data['num_processing'] = $num_processing;
   $data['num_shipped'] = $num_shipped;
   $data['num_delivered'] = $num_delivered;
   $data['num_canceled'] = $num_canceled;
   $data['num_all'] = $num_all;
   $data['total'] = $total['total'];
   load_view("index", $data);
}

function updateStatusAction()
{
   if (isset($_POST['sm_action'])) {
      if (isset($_POST['checkItem'])) {
         $checkItem = $_POST['checkItem'];
         $action = $_POST['actions'];
         $date = date('Y-m-d H:i:s', time());
         foreach ($checkItem as $v => $key) {
            $data = [
               'status' => $action,
               'updated_at' => $date,
            ];
            update_order_status($data, $v);
         }

        
      }
      redirect_to("?mod=orders&action=index");
   }

   if(isset($_POST['sm_status'])){
      $order_id = $_GET['order_id'];
      $status = $_POST['status'];
      $date = date('Y-m-d H:i:s', time());
      $data = [
         'status' => $status,
         'updated_at' => $date,
      ];
      update_order_status($data, $order_id);
      redirect_to("?mod=orders&action=detail&order_id={$order_id}");
   }

   
}

function detailAction()
{
   $order_id = $_GET['order_id'];

   $result = db_fetch_row("SELECT * FROM `tbl_orders` o
                               INNER JOIN `tbl_customer` c ON o.`order_id` = c.`customer_id`
                               
                                  WHERE o.`order_id` =  {$order_id}");

   $result_items = db_fetch_array("SELECT * FROM `tbl_order_items` o
                                       INNER JOIN `tbl_products` p ON o.`product_id` = p.`product_id`
                                       INNER JOIN `tbl_product_images` pi ON o.`product_id` = pi.`product_id`
                                       INNER JOIN `tbl_images` i ON pi.`image_id` = i.`image_id`
                                  WHERE `order_id` = {$order_id} AND pi.`pin` = 1");

   $data['result'] = $result;
   $data['result_items'] = $result_items;
   load_view("detail", $data);
}