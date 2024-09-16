<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách khách hàng</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a href="#">Tất cả <span class="count">(<?php if (isset($num_rows)) {
                                echo $num_rows;
                            } else {
                                echo 0;
                            }
                            ?>)</span></a>
                            </li>
                        </ul>
                        <form method="GET" class="form-s fl-right" action="">
                            <input type="hidden" name="mod" value="customers">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s">
                            <input type="submit" value="Tìm kiếm">
                        </form>
                    </div>
                    <!-- <div class="actions">
                        <form method="GET" action="" class="form-actions">
                            <select name="actions">

                                <option value="0">Tác vụ</option>
                                <option value="1">Xóa</option>
                            </select>
                            <input type="submit" name="sm_action" value="Áp dụng">
                        </form>
                    </div> -->
                    <div class="table-responsive">
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Họ và tên</span></td>
                                    <td><span class="thead-text">Số điện thoại</span></td>
                                    <td><span class="thead-text">Email</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($result)) {
                                    $temp = 0;
                                    foreach ($result as $v) {
                                        $temp++;
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkItem" class="checkItem"></td>
                                            <td><span class="tbody-text"><?php echo $temp; ?></h3></span>
                                            <td>
                                                <div class="tb-title fl-left">
                                                    <a href="?mod=customers&action=update&customer_id=<?php echo $v['customer_id'] ?>"
                                                        title=""><?php echo $v['fullname'] ?></a>
                                                </div>
                                                <ul class="list-operation fl-right">
                                                    <li><a href="?mod=customers&action=update&customer_id=<?php echo $v['customer_id'] ?>"
                                                            title="Sửa" class="edit"><i class="fa fa-pencil"
                                                                aria-hidden="true"></i></a></li>
                                                    <!-- <li><a href="?mod=customer&action=delete&customer_id=<?php echo $v['customer_id'] ?>" title="Xóa" class="delete"><i class="fa fa-trash"
                                                                aria-hidden="true"></i></a></li> -->
                                                </ul>
                                            </td>
                                            <td><span class="tbody-text"><?php echo $v['email'] ?></span></td>
                                            <td><span class="tbody-text"><?php echo $v['phone_number'] ?></span></td>
                                            <td><span class="tbody-text"><?php echo $v['created_at'] ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "Không có dữ liệu";
                                }
                                ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <div class="section-detail clearfix">
                        <?php
                        $check = isset($query) ? "&s=" . $query : "";
                        get_pagging($num_page, $page, "?mod=customers&action=index{$check}");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>