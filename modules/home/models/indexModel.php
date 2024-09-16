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

function show_product($data, $parent_id = 0)
{
    $result = [];
    $i = 0;

    foreach ($data as $v) {

        if ($v['parent_id'] == $parent_id) {
            // Khởi tạo mảng sản phẩm
            $products = [];

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
                ORDER BY p.product_id DESC
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
                }
            }

            // Xử lý các danh mục con
            $sub_result = show_product($data, $v['category_id']);

            // Gộp các sản phẩm từ danh mục con vào danh mục hiện tại
            foreach ($sub_result as $sub_item) {
                if (isset($sub_item['product']) && is_array($sub_item['product'])) {
                    foreach ($sub_item['product'] as $sub_product) {
                        $products[$sub_product['product_id']] = $sub_product;
                    }
                }
            }

            // Chỉ thêm danh mục vào kết quả nếu có sản phẩm
            if (!empty($products)) {
                $result[$i] = [
                    'category_name' => $v['category_name'],
                    'category_slug' => $v['category_slug'],
                    'category_id' => $v['category_id'],
                    'product' => $products
                ];
                $i++;
            }
        }
    }

    return $result;
}