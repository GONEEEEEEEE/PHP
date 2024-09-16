<?php

function construct()
{

}

// Giao diện hiển thị
function indexAction()
{

   $page = isset($_GET['page']) ? urldecode($_GET['page']) : 1;



   //Số lưởng bản ghi trên trang
   $num_per_page = 20;
   //Chỉ số bắt đầu
   $start = ($page - 1) * $num_per_page;

   $result = db_fetch_array("SELECT * FROM `tbl_posts` p
   INNER JOIN tbl_images i ON p.image_id = i.image_id
   WHERE  `post_status` = 'published'
   ORDER BY `post_id` DESC
   LIMIT {$start}, {$num_per_page} ");

   $num_rows = db_num_rows("SELECT * FROM `tbl_posts` WHERE  `post_status` = 'published'");
   $total_row = $num_rows;

   $num_page = ceil($total_row / $num_per_page);
   // product bán chạy
   $result_sell = db_fetch_array("SELECT
      COUNT(o.product_id) as Tong, p.*, i.file_name
      FROM
         `tbl_order_items` o
      INNER JOIN `tbl_products` p ON
      o.product_id = p.product_id
      INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
      INNER JOIN tbl_images i ON pi.image_id = i.image_id
      INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
      WHERE p.product_status = 'public' AND pi.pin = 1 AND pc.category_status = 'public'
      GROUP BY o.product_id
      ORDER BY Tong DESC LIMIT 8");
   $data['result_sell'] = $result_sell;

   $data['result'] = $result;
   $data['num_page'] = $num_page;
   $data['page'] = $page;

   //
   load_view("index", $data);
}

function detailAction()
{
   $post_id = urldecode($_GET['post_id']);

   $result = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `post_id` = '{$post_id}'");

   $result_sell = db_fetch_array("SELECT
   COUNT(o.product_id) as Tong, p.*, i.file_name
   FROM
      `tbl_order_items` o
   INNER JOIN `tbl_products` p ON
   o.product_id = p.product_id
   INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
   INNER JOIN tbl_images i ON pi.image_id = i.image_id
   INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
   WHERE p.product_status = 'public' AND pi.pin = 1 AND pc.category_status = 'public'
   GROUP BY o.product_id
   ORDER BY Tong DESC LIMIT 8");

   $data['result_sell'] = $result_sell;
   $data['result'] = $result;
   $data['post_title'] = $result['post_title'];
   // show_array($result);

   load_view("detail", $data);
}
