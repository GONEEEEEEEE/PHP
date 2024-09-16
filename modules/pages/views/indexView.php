<?php get_header(); ?>
<div id="main-content-wp" class="clearfix blog-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title=""><?php echo $page_title; ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div>
           <?php 
           echo $content;
           ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>