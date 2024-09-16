<?php
function construct()
{
    load_model('team');
    load_model('index');
    // load('lib', 'email');
    // load('lib', 'validation');
}

// Giao diện hiển thị
function indexAction()
{
    global $num_users, $num_user_active, $num_user_inactive, $num_user_banned, $num_page, $page;
    // global $query, $data, $status;
    // Kiểm tra xem $query và $status có tồn tại hay không
    $query = isset($_GET['s']) ? $_GET['s'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // ==========PAGGING===============

    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    //Số lưởng bản ghi trên trang
    $num_per_page = 10;

    // =========================
    $num_users = 0;
    $num_user_active = 0;
    $num_user_inactive = 0;
    $num_user_banned = 0;

    //Hiện thị danh sách nếu có tìm kiếm

    if ($query) {
        //Câu lệnh sql tìm kiếm
        $sql = "SELECT * FROM `tbl_users` WHERE (`username` LIKE '%{$query}%' OR 
     `fullname` LIKE '%{$query}%' OR `email` LIKE '%{$query}%' OR 
     `tel` LIKE '%{$query}%' OR `address` LIKE '%{$query}%' OR `status` LIKE '%{$query}%')";
        $num_users = db_num_rows($sql);

        //kiếm tra status có khác all và khác rỗng không
        if ($status != 'all' && $status != "") {
            $sql .= " AND `status` = '{$status}'";
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
            $result = get_user_query($start, $num_per_page, $sql);
        }
        //Đếm số người actice
        $num_user_active = status_users_query($query, 'active');
        $num_user_inactive = status_users_query($query, 'inactive');
        $num_user_banned = status_users_query($query, 'banned');

        //lấy kết quả mảng
        $data['result'] = $result;
        //dữ liệu cho việc dùng hàm get_pagging()
        $data['num_page'] = $num_page;
        $data['page'] = $page;
        //Dữ liệu cho tìm kiếm
        $data['query'] = $query;
        //Dữ liệu trong trạng thái
        $data['status'] = $status;

        //số lượng user các loại
        $data['num_users'] = $num_users;
        $data['num_user_active'] = $num_user_active;
        $data['num_user_inactive'] = $num_user_inactive;
        $data['num_user_banned'] = $num_user_banned;
        load_view('index', $data);
        exit;
    }

    //Hiện thị danh sách nếu KHÔNG tìm kiếm
    if ($status == '' || $status == 'all') {
        $result = db_fetch_array("SELECT * FROM `tbl_users`");
        $num_rows = db_num_rows("SELECT * FROM `tbl_users`");
    } else {
        $result = num_status_users($status);
        $num_rows = db_num_rows("SELECT * FROM `tbl_users` WHERE `status` = '$status'");

    }

    if ($page) {
        //Tổng số bản ghi
        $total_row = $num_rows;
        //Tổng số trang
        $num_page = ceil($total_row / $num_per_page);
        //Chỉ số bắt đầu
        $start = ($page - 1) * $num_per_page;

        if ($status == '' || $status == 'all') {
            $result = get_user($start, $num_per_page);
        } else {
            $result = get_user($start, $num_per_page, " WHERE `status` = '$status'");
        }
    }

    $query = "";
    $num_users = db_num_rows("SELECT * FROM `tbl_users`");
    $num_user_active = status_users('active');
    $num_user_inactive = status_users('inactive');
    $num_user_banned = status_users('banned');

    $data['query'] = $query;
    $data['status'] = $status;
    $data['result'] = $result;
    $data['num_page'] = $num_page;
    $data['page'] = $page;

    $data['num_users'] = $num_users;
    $data['num_user_active'] = $num_user_active;
    $data['num_user_inactive'] = $num_user_inactive;
    $data['num_user_banned'] = $num_user_banned;

    load_view('index', $data);
}


function statusAction()
{
    global $num_users, $num_user_active, $num_user_inactive, $num_user_banned;
    if (isset($_POST['btn_action'])) {
        if (isset($_POST['checkItem'])) {
            $checkedItems = $_POST['checkItem'];
            $action = $_POST['actions'];
            if ($action != "") {
                foreach ($checkedItems as $id => $key) {
                    // echo $id;
                    $data = array(
                        'status' => $action,
                    );
                    update_status($data, $id);
                }
            }

        }
    }
    redirect_to("?mod=users&controller=team&action=index");
}






?>