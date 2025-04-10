<style>
    .product-name {
        min-height: 50px;
    }

    #num-order-wp {
        display: flex;
    }

    #main-thumb #zoom {
        max-width: 350px;
        max-height: auto;
        min-width: 350px;
        min-height: 194px;
    }

    #minus,
    #plus {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    #list-thumb {
        justify-content: space-between !important;
    }

    #list-thumb .owl-wrapper {
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
    }

    .list-item img {
        width: 191px;
        height: 127px;
        margin: 0px auto;
    }
</style>
<?php get_header(); ?>
<div id="main-content-wp" class="clearfix category-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="trang-chu.html" title="">Trang chủ</a>
                    </li>
                    <?php
                    echo $category_menu;
                    ?>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-product-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title fl-left">
                        <?php if (isset($category_name)) {
                            echo $category_name;
                        } else {
                            echo $result['category_name'];
                        } ?>
                    </h3>
                    <div class="filter-wp fl-right">
                        <p class="desc">Hiển thị <?php echo $num_product; ?> trên <?php echo $num_rows; ?> sản
                            phẩm</p>
                        <div class="form-filter">
                            <form method="GET" action="san-pham/<?php echo ($category_slug); ?>.html">
                                <select name="sort">
                                    <option value="san-pham-moi-nhat">Sản phẩm mới nhất</option>
                                    <option value="gia-giam-dan">Giá cao xuống thấp</option>
                                    <option value="gia-tang-dan">Giá thấp lên cao</option>
                                </select>
                                <button type="submit">Lọc</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="section-detail">
                    <ul class="list-item clearfix" id="product-list">
                        <?php
                        // show_array($result);
                        if (empty($result['product'])) {
                            ?>
                            <p>Không có dữ liệu</p>
                            <?php
                        } else {
                            foreach ($result['product'] as $v) {
                                ?>
                                <li>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="thumb">
                                        <img src="public/images/<?php echo $v['file_name'] ?>">
                                    </a>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="product-name"><?php echo $v['product_name'] ?></a>

                                    <div class="price">
                                        <span class="new"><?php echo currency_format($v['product_price']); ?></span>
                                    </div>
                                    <div class="action clearfix">
                                    <a href="them-vao-gio-hang/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" data-product-id="<?php echo $v['product_id'] ?>" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                    <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                    </div>
                                </li>
                                <?php
                            }
                        }

                        ?>

                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail">
                    <?php
                    if (isset($query) && $query != "") {
                        get_pagging($num_page, $page, "search=$query");
                        ?>
                        <input type="hidden" id="slug" value="<?php echo $query ?>">
                        <?php

                    } else if (isset($category_slug)) {
                        get_pagging($num_page, $page, "san-pham/{$category_slug}.html");
                        ?>
                            <input type="hidden" id="slug" value="<?php echo $category_slug ?>">
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="sidebar fl-left">
            <div class="section" id="category-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Danh mục sản phẩm</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        <?php
                        echo array_categories($result_menu);
                        ?>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php get_footer(); ?>