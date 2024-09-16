<?php

function construct()
{
    load_model("index");
}

// Giao diện hiển thị
function indexAction()
{
    $product_id = $_GET['product_id'];
    $result = db_fetch_row("SELECT *
                        FROM `tbl_products`
                        WHERE `product_id` = '{$product_id}'");

    $result_image = db_fetch_array("SELECT *
                    FROM
                        `tbl_images` i
                    INNER JOIN `tbl_product_images` pi ON
                        i.`image_id` = pi.`image_id`
                    WHERE
                        pi.`product_id` = '{$product_id}'");



    $result_menu = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");


    $result_product = db_fetch_array(" SELECT p.*, i.file_name, pi.pin
                                       FROM tbl_products p
                                       INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
                                       INNER JOIN tbl_images i ON pi.image_id = i.image_id
                                       WHERE p.product_status = 'public' AND pi.pin = 1 AND p.category_id = '{$result['category_id']}' AND p.product_id != '{$product_id}'
                                       GROUP BY p.product_id
                                       ORDER BY p.product_id DESC; ");

    // show_array($result);
    if ($result['product_status'] == "public") {
        $data['product_status'] = "còn hàng";
    } else {
        $data['product_status'] = "hết hàng";
    }

    // tìm danh mục theo dạng Điện thoại > Samsung
    $category_id = $result['category_id'];
    $result_category = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE
     `category_status` = 'public'");
    $category = category($result_category, $category_id);
    //======

    $data['product_name'] = $result['product_name'];
    $data['product_desc'] = $result['product_desc'];
    $data['product_slug'] = $result['product_slug'];
    $data['product_id'] = $product_id;
    $data['product_details'] = $result['product_details'];
    $data['product_price'] = $result['product_price'];
    $data['stock_quantity'] = $result['stock_quantity'];

    $data['result'] = $result;
    $data['result_product'] = $result_product;
    $data['result_image'] = $result_image;
    $data['result_menu'] = $result_menu;
    $data['category'] = $category;


    load_view("index", $data);
}

function categoryProductAction()
{
    $category_slug = isset($_GET['category_slug']) ? $_GET['category_slug'] : "";

    $category = db_fetch_row("SELECT `category_id` FROM `tbl_product_categories` 
    WHERE `category_slug` = '{$category_slug}' AND `parent_id` != 0");
    $sort = isset($_GET['sort']) ? $_GET['sort'] : "";
    // Kiểm tra giá trị của tham số và xử lý theo yêu cầu
    if ($sort == "san-pham-moi-nhat" || $sort == "") {
        $sql_sort = "product_id DESC";
    } elseif ($sort == "gia-giam-dan") {
        $sql_sort = "product_price DESC";
    } elseif ($sort == "gia-tang-dan") {
        $sql_sort = "product_price ASC";
    }

    $result_menu = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");

    $num_product = 0;


    $query = isset($_GET['search']) ? urldecode(trim($_GET['search'])) : "";
    //=================================
    //PHÂN TRANG HIỆN THỊ SẢN PHẨM
    //=================================
    $page = isset($_GET['page']) ? urldecode($_GET['page']) : 1;
    //Số lưởng bản ghi trên trang
    $num_per_page = 20;
    //Chỉ số bắt đầu
    $start = ($page - 1) * $num_per_page;

    if ($query) {
        //=================================
        //HIỆN THỊ SẢN PHẨM NẾU TÌM KIẾM
        //=================================
        // Thực hiện truy vấn cơ sở dữ liệu
        $result['product'] = db_fetch_array("SELECT p.*, i.file_name, pi.pin, pc.*
            FROM tbl_products p
            INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
            INNER JOIN tbl_images i ON pi.image_id = i.image_id
            INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
            WHERE p.product_status = 'public' AND pi.pin = 1
            AND (p.product_name LIKE '%$query%' OR pc.category_name LIKE '%$query%')
            GROUP BY p.product_id
            ORDER BY p.{$sql_sort}
            LIMIT {$start}, {$num_per_page}
            ");
        // Mặc định nếu không có kết quả
        $category_menu = "<li class='disabled'><a title=''>Không có kết quả tìm kiếm cho <strong>$query</strong></a></li>";
        $category_name = $query;
        $result['count'] = 0;
        // Kiểm tra nếu có kết quả tìm kiếm
        if (is_array($result['product']) && !empty($result['product'])) {
            $num_product = 0;
            foreach ($result['product'] as $v) {
                $num_product++;
            }
            $result['count'] = $num_product;
            $result['category_name'] = $result['product'][0]['category_name'];
            $result['category_slug'] = $result['product'][0]['category_slug'];
            $category_id = $result['product'][0]['category_id'];

            // Cập nhật thông báo khi có kết quả tìm kiếm
            $category_menu = "<li class='disabled'><a title=''>Kết quả tìm kiếm cho <strong>$query</strong></a></li>";

        }
        $data['category_name'] = $category_name;

        $num_rows = db_num_rows("SELECT p.*, i.file_name, pi.pin, pc.*
        FROM tbl_products p
        INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
        INNER JOIN tbl_images i ON pi.image_id = i.image_id
        INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
        WHERE p.product_status = 'public' AND pi.pin = 1
        AND (p.product_name LIKE '%$query%' OR pc.category_name LIKE '%$query%')
        GROUP BY p.product_id
        ORDER BY p.product_id DESC");


    } else if ($category > 0) {
        //=================================
        //HIỆN THỊ SẢN PHẨM LÀ DANH MỤC CON
        //=================================
        $result['product'] = db_fetch_array("SELECT p.*, i.file_name, pi.pin, pc.*
        FROM tbl_products p
        INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
        INNER JOIN tbl_images i ON pi.image_id = i.image_id
        INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
        WHERE p.product_status = 'public' AND pi.pin = 1 AND pc.category_slug = '$category_slug'
        GROUP BY p.product_id
        ORDER BY p.{$sql_sort}
        LIMIT {$start}, {$num_per_page}");


        foreach ($result['product'] as $v) {
            $num_product++;
        }

        $result['count'] = $num_product;
        $result['category_name'] = $result['product'][0]['category_name'];
        $result['category_slug'] = $result['product'][0]['category_slug'];
        $category_id = $result['product'][0]['category_id'];
        $category_menu = category($result_menu, $category_id);


        $num_rows = db_num_rows("SELECT p.*, i.file_name, pi.pin, pc.*
        FROM tbl_products p
        INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
        INNER JOIN tbl_images i ON pi.image_id = i.image_id
        INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
        WHERE p.product_status = 'public' AND pi.pin = 1 AND pc.category_slug = '$category_slug'
        GROUP BY p.product_id
        ORDER BY p.product_id DESC");
    } else {
        //=================================
        //HIỆN THỊ SẢN PHẨM LÀ DANH MỤC CHA
        //=================================
        $result_product = show_product($result_menu, $sql_sort);

        // $result = $result_product[$category_slug];
        // $category_id = $result['category_id'];
        $num_rows = 0;
        // foreach ($result['product'] as $v) {
        //     $num_product++;
        //     // $num_rows++;
        // }
        $result = [];
        $i = 0;
        $sliced_products = array_slice($result_product[$category_slug]['product'], $start, $num_per_page);

        $result_test = $result_product[$category_slug];
        $result["category_name"] = $result_test['category_name'];
        $result["category_slug"] = $result_test['category_slug'];
        $result["category_id"] = $result_test['category_id'];
        $result["count"] = $result_test['count'];
        $num_rows = $result["count"];
        foreach ($sliced_products as $k) {
            $result['product'][$i] = $k;
            $num_product++;
            $i++;
        }
        $category_menu = category($result_menu, $result["category_id"]);
    }


    $total_row = $num_rows;
    $num_page = ceil($total_row / $num_per_page);

    $data['result_menu'] = $result_menu;
    $data['num_product'] = $num_product;
    $data['result'] = $result;
    $data['category_menu'] = $category_menu;
    $data['page'] = $page;
    $data['num_page'] = $num_page;
    $data['category_slug'] = $category_slug;
    $data['query'] = $query;
    $data['num_rows'] = $num_rows;
    

    load_view("categoryProduct", $data);
}
