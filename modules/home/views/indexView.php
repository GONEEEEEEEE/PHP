<style>
    .thumb img {
        max-height: 105px;
        display: block;
        margin: 0px auto;
    }

    .product-name {
        min-height: 50px;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
    }
</style>
<?php
// echo $get;
get_header(); ?>
<div id="main-content-wp" class="home-page clearfix">
    <div class="wp-inner">
        <div class="main-content fl-right">
            <?php get_template_part("adv"); ?>
            <?php
            if (isset($result_featured)) {
                ?>
                <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm nổi bật</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            <?php
                            foreach ($result_featured as $v) {
                                ?>
                                <li>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="thumb">
                                        <img src="public/images/<?php echo $v['file_name'] ?>">
                                    </a>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="product-name"><?php echo $v['product_name'] ?></a>
                                    <div class="price">
                                        <span class="new"><?php echo currency_format($v['product_price']) ?></span>
                                    </div>
                                    <div class="action clearfix">
                                        <a href="them-vao-gio-hang/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" data-product-id="<?php echo $v['product_id'] ?>" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                        <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" title="" class="buy-now fl-right">Mua ngay</a>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="section" id="list-product-wp">
                <?php

                foreach ($result as $category_name => $i) {
                    ?>
                    <div class="section-head">
                        <h3 class="section-title"><?php echo $i['category_name']; ?></h3>
                        <a href="san-pham/<?php echo $i['category_slug'] ?>.html">Xem tất cả >>></a>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <?php
                            $check = 1;
                            foreach ($i['product'] as $v) {
                                ?>
                                <li>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="thumb">
                                        <img src="public/images/<?php echo $v['file_name'] ?>">
                                    </a>
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="product-name"><?php echo $v['product_name'] ?></a>
                                    <div class="price">
                                        <span class="new"><?php echo currency_format($v['product_price']) ?></span>
                                    </div>
                                    <div class="action clearfix">
                                    <a href="them-vao-gio-hang/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" data-product-id="<?php echo $v['product_id'] ?>" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                    <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" title="" class="buy-now fl-right">Mua ngay</a>
                                    </div>
                                </li>
                                <?php
                                if ($check >= 8) {
                                    break;
                                }
                                $check++;
                            }

                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
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
            <div class="section" id="selling-wp">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm bán chạy</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        <?php
                        if (isset($result_sell)) {
                            foreach ($result_sell as $v) {
                                ?>
                                <li class="clearfix">
                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                        title="" class="thumb fl-left">
                                        <img src="public/images/<?php echo $v['file_name'] ?>" alt="">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="product-name"><?php echo $v['product_name'] ?></a>
                                        <div class="price">
                                            <span class="new"><?php echo currency_format($v['product_price']) ?></span>
                                        </div>
                                        <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html" title="" class="buy-now">Mua ngay</a>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>


                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
