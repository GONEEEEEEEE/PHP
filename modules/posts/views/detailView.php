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
<div id="main-content-wp" class="clearfix detail-blog-page">
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
                    <li class="disabled">
                        <a href="#" title=""><?php if (isset($post_title)) {
                            echo $post_title;
                        }
                        ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="detail-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title"><?php echo $result['post_title'] ?>
                    </h3>
                </div>
                <div class="section-detail">
                    <span class="create-date"><?php echo $result['created_at'] ?></span>
                    <div class="detail">
                        <?php echo $result['post_content'] ?>
                    </div>
                </div>
            </div>
            <!-- <div class="section" id="social-wp">
                <div class="section-detail">
                    <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                        data-show-faces="true" data-share="true"></div>
                    <div class="g-plusone-wp">
                        <div class="g-plusone" data-size="medium"></div>
                    </div>
                    <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                </div>
            </div> -->
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