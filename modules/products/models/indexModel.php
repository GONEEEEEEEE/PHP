<?php
function has_child($data, $id)
{
    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            return true;
        }
    }

    return false;
}

function array_categories($data, $parent_id = 0)
{
    $result = "<ul class='list-item'>";
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $result .= "<li>";
            $category_name = $v['category_name'];
            $slug = $v['category_slug'];
            $result .= "<a href='san-pham/$slug.html' title=''>$category_name</a>";
            // Nếu có phần tử con, gọi đệ quy và gộp kết quả vào mảng kết quả chính
            if (has_child($data, $v['category_id'])) {
                $result .= "<ul class='sub-menu'>";
                $result .= array_categories($data, $v['category_id']);
                $result .= "</ul>";
            }
            $result .= "</li>";
        }
    }
    $result .= "</ul>";
    return $result;
}

// Hàm để tìm đường dẫn từ danh mục hiện tại đến cha của nó
function find_category_path($data, $category_id)
{
    $path = [];
    foreach ($data as $v) {
        if ($v['category_id'] == $category_id) {
            $path[] = $v; // Thêm danh mục hiện tại vào đường dẫn
            if ($v['parent_id'] != 0) { // Nếu có cha, tiếp tục tìm cha của nó
                $path = array_merge($path, find_category_path($data, $v['parent_id']));
            }
            break;
        }
    }
    return $path;
}

function category($data, $category_id, $parent_id = 0)
{
    // Tìm đường dẫn đến danh mục đích
    $path = find_category_path($data, $category_id);
    $path_ids = array_column($path, 'category_id'); // Lấy danh sách các ID trong đường dẫn

    $result = ""; // Biến lưu trữ kết quả HTML

    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id && in_array($v['category_id'], $path_ids)) {
            $result .= "<li>";
            $result .= "<a href='san-pham/" . $v['category_slug'] . ".html' title='" . $v['category_name'] . "'>" . $v['category_name'] . "</a>";

            // Nếu có danh mục con, gọi đệ quy và gộp kết quả vào
            if (has_child($data, $v['category_id'])) {
                $result .= category($data, $category_id, $v['category_id']);
            }

            $result .= "</li>";
        }
    }

    return $result;
}

function show_product($data, $sql_sort, $parent_id = 0, &$total_count = 0)
{
    $result = [];
    $i = 0;
    foreach ($data as $v) {

        if ($v['parent_id'] == $parent_id) {
            // Khởi tạo mảng sản phẩm
            $products = [];
            $count = 0;

            // Lấy tất cả sản phẩm của danh mục hiện tại và các danh mục con
            $result_product = db_fetch_array("SELECT p.*, i.file_name, pi.pin
                FROM tbl_products p
                INNER JOIN tbl_product_images pi ON p.product_id = pi.product_id
                INNER JOIN tbl_images i ON pi.image_id = i.image_id
                INNER JOIN tbl_product_categories pc ON pc.category_id = p.category_id
                WHERE p.product_status = 'public' 
                AND pi.pin = 1 
                AND pc.category_id = {$v['category_id']}
                GROUP BY p.product_id
                ORDER BY p.{$sql_sort}
            ");

            if (!empty($result_product)) {

                foreach ($result_product as $p) {
                    $products[$p['product_id']] = [
                        'product_id' => $p['product_id'],
                        'product_name' => $p['product_name'],
                        'product_slug' => $p['product_slug'],
                        'product_price' => $p['product_price'],
                        'category_id' => $p['category_id'],
                        'file_name' => $p['file_name']
                    ];
                    $count++; // Đếm số sản phẩm trong danh mục hiện tại
                    $total_count++; // Đếm tổng số sản phẩm trong tất cả các danh mục
                }
            }

            // Xử lý các danh mục con
            $sub_result = show_product($data, $sql_sort, $v['category_id'], $total_count);

            // Gộp các sản phẩm từ danh mục con vào danh mục hiện tại
            foreach ($sub_result as $sub_item) {
                if (isset($sub_item['product']) && is_array($sub_item['product'])) {
                    foreach ($sub_item['product'] as $sub_product) {
                        $products[$sub_product['product_id']] = $sub_product;
                        $count++; // Đếm thêm số sản phẩm từ danh mục con
                    }
                }
            }

            // Chỉ thêm danh mục vào kết quả nếu có sản phẩm
            $result[$v['category_slug']] = [
                'category_name' => $v['category_name'],
                'category_slug' => $v['category_slug'],
                'category_id' => $v['category_id'],
                'count' => $count,
                'product' => $products
            ];
            $i++;
        }
    }

    return $result;
}
