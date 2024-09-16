<style>
    .info-exhibition .thumb img{
        width: 100% !important; 
        height: auto !important;  
    }
</style>
<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="detail-exhibition fl-right">
            <div class="section" id="info">
                <div class="section-head">
                    <h3 class="section-title">Thông tin đơn hàng</h3>
                </div>
                <ul class="list-item">
                    <li>
                        <h3 class="title">Mã đơn hàng</h3>
                        <span class="detail"><?php echo $result['order_id'] ?></span>
                    </li>
                    <li>
                        <h3 class="title">Địa chỉ nhận hàng</h3>
                        <span
                            class="detail"><?php echo $result['shipping_address'] . " / " . $result['phone_number']; ?></span>
                    </li>
                    <li>
                        <h3 class="title">Thông tin vận chuyển</h3>
                        <span class="detail"><?php echo payment_method($result['payment_method']) ?></span>
                    </li>
                    <form method="POST" action="?mod=orders&action=updateStatus&order_id=<?php echo $result['order_id'] ?>">
                        <li>
                            <h3 class="title">Tình trạng đơn hàng</h3>
                            <select name="status">
                                <option value="pending" <?php check_status("pending", $result['status']) ?>>Chờ Xử Lý</option>
                                <option value="processing" <?php check_status("processing", $result['status']) ?>>Đã Xác Nhận</option>
                                <option value="shipped" <?php check_status("shipped", $result['status']) ?>>Đang Giao Hàng</option>
                                <option value="delivered" <?php check_status("delivered", $result['status']) ?>>Đã Giao Hàng</option>
                                <option value="canceled" <?php check_status("canceled", $result['status']) ?>>Đã Hủy</option>
                            </select>
                            <input type="submit" name="sm_status" value="Cập nhật đơn hàng">
                        </li>
                    </form>
                </ul>
            </div>
            <div class="section">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm đơn hàng</h3>
                </div>
                <div class="table-responsive">
                    <table class="table info-exhibition">
                        <thead>
                            <tr>
                                <td class="thead-text">STT</td>
                                <td class="thead-text">Ảnh sản phẩm</td>
                                <td class="thead-text">Tên sản phẩm</td>
                                <td class="thead-text">Đơn giá</td>
                                <td class="thead-text">Số lượng</td>
                                <td class="thead-text">Thành tiền</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($result_items) && !empty($result_items)) {
                                $temp = 0;
                                foreach ($result_items as $v) {
                                    $temp++;
                                    ?>
                                    <tr>
                                        <td class="thead-text"><?php echo $temp ?></td>
                                        <td class="thead-text">
                                            <div class="thumb">
                                                <img src="../public/images/<?php echo $v['file_name'] ?>" alt="">
                                            </div>
                                        </td>
                                        <td class="thead-text"><?php echo $v['product_name'] ?></td>
                                        <td class="thead-text"><?php echo currency_format($v['product_price']) ?></td>
                                        <td class="thead-text"><?php echo $v['quantity'] ?></td>
                                        <td class="thead-text"><?php echo currency_format($v['price']) ?></td>
                                    </tr>
                                <?php
                                }
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="section">
                <h3 class="section-title">Giá trị đơn hàng</h3>
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <span class="total-fee">Tổng số lượng</span>
                            <span class="total">Tổng đơn hàng</span>
                        </li>
                        <li>
                            <span class="total-fee"><?php echo $result['product_quantity'] ?> sản phẩm</span>
                            <span class="total"><?php echo currency_format($result['total_amount']) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>