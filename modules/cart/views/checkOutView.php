<?php get_header(); ?>
<div id="main-content-wp" class="checkout-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="trang-chu.html" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Thanh toán</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <?php
        if (isset($_SESSION['cart'])) {
            ?>
            <form method="POST" action="" name="form-checkout" id="form-checkout">
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">

                        <div class="form-row clearfix">
                            <div class="form-col fl-left fullname">
                                <label for="fullname">Họ tên (<span style="color: red">*</span>)</label>
                                <input type="text" name="fullname" id="fullname" placeholder="VD: Nguyễn Văn A">
                            </div>
                            <div class="form-col fl-right email">
                                <label for="email">Email (<span style="color: red">*</span>)</label>
                                <input type="email" name="email" id="email" placeholder="VD: nguyenvana@gmail.com">
                            </div>
                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left phone-order">
                                <label for="phone">Số điện thoại (<span style="color: red">*</span>)</label>
                                <input type="tel" name="phone" id="phone" placeholder="VD: 0987654321">
                            </div>
                            <div class="form-col fl-right tinh">
                                <label for="tinh">Tỉnh/Thành Phố (<span style="color: red">*</span>)</label>
                                <select class="css_select" id="tinh" name="tinh" title="Chọn Tỉnh Thành">
                                    <option value="0" disabled selected>Tỉnh Thành</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row clearfix">
                            <div class="form-col fl-left quan">
                                <label for="quan">Quận/Huyện (<span style="color: red">*</span>)</label>
                                <select class="css_select" id="quan" name="quan" title="Chọn Quận Huyện">
                                    <option value="0" disabled selected>Quận Huyện</option>
                                </select>
                            </div>
                            <div class="form-col fl-right phuong">
                                <label for="phuong">Phường/Xã (<span style="color: red">*</span>)</label>
                                <select class="css_select" id="phuong" name="phuong" title="Chọn Phường Xã">
                                    <option value="0" disabled selected>Phường Xã</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col address" style="padding-bottom: 15px;">
                                <label for="address">Địa chỉ (<span style="color: red">*</span>)</label>
                                <input type="text" name="address" id="address" placeholder="VD: K108/H18/2/4 Đoàn Phứ Tứ">
                            </div>
                            <div class="form-col note">
                                <label for="notes">Ghi chú</label>
                                <textarea name="note" id="notes"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    <div class="section-detail">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($_SESSION['cart']['buy'] as $v) {
                                    ?>
                                    <tr class="cart-item">
                                        <td class="product-name"><?php echo $v['product_name']; ?><strong
                                                class="product-quantity">x <?php echo $v['qty'] ?></strong>
                                        </td>
                                        <td class="product-total"><?php echo currency_format($v['sub_total']) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong
                                            class="total-price"><?php echo currency_format($_SESSION['cart']['info']['total']) ?></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="Online-Payment" name="payment-method" value="Online Payment">
                                    <label for="Online-Payment">Thanh toán qua ngân hàng</label>
                                </li>
                                <li>
                                    <input type="radio" id="COD" name="payment-method" value="COD" checked>
                                    <label for="COD">Thanh toán tại nhà</label>
                                </li>
                            </ul>
                        </div>
                        <div class="place-order-wp clearfix">
                            <input type="submit" id="order-now" value="Đặt hàng">
                        </div>
                    </div>
                </div>
            </form>
            <?php
        } else {
            ?>
            <p><a href="trang-chu.html">Hiện tại không có sản phẩm nào trong giỏ hàng ấn vào đây để bắt đầu mua sắm</a></p>
            <?php
        }
        ?>

    </div>
</div>
<script>
    $(document).ready(function () {
        $("#form-checkout").submit(function (e) {
            e.preventDefault();
            var $orderNowBtn = $("#order-now");
            $orderNowBtn.val("Đang xử lý ...").prop("disabled", true);

            var fullName = $("#fullname").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var tinh_name = $("#tinh option:selected").text();
            var quan_name = $("#quan option:selected").text();
            var phuong_name = $("#phuong option:selected").text();
            var tinh = $("#tinh").val();
            var quan = $("#quan").val();
            var phuong = $("#phuong").val();
            var address = $("#address").val();
            var notes = $("#notes").val();
            var pay = $("input[name='payment-method']:checked").val();
            let data = { fullName: fullName, email: email, phone: phone, tinh: tinh, tinh_name: tinh_name, quan: quan, quan_name: quan_name, phuong: phuong, phuong_name: phuong_name, address: address, notes: notes, pay: pay };
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "?mod=cart&action=order",
                data: data,
                dataType: "json",
                success: function (response) {
                    if ($("span.error-mess")) {
                        $("span.error-mess").empty();
                    }

                    console.log(response);

                    // Kiểm tra nếu response.error tồn tại
                    if (response.error) {
                        if (response.error.fullName) {
                            $(".fullname").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.fullName}</i></span>`)
                        }

                        if (response.error.email) {
                            $(".email").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.email}</i></span>`)
                        }

                        if (response.error.phone) {
                            $(".phone-order").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.phone}</i></span>`)
                        }

                        if (response.error.tinh) {
                            $(".tinh").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.tinh}</i></span>`)
                        }

                        if (response.error.quan) {
                            $(".quan").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.quan}</i></span>`)
                        }

                        if (response.error.phuong) {
                            $(".phuong").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.phuong}</i></span>`)
                        }

                        if (response.error.address) {
                            $(".address").append(`<span class="error-mess" style="margin-top: 10px;color: red; display: block;"><i>${response.error.address}</i></span>`)
                        }

                        $orderNowBtn.val("Đặt hàng").prop("disabled", false);
                    };

                    if (response.success == 1) {
                        Swal.fire({
                            title: "Đặt Đơn Hàng Thành Công",
                            text: "Thông tin về đơn hàng đã được gửi đến email của bạn, vui lòng theo dõi đơn hàng của bạn ở đó nhé!",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Quay trở lại trang chủ",
                            allowOutsideClick: false, // Cho phép đóng thông báo khi nhấp ra ngoài
                            allowEscapeKey: false // Không cho phép đóng thông báo khi nhấn phím Escape
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "trang-chu.html";
                            }
                        });
                    };
                }
            });
        });

        //Lấy tỉnh thành
        $.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function (data_tinh) {
            if (data_tinh.error == 0) {
                $.each(data_tinh.data, function (key_tinh, val_tinh) {
                    $("#tinh").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
                });
                $("#tinh").change(function (e) {
                    var idtinh = $(this).val();
                    //Lấy quận huyện
                    $.getJSON('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm', function (data_quan) {
                        if (data_quan.error == 0) {
                            $("#quan").html('<option value="0" disabled selected>Quận Huyện</option>');
                            $("#phuong").html('<option value="0" disabled selected>Phường Xã</option>');
                            $.each(data_quan.data, function (key_quan, val_quan) {
                                $("#quan").append('<option value="' + val_quan.id + '">' + val_quan.full_name + '</option>');
                            });
                            //Lấy phường xã  
                            $("#quan").change(function (e) {
                                var idquan = $(this).val();
                                $.getJSON('https://esgoo.net/api-tinhthanh/3/' + idquan + '.htm', function (data_phuong) {
                                    if (data_phuong.error == 0) {
                                        $("#phuong").html('<option value="0">Phường Xã</option>');
                                        $.each(data_phuong.data, function (key_phuong, val_phuong) {
                                            $("#phuong").append('<option value="' + val_phuong.id + '">' + val_phuong.full_name + '</option>');
                                        });
                                    }
                                });
                            });

                        }
                    });
                });

            }
        });
    });	    
</script>
<?php get_footer(); ?>