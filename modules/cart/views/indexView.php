<?php get_header(); ?>
<div id="main-content-wp" class="cart-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="trang-chu.html" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Giỏ hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <?php
        if (isset($_SESSION['cart']['buy']) && !empty($_SESSION['cart']['buy'])) {
            ?>
            <div class="section" id="info-cart-wp">
                <div class="section-detail table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>STT</td>
                                <td>Ảnh sản phẩm</td>
                                <td>Tên sản phẩm</td>
                                <td>Giá sản phẩm</td>
                                <td>Số lượng</td>
                                <td colspan="2">Thành tiền</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $temp = 0;
                            foreach ($_SESSION['cart']['buy'] as $v) {
                                $temp++;
                                ?>
                                <tr>
                                    <td><?php echo $temp; ?></td>
                                    <td>
                                        <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="thumb">
                                            <img src="public/images/<?php echo $v['file_name'] ?>"
                                                alt="<?php echo $v['file_name'] ?>">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="name-product"><?php echo $v['product_name'] ?></a>
                                    </td>
                                    <td><?php echo currency_format($v['product_price']) ?></td>
                                    <td>
                                        <button class="minus" data-product-id="<?php echo $v['product_id'] ?>"><i class="fa fa-minus"></i></button>
                                        <input type="text" name="num-order" data-product-id="<?php echo $v['product_id'] ?>"
                                            id="num-order-<?php echo $v['product_id'] ?>" class="num-order"
                                            value="<?php echo $v['qty'] ?>" min="1" max="<?php echo $v['stock_quantity'] ?>">
                                        <button class="add" data-product-id="<?php echo $v['product_id'] ?>"><i class="fa fa-plus"></i></button>
                                    </td>
                                    <td class="sub-total-<?php echo $v['product_id'] ?>">
                                        <?php echo currency_format($v['sub_total']); ?>
                                    </td>
                                    <td>
                                        <a href="xoa-san-pham/<?php echo $v['product_slug'] ?>-<?php echo $v['product_id'] ?>.html"
                                            title="" class="del-product"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }


                            ?>


                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div class="clearfix">
                                        <p id="total-price" class="fl-right">Tổng giá: <span class="total"> <?php
                                        if (isset($_SESSION['cart']['info']['total'])) {
                                            echo currency_format($_SESSION['cart']['info']['total']);
                                        } else {
                                            echo 0;
                                        } ?></span></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div class="clearfix">
                                        <div class="fl-right">
                                            <a href="thanh-toan" title="" id="checkout-cart">Thanh toán</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="section" id="action-cart-wp">
                <div class="section-detail">
                    <a href="trang-chu.html" title="" id="buy-more">Mua tiếp</a><br />
                    <a href="xoa-gio-hang" title="" id="delete-cart">Xóa giỏ hàng</a>
                </div>
            </div>
        <?php
        }else{
        ?>
        <p><a href="trang-chu.html">Hiện tại không có sản phẩm nào trong giỏ hàng ấn vào đây để bắt đầu mua sắm</a></p>
        <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        let minValue = parseInt($(".num-order").attr("min"), 10);
        let maxValue = parseInt($(".num-order").attr("max"), 10);

        $(".minus").click(function () {
            var product_id = $(this).attr("data-product-id");
            console.log(product_id);
            let currentValue = parseInt($(`#num-order-${product_id}`).val(), 10);  // Cập nhật giá trị hiện tại mỗi lần nhấn nút
            if (currentValue > minValue) {
                currentValue -= 1;
                $(`#num-order-${product_id}`).val(currentValue).change(); // Thay đổi giá trị và kích hoạt sự kiện change
            }
        });

        $(".add").click(function () {
            var product_id = $(this).attr("data-product-id");
            let currentValue = parseInt($("#num-order-" + product_id).val(), 10);  // Cập nhật giá trị hiện tại mỗi lần nhấn nút
            if (currentValue < maxValue) {
                currentValue += 1;
                $("#num-order-" + product_id).val(currentValue).change(); // Thay đổi giá trị và kích hoạt sự kiện change
            }
        });

        $(".num-order").change(function () {
            var qty = $(this).val();
            var product_id = $(this).attr("data-product-id");
            let data = { qty: qty, product_id: product_id };
            console.log(data);
            $.ajax({
                type: "POST",
                url: "?mod=cart&action=updateAjax",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    $(`.sub-total-${product_id}`).text(response.sub_total);
                    $(".total").text(response.total);
                }
            });
        });
    });
</script>
<?php get_footer(); ?>