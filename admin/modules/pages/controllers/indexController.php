<?php

function construct()
{
   load_model('index');
   load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{  
   global $num_page, $page, $status;
   // Kiểm tra xem $query và $status có tồn tại hay không
   $query = isset($_GET['s']) ? $_GET['s'] : '';
   $status = isset($_GET['status']) ? $_GET['status'] : '';
   
   // ==========PAGGING===============

   $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
   //Số lưởng bản ghi trên trang
   $num_per_page = 10;

   // =========================
   $num_pages = 0;
   $num_pages_draft = 0;
   $num_pages_published = 0;
   $num_pages_pending = 0;
   $num_pages_archived = 0;

   //Hiện thị danh sách nếu KHÔNG tìm kiếm
   if ($status == '' || $status == 'all') {
      $result = db_fetch_array("SELECT * FROM `tbl_pages` ");
      $num_rows = db_num_rows("SELECT * FROM `tbl_pages`");
   } else {
      $result = num_status_pages($status);
      $num_rows = db_num_rows("SELECT * FROM `tbl_pages` WHERE `page_status` = '$status' ");

   }

   if ($page) {
      //Tổng số bản ghi
      $total_row = $num_rows;
      //Tổng số trang
      $num_page = ceil($total_row / $num_per_page);
      //Chỉ số bắt đầu
      $start = ($page - 1) * $num_per_page;

      if ($status == '' || $status == 'all') {
         $result = get_pages($start, $num_per_page);
      } else {
         $result = get_pages($start, $num_per_page, " WHERE `page_status` = '$status'");
      }
   }

   $query = "";
   $num_pages = db_num_rows("SELECT * FROM `tbl_pages`");
   $num_pages_draft = num_status_pages('draft');
   $num_pages_published = num_status_pages('published');
   $num_pages_pending = num_status_pages('pending');
   $num_pages_archived = num_status_pages('archived');

   //Hiện thị danh sách nếu có tìm kiếm
   if ($query) {
      //Câu lệnh sql tìm kiếm
      
      $sql = "SELECT * FROM `tbl_pages` WHERE (`page_id` LIKE '%{$query}%' OR 
     `page_title` LIKE '%{$query}%' OR `page_content` LIKE '%{$query}%' OR 
     `page_status` LIKE '%{$query}%' OR `user_id` LIKE '%{$query}%' OR `created_at` LIKE '%{$query}%')";

      $num_pages = db_num_rows($sql);

      //kiếm tra status có khác all và khác rỗng không
      if ($status != 'all' && $status != "") {
         $sql .= " AND `page_status` = '{$status}'";
      }
      //lấy kết quả có $sql
      $result = db_fetch_array($sql);

      //kiếm tra xem có phân trang không
      if ($page) {
         //hàm phân trang
         $num_rows = db_num_rows($sql);
         //Tổng số bản ghi
         $total_row = $num_rows;
         //Tổng số trang
         $num_page = ceil($total_row / $num_per_page);
         //Chỉ số bắt đầu
         $start = ($page - 1) * $num_per_page;
         //Lấy kết quả của hàm phân trang
         $result = get_pages_query($start, $num_per_page, $sql);
      }
      //Đếm số pages actice

      $num_pages_draft = num_status_pages_query('draft', $query);
      $num_pages_published = num_status_pages_query('published', $query);
      $num_pages_pending = num_status_pages_query('pending', $query);
      $num_pages_archived = num_status_pages_query('archived', $query);

      //lấy kết quả mảng
      $data['result'] = $result;
      //dữ liệu cho việc dùng hàm get_pagging()
      $data['page'] = $page;
      //Dữ liệu cho tìm kiếm
      $data['query'] = $query;
      //Dữ liệu trong trạng thái
      $data['status'] = $status;

      //số lượng user các loại
      $data['num_pages'] = $num_pages;
      $data['num_pages_draft'] = $num_pages_draft;
      $data['num_pages_published'] = $num_pages_published;
      $data['num_pages_pending'] = $num_pages_pending;
      $data['num_pages_archived'] = $num_pages_archived;

      load_view('index', $data);
   }



   $data['query'] = $query;
   $data['status'] = $status;
   $data['result'] = $result;
   // $data['num_page'] = $num_page;
   $data['page'] = $page;

   $data['num_pages'] = $num_pages;
   $data['num_pages_draft'] = $num_pages_draft;
   $data['num_pages_published'] = $num_pages_published;
   $data['num_pages_pending'] = $num_pages_pending;
   $data['num_pages_archived'] = $num_pages_archived;

   load_view('index', $data);
}

