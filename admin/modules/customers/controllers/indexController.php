<?php

function construct()
{
   load_model('index');
   load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
   global $num_page, $page;
   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   $query = isset($_GET['s']) ? $_GET['s'] : "";
   $num_per_page = 20;
   $start = ($page - 1) * $num_per_page;
   if ($query != "") {
      $result = db_fetch_array("SELECT * FROM `tbl_customer` 
                          WHERE `fullname` LIKE '%{$query}%' OR `phone_number` LIKE '%{$query}%' OR `email` LIKE '%{$query}%' 
                          ORDER BY `customer_id` DESC");
      $num_rows = db_num_rows("SELECT * FROM `tbl_customer` WHERE `fullname` LIKE '%{$query}%' 
                          OR `email` LIKE '%{$query}%' 
                          OR `phone_number` LIKE '%{$query}%' ");
   } else {
      $result = db_fetch_array("SELECT * FROM `tbl_customer` ORDER BY `customer_id` DESC LIMIT $start, $num_per_page");
      $num_rows = db_num_rows("SELECT * FROM `tbl_customer`");
   }

   $num_page = ceil($num_rows / $num_per_page);
   $data['query'] = $query;
   $data['result'] = $result;
   $data['num_rows'] = $num_rows;
   $data['num_page'] = $num_page;
   $data['page'] = $page;
   load_view("index", $data);
}

function updateAction()
{
   $customer_id = $_GET['customer_id'];

   $result = db_fetch_row("SELECT * FROM `tbl_customer` WHERE `customer_id` = {$customer_id}");

   if (isset($_POST['btn-update'])) {
      $error = array();

      if (empty($_POST['fullname'])) {
         $error['fullname'] = "Họ tên không được để trống";
      } else {
         if (strlen($_POST['fullname']) < 3) {
            $error['fullname'] = "Họ tên không được quá ngắn";
         }
         $fullname = $_POST['fullname'];
      }

      if (empty($_POST['email'])) {
         $error['email'] = "Email không được để trống";
      } else {
         if (!is_email($_POST['email'])) {
            $error['email'] = "Email không đúng định dạng";
         }
         $email = $_POST['email'];
      }

      if (empty($_POST['phone_number'])) {
         $error['phone_number'] = "Số điện thoại không được để trống";
      } else {
         if (!is_tel($_POST['phone_number'])) {
            $error['phone_number'] = "Số điện thoại không đúng định dạng";
         }
         $phone_number = $_POST['phone_number'];
      }

      if (empty($_POST['address'])) {
         $error['address'] = "Địa chỉ không được để trống";
      } else {
         $address = $_POST['address'];
      }

      if (empty($error)) {
         $customer = [
            'fullname' => $fullname,
            'email' => $email,
            'phone_number' => $phone_number,
            'address' => $address,
         ];
         update_customer($customer_id, $customer);
         redirect_to("?mod=customers&action=index");
      } else {
         $data['error'] = $error;
      }
   }
   $data['result'] = $result;
   load_view("update", $data);
}
