<style>
    .thumb img {
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
<?php get_header(); ?>
<div id="main-content-wp" class="clearfix blog-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="trang-chu.html" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="bai-viet.html" title="">Bài Viết</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title">Blog</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        <?php
                        if (isset($result)) {
                            foreach ($result as $v) {
                                ?>
                                <li class="clearfix">
                                    <a href="bai-viet/<?php echo $v['post_slug'] ?>-<?php echo $v['post_id'] ?>.html" title="<?php echo $v['post_slug'] ?>" class="thumb fl-left">
                                        <img src="public/images/<?php echo $v['file_name'] ?>" alt="">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="bai-viet/<?php echo $v['post_slug'] ?>-<?php echo $v['post_id'] ?>.html" title="<?php echo $v['post_slug'] ?>" class="title"><?php echo $v['post_title'] ?></a>
                                        <span class="create-date"><?php echo $v['created_at'] ?></span>
                                        <p class="desc"><?php echo $v['post_desc'] ?></p>
                                    </div>
                                </li>
                                <?php
                            }
                        } else {
                            echo "Không có bài viết";
                        }
                        ?>


                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section" id="paging-wp">
                    <div class="section-detail">
                        <?php
                        get_pagging($num_page, $page, "bai-viet.html");
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar fl-left">
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
                                        <a href="mua-ngay/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="buy-now">Mua ngay</a>
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