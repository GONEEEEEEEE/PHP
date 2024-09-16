<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách đơn hàng</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class=""><a href="?mod=orders&action=index&status=all<?php check_query($query); ?>">Tất
                                    cả <span class="count">(<?php echo $num_all ?>)</span></a> |</li>
                            <li class=""><a
                                    href="?mod=orders&action=index&status=pending<?php check_query($query); ?>">Chờ Xử
                                    Lý <span class="count">(<?php echo $num_pending ?>)</span></a> |</li>
                            <li class=""><a
                                    href="?mod=orders&action=index&status=processing<?php check_query($query); ?>">Đã
                                    Xác Nhận <span class="count">(<?php echo $num_processing ?>)</span></a> |</li>
                            <li class=""><a
                                    href="?mod=orders&action=index&status=shipped<?php check_query($query); ?>">Đang
                                    Giao Hàng <span class="count">(<?php echo $num_shipped ?>)</span> |</a></li>
                            <li class=""><a
                                    href="?mod=orders&action=index&status=delivered<?php check_query($query); ?>">Đã
                                    Giao Hàng <span class="count">(<?php echo $num_delivered ?>)</span> |</a></li>
                            <li class=""><a
                                    href="?mod=orders&action=index&status=canceled<?php check_query($query); ?>">Đã
                                    Hủy <span class="count">(<?php echo $num_canceled ?>)</span> |</a></li>
                            <li class="" style="color: #337ab7">Doanh Thu <span
                                    class="count">(<?php echo currency_format($total) ?>)</span></li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="hidden" name="mod" value="orders">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <form method="POST" action="?mod=orders&action=updateStatus" class="form-actions">
                        <div class="actions">

                            <select name="actions">
                                <option value="0">Tác vụ</option>
                                <option value="pending">Chờ Xử Lý</option>
                                <option value="processing">Đã Xác Nhận</option>
                                <option value="shipped">Đang Giao Hàng</option>
                                <option value="delivered">Đã Giao Hàng</option>
                                <option value="canceled">Đã Hủy</option>
                            </select>
                            <input type="submit" name="sm_action" value="Áp dụng">

                        </div>
                        <div class="table-responsive">
                            <?php
                            if (isset($result) && !empty($result)) {
                                $temp = 0;
                                ?>
                                <table class="table list-table-wp">

                                    <thead>
                                        <tr>
                                            <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                            <td><span class="thead-text">STT</span></td>
                                            <td><span class="thead-text">Mã đơn hàng</span></td>
                                            <td><span class="thead-text">Họ và tên</span></td>
                                            <td><span class="thead-text">Số sản phẩm</span></td>
                                            <td><span class="thead-text">Tổng giá</span></td>
                                            <td><span class="thead-text">Phương Thức</span></td>
                                            <td><span class="thead-text">Trạng thái</span></td>
                                            <td><span class="thead-text">Thời gian</span></td>
                                            <td><span class="thead-text">Chi tiết</span></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($result as $v) {
                                            $temp++;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['order_id'] ?>]"
                                                        class="checkItem"></td>
                                                <td><span class="tbody-text"><?php echo $temp; ?></h3></span>
                                                <td><span class="tbody-text"><?php echo $v['order_id'] ?></h3></span>
                                                <td>
                                                    <div class="tb-title fl-left">
                                                        <a href="?mod=customers&action=update&customer_id=<?php echo $v['customer_id'] ?>"
                                                            title=""><?php echo $v['fullname'] ?></a>
                                                    </div>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['product_quantity'] ?></span></td>
                                                <td><span
                                                        class="tbody-text"><?php echo currency_format($v['total_amount']) ?></span>
                                                </td>
                                                <td><span
                                                        class="tbody-text"><?php echo payment_method($v['payment_method']) ?></span>
                                                </td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['status'] ?>"><?php echo status($v['status']) ?></span>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['created_at'] ?></span></td>
                                                <td><a href="?mod=orders&action=detail&order_id=<?php echo $v['order_id'] ?>" title="" class="tbody-text">Chi tiết</a></td>
                                            </tr>
                                            <?php
                                        }

                                        ?>

                                    </tbody>

                                </table>
                                <?php
                            } else {
                                echo "Không có dữ liệu";
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php
                    $check = check_query($query);
                    get_pagging($num_page, $page, "?mod=orders&action=index&status={$status}{$check}");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>