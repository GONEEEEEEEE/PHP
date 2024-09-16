<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gửi mail</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #ecf0f1; padding: 20px;">
    <?php
    if (isset($_SESSION['cart']) && isset($_SESSION['customer'])) {
        ?>
        <div style="background-color: #ffffff; padding: 20px; border-radius: 10px; max-width: 100%; margin: auto;">
            <p>Xin chào <strong><?php echo $_SESSION['customer']['fullname']; ?></strong></p>
            <p>Xin cảm ơn bạn đã tin tưởng ISMART, dưới đây là thông tin về đơn hàng của bạn:</p>
            <hr style="border-top: 1px solid #ccc;">
            <div style="text-align: center;">
                <h2>Thông tin về đơn hàng</h2>
                <p>Mã đơn hàng: <strong>#ISMART<?php echo $_SESSION['customer']['order_id']; ?></strong></p>
                <p>Ngày đặt hàng: <strong><?php echo $_SESSION['customer']['date']; ?></strong></p>
            </div>
            <hr style="border-top: 1px solid #ccc;">
            <div>
                <p><strong>Họ và tên:</strong> <?php echo $_SESSION['customer']['fullname']; ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo $_SESSION['customer']['phone']; ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['customer']['email']; ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?php
                if ($_SESSION['customer']['pay'] == "COD") {
                    echo "Thanh toán tại nhà";
                } else {
                    echo "Thanh toán online";
                }
                ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo $_SESSION['customer']['address']; ?></p>
            </div>
            <h3 style="text-align: center;">Thông tin sản phẩm</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">STT</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Tên Sản Phẩm</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Số Lượng</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Đơn Giá</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $temp = 0;
                    foreach ($_SESSION['cart']['buy'] as $v) {
                        $temp++;
                        ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;"><?php echo $temp; ?></td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">
                                <!-- <img src="public/images/<?php echo $v['file_name']; ?>"
                                    style="max-width: 100px; vertical-align: middle; margin-right: 10px;" alt=""> -->
                                <span><?php echo $v['product_name']; ?></span>
                            </td>
                            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">x<?php echo $v['qty']; ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">
                                <?php echo $v['product_price_fomart']; ?></td>
                            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">
                                <?php echo currency_format($v['sub_total']); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">Tạm tính:</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">
                            <?php echo currency_format($_SESSION['cart']['info']['total']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">Phí vận chuyển:</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">Miễn phí</td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding: 10px; border: 1px solid #dee2e6; text-align: right; font-weight: bold;">Tổng
                            tiền:</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: right; font-weight: bold;">
                            <?php echo currency_format($_SESSION['cart']['info']['total']); ?></td>
                    </tr>
                </tfoot>
            </table>

            <p><strong>Thời gian giao hàng:</strong> Dự kiến khoảng 3 - 4 ngày.</p>
            <p><strong>Đơn vị giao hàng:</strong> ViettelPost</p>
            <p><i>Lưu ý khi nhận hàng:</i></p>
            <p><i>- Chỉ nhận hàng khi trạng thái đơn hàng là "Đang vận chuyển". Kiểm tra đúng mã đơn hàng, mã vận đơn trước
                    khi nhận hàng.</i></p>
            <p><i>- Được đồng kiểm (không bao gồm mở niêm phong sản phẩm hoặc cắm điện, dùng thử).</i></p>
            <p><i>- Đối với đơn hàng đã thanh toán trước, nhân viên giao nhận có thể yêu cầu người nhận hàng cung cấp mã xác
                    thực gửi bởi ISMART, hoặc giấy tờ tùy thân để xác nhận thông tin.</i></p>

            <p>Một lần nữa xin cảm ơn quý khách rất nhiều.</p>
            <hr style="border-top: 1px solid #ccc;">
            <div style="padding: 20px 0;">
                <p>Nếu có vấn đề gì vui lòng liên hệ theo các thông tin bên dưới.</p>
                <p><strong>Số điện thoại:</strong> 0333198402</p>
                <p><strong>Email:</strong> nguyenxuantoan2004@gmail.com</p>
                <p><strong>Địa chỉ:</strong> Quảng Bình, Việt Nam</p>
            </div>
        </div>
        <?php
    }
    ?>
</body>

</html>