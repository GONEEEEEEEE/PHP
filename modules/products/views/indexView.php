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
<div id="main-content-wp" class="clearfix detail-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="trang-chu.html" title="">Trang chủ</a>
                    </li>
                    <?php
                    echo $category;
                    ?>
                    <li>
                        <a href="san-pham/<?php echo $product_slug ?>-<?php echo $product_id ?>.html"
                            title=""><?php echo $product_name; ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="detail-product-wp">
                <div class="section-detail clearfix">
                    <div class="thumb-wp fl-left">
                        <?php
                        if (isset($result_image)) {
                            foreach ($result_image as $v) {
                                if ($v['pin'] == 1) {
                                    ?>
                                    <a href="san-pham/<?php echo $product_slug ?>-<?php echo $product_id ?>.html" title="" id="main-thumb">
                                        <img id="zoom" src="public/images/<?php echo $v['file_name'] ?>"
                                            data-zoom-image="public/images/<?php echo $v['file_name'] ?>" />
                                    </a>
                                    <?php
                                }
                            } ?>
                            <div id="list-thumb">
                                <?php
                                foreach ($result_image as $v) {
                                    ?>
                                    <a href="san-pham/<?php echo $product_slug ?>-<?php echo $product_id ?>.html" data-image="public/images/<?php echo $v['file_name'] ?>"
                                        data-zoom-image="public/images/<?php echo $v['file_name'] ?>">
                                        <img id="zoom" src="public/images/<?php echo $v['file_name'] ?>" />
                                    </a>
                                    <?php
                                }
                        }
                        ?>
                        </div>
                    </div>
                    <div class="thumb-respon-wp fl-left">
                    <?php
                        if (isset($result_image)) {
                            foreach ($result_image as $v) {
                                if ($v['pin'] == 1) {
                                    ?>
                                    <a href="san-pham/<?php echo $product_slug ?>-<?php echo $product_id ?>.html" title="" id="main-thumb">
                                        <img id="zoom" src="public/images/<?php echo $v['file_name'] ?>"
                                            data-zoom-image="public/images/<?php echo $v['file_name'] ?>" />
                                    </a>
                                    <?php
                                }
                            } 
                        }
                        ?>
                    </div>
                    <div class="info fl-right">
                        <h3 class="product-name"><?php echo $product_name ?></h3>
                        <div class="desc">
                            <?php echo $product_desc ?>
                        </div>
                        <div class="num-product">
                            <span class="title">Sản phẩm: </span>
                            <span class="status"> <?php echo $product_status ?></span>
                        </div>
                        <p class="price"> <?php echo currency_format($product_price) ?></p>
                        <div id="num-order-wp">
                            <a title="" id="minus"><i class="fa fa-minus"></i></a>
                            <input type="text" name="num-order" value="1" id="num-order" disabled>
                            <input type="hidden" id="max-order" value="<?php echo $stock_quantity ?>">
                            <a title="" id="plus"><i class="fa fa-plus"></i></a>
                        </div>
                        <a href="them-vao-gio-hang/<?php echo $product_slug ?>-<?php echo $$product_id ?>.html"
                            data-product-id="<?php echo $product_id ?>" title="" class="add-cart fl-left"
                            id="add-cart">Thêm giỏ
                            hàng</a>
                    </div>
                </div>
            </div>
            <div class="section" id="post-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Mô tả sản phẩm</h3>
                </div>
                <div class="section-detail">
                    <?php echo $product_details ?>
                </div>
            </div>
            <div class="section" id="same-category-wp">
                <?php
                if (isset($result_product)) {
                    ?>
                    <div class="section-head">
                        <h3 class="section-title">Cùng chuyên mục</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            <?php
                            foreach ($result_product as $v) {
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
                                        <a href="them-vao-gio-hang/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            data-product-id="<?php echo $v['product_id'] ?>" title=""
                                            class="add-cart fl-left">Thêm giỏ hàng</a>
                                        <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="buy-now fl-right">Mua ngay</a>
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
        <div class="sidebar fl-left">
            <div class="section" id="category-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Danh mục sản phẩm</h3>
                </div>
                <div class="secion-detail">
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