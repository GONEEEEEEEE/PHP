<?php

function construct()
{
   load_model("index");
}

// Giao diện hiển thị
function indexAction()
{
   // product nổi bật
   $result_featured = db_fetch_array("SELECT p.*, i.file_name, pi.pin
                                       FROM tbl_products p
                                       INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
                                       INNER JOIN tbl_images i ON pi.image_id = i.image_id
                                       INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
                                       WHERE p.is_featured = 'yes' AND p.product_status = 'public' AND pi.pin = 1 AND pc.category_status = 'public'
                                       GROUP BY p.product_id
                                       ORDER BY p.product_id DESC;");
   // show menu
   $result_menu = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");

   // Danh sách sản phẩm

   // $result_product = db_fetch_array("SELECT p.*, i.file_name, pi.pin
   //                                     FROM tbl_products p
   //                                     INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
   //                                     INNER JOIN tbl_images i ON pi.image_id = i.image_id
   //                                     WHERE p.product_status = 'public' AND pi.pin = 1
   //                                     GROUP BY p.product_id
   //                                     ORDER BY p.product_id DESC;");

   $result = show_product($result_menu);
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


   //=====================
   $data['result_featured'] = $result_featured;
   $data['result'] = $result;
   $data['result_sell'] = $result_sell;
   $data['result_menu'] = $result_menu;


   $get = isset($_GET['search']) ? (string)$_GET['search'] : "";
   // echo $get;
   if ($get != "") {
      redirect_to("search=" . urlencode($get));
      return;
   }

   load_view('index', $data);
}
