<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <meta charset="UTF-8">
    <base href="<?php echo base_url(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="public/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="public/reset.css" rel="stylesheet" type="text/css" />
    <link href="public/css/carousel/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="public/css/carousel/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="public/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="public/style.css" rel="stylesheet" type="text/css" />
    <link href="public/responsive.css" rel="stylesheet" type="text/css" />
    <link href="public/pagging.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="public/js/elevatezoom-master/jquery.elevatezoom.js" type="text/javascript"></script>
    <script src="public/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="public/js/carousel/owl.carousel.js" type="text/javascript"></script>


    <script src="public/js/main.js" type="text/javascript"></script>
</head>
<style>
    .disabled a {
        pointer-events: none;
        color: gray;
    }

    .disabled {
        cursor: text;
    }
    /* Hiệu ứng loading */

</style>

<body>
    

    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <?php
                                $result = db_fetch_array("SELECT * FROM `tbl_pages` WHERE `page_status` =  'published'");
                                foreach ($result as $v) {
                                    ?>
                                    <li>
                                        <a href="<?php echo $v['page_slug'] ?>.html"
                                            title=""><?php echo $v['page_title'] ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="trang-chu.html" title="" id="logo" class="fl-left"><img
                                src="public/images/logo.png" /></a>
                        <div id="search-wp" class="fl-left">
                            <!-- <form method="GET" action="">
                                <input type="hidden" name="mod" value="products">
                                <input type="hidden" name="action" value="categoryProduct">
                                <input type="text" name="search" id="s" placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                            </form> -->
                            <form method="GET" action="?mod=products&action=categoryProduct">
                                <input type="text" name="search" id="s" placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                            <a href="gio-hang" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num"><?php
                                if (isset($_SESSION['cart']['count'])) {
                                    echo $_SESSION['cart']['count'];
                                } else {
                                    echo 0;
                                } ?></span>
                            </a>
                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="num"><?php
                                    if (isset($_SESSION['cart']['count'])) {
                                        echo $_SESSION['cart']['count'];
                                    } else {
                                        echo 0;
                                    } ?></span>
                                </div>
                                <div id="dropdown">
                                    <p class="desc">Có <span id="num_product"><?php
                                    if (isset($_SESSION['cart']['count'])) {
                                        echo $_SESSION['cart']['count'];
                                    } else {
                                        echo 0;
                                    }
                                    ?> sản phẩm</span> trong giỏ hàng</p>
                                    <ul class="list-cart" id="list-cart-js">
                                        <?php
                                        if (isset($_SESSION['cart']['buy'])) {
                                            foreach ($_SESSION['cart']['buy'] as $v) {
                                                ?>
                                                <li class="clearfix">
                                                    <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                                        title="" class="thumb fl-left">
                                                        <img src="public/images/<?php echo $v['file_name'] ?>"
                                                            alt="<?php echo $v['file_name'] ?>">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                                            title="" class="product-name"
                                                            style="min-height: 50px;"><?php echo $v['product_name'] ?></a>
                                                        <p class="price"><?php echo currency_format($v['product_price']) ?></p>
                                                        <p class="qty">Số lượng: <span><?php echo $v['qty'] ?></span></p>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="clearfix" style="height: 100px; display: grid; align-items: center;">
                                                <p style="color:black; text-align:center">Không có sản phẩm trong giỏ hàng
                                                </p>
                                                <a href="trang-chu.html" style="display:block; text-align:center">Ấn vào đây
                                                    để tiếp tục mua sắm</a>
                                            </li>
                                            <?php
                                        }
                                        ?>


                                    </ul>
                                    <div class="total-price clearfix">
                                        <p class="title fl-left">Tổng:</p>
                                        <p class="price fl-right" id="total">
                                            <?php
                                            if (isset($_SESSION['cart']['info']['total'])) {
                                                echo currency_format($_SESSION['cart']['info']['total']);
                                            } else {
                                                echo 0;
                                            } ?>
                                        </p>
                                    </div>
                                    <dic class="action-cart clearfix">
                                        <a href="gio-hang" title="Giỏ hàng" class="view-cart fl-left">Giỏ hàng</a>
                                        <a href="thanh-toan" title="Thanh toán" class="checkout fl-right">Thanh
                                            toán</a>
                                    </dic>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>