function createAction()
{
   global $error, $title, $slug, $desc, $status, $success;

   if (isset($_POST['btn-create'])) {
      $error = array();

      //====================
      //check title
      //====================

      if (empty($_POST['title'])) {
         $error['title'] = "Tiêu đề không được để trống";
      } else {
         if (strlen($_POST['title']) < 3) {
            $error['title'] = "Tiêu đề không được quá ngắn";
         }
         $title = $_POST['title'];
      }

      //====================
      //check slug
      //====================

      if (empty($_POST['slug'])) {
         $error['slug'] = "Slug không được để trống";
      } else {
         $slug = $_POST['slug'];
      }

      //====================
      //check content
      //====================

      if (empty($_POST['desc'])) {
         $error['desc'] = "Nội dung không được để trống";
      } else {
         $desc = $_POST['desc'];
      }

      //====================
      //check content
      //====================

      if (empty($_POST['status'])) {
         $error['status'] = "Trạng thái không được để trống";
      } else {
         $status = $_POST['status'];
      }

      //====================
      //check error
      //====================

      if (empty($error)) {
         $user_id = $_SESSION['user_id'];
         $date = date('Y-m-d H:i:s', time());
         $data_pages = [
            'page_title' => $title,
            'page_slug' => $slug,
            'page_content' => $desc,
            'page_status' => $status,
            'user_id ' => $user_id,
            'updated_at' => $date,
         ];
         create_page($data_pages);
         $success = "<div class='success'>
                        <p>Tạo trang mới thành công</p>
                    </div>";

         $data['success'] = $success;
         $title = "";
         $slug = "";
         $desc = "";
         $status = "";
         load_view('create', $data);

         exit;
      } else {
         $data['error'] = $error;
         load_view('create', $data);
         exit;
      }

   }
   load_view('create');
}

function updateAction()
{
   global $error, $title, $slug, $desc, $status, $success, $result;
   $page_id = $_GET['page_id'];
   $result = db_fetch_row("SELECT * FROM `tbl_pages` WHERE `page_id` = {$page_id}");
   $status = $result['page_status'];
   if (isset($_POST['btn-update'])) {
      $error = array();

      //====================
      //check title
      //====================

      if (empty($_POST['title'])) {
         $error['title'] = "Tiêu đề không được để trống";
      } else {
         if (strlen($_POST['title']) < 3) {
            $error['title'] = "Tiêu đề không được quá ngắn";
         }
         $title = $_POST['title'];
      }

      //====================
      //check slug
      //====================

      if (empty($_POST['slug'])) {
         $error['slug'] = "Slug không được để trống";
      } else {
         $slug = $_POST['slug'];
      }

      //====================
      //check content
      //====================

      if (empty($_POST['desc'])) {
         $error['desc'] = "Nội dung không được để trống";
      } else {
         $desc = $_POST['desc'];
      }

      //====================
      //check content
      //====================

      if (empty($_POST['status'])) {
         $error['status'] = "Trạng thái không được để trống";
      } else {
         $status = $_POST['status'];
      }

      //====================
      //check error
      //====================

      if (empty($error)) {
         $date = date('Y-m-d H:i:s', time());
         $data_pages = [
            'page_title' => $title,
            'page_slug' => $slug,
            'page_content' => $desc,
            'page_status' => $status,
            'updated_at' => $date,
         ];
         update_page($data_pages, "page_id = {$page_id}");
         $success = "<div class='success'>
                        <p>Cập nhật trang thành công</p>
                    </div>";

         $data['success'] = $success;
         $data['status'] = $status;
         load_view('update', $data);
         exit;
      } else {
         $data['error'] = $error;
         load_view('update', $data);
         exit;
      }
   }
   $data['result'] = $result;
   load_view('update', $data);
}

function update_statusAction()
{
   if (isset($_POST['sm_action'])) {
      if (isset($_POST['checkItem'])) {
         $checkedItems = $_POST['checkItem'];
         $action = $_POST['actions'];
         if ($action != "") {
            foreach ($checkedItems as $id => $key) {
               // echo $id;
               $date = date('Y-m-d H:i:s', time());
               $data = array(
                  'page_status' => $action,
                  'updated_at' => $date,
               );
               update_status($data, $id);
            }
         }

      }
   }
   redirect_to("?mod=pages&action=index");
   // load_view('index');
}

function delete_pageAction()
{

   $id = $_GET['page_id'];
   $date = date('Y-m-d H:i:s', time());
   $data = array(
      'page_status' => "archived",
      'updated_at' => $date,
   );
   update_status($data, $id);
   redirect_to("?mod=pages&action=index");
}