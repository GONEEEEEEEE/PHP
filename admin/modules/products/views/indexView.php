<?php get_header(); ?>
<style>
    .tb-title {
        width: 100%;
    }

    .list-operation {
        width: 20%;
    }
</style>
<div id="main-content-wp" class="list-post-page">
    <div class="wrap clearfix">
        <?php get_sidebar();
        ?>
        <div id="content" class="fl-right">

            <div class="section" id="detail-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Nhóm sản phẩm</h3>
                    <a href="?mod=products&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li><a href="?mod=products&action=index&status=all<?php check_query($query); ?>">Tất
                                    cả <span class="count">(<?php echo $num_products ?>)</span></a>
                                |</li>
                                <li><a href="?mod=products&action=index&status=featured<?php check_query($query); ?>">Nổi bật <span class="count">(<?php echo $num_featured; ?>)</span></a> |</li>
                            <li><a href="?mod=products&action=index&status=public<?php check_query($query); ?>">Hoạt
                                    động <span class="count">(<?php echo $num_products_active ?>)</span></a> |</li>
                            <li><a href="?mod=products&action=index&status=inactive<?php check_query($query); ?>">Chờ
                                    duyệt <span class="count">(<?php echo $num_products_inactive ?>)</span></a> |</li>
                            <li><a href="?mod=products&action=index&status=out_of_stock<?php check_query($query); ?>">Hết
                                    hàng <span class="count">(<?php echo $num_out_of_stock ?>)</span></a> |</li>
                            <li><a href="?mod=products&action=index&status=trash<?php check_query($query); ?>">Thùng
                                    rác <span class="count">(<?php echo $num_trash ?>)</span></a></li>
                        </ul>
                        <form action="?mod=products&action=index" method="GET" class="form-s fl-right"
                            enctype="multipart/form-data">
                            <input type="hidden" name="mod" value="products">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s" placeholder="Nhập để tìm kiếm">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>

                    <form method="POST" action="?mod=products&action=updateStatus" class="form-actions">
                        <div class="actions">
                            <select name="actions">
                                <option value="">Tác vụ</option>
                                <option value="yes_featured">Nổi bật</option>
                                <option value="no_featured">Bỏ nổi bật</option>
                                <option value="public">Hoạt động</option>
                                <option value="inactive">Chờ duyệt</option>
                                <option value="out_of_stock">Hết hàng</option>
                                <option value="trash">Thùng rác</option>
                            </select>
                            <input type="submit" name="btn_action" value="Áp dụng">
                        </div>
                        <?php
                        if (isset($status) && $status == "trash") {
                            ?>
                            <p style="background-color: lightcoral; color: white; padding: 5px;"><strong>Lưu ý</strong>: Tất
                                cả
                                danh mục nằm ở <strong>THÙNG RÁC</strong> sẽ bị xóa hoàn toàn sau 7 ngày. Vui
                                lòng chuyển tất cả các sản phẩm còn sử dụng sang danh mục khác</p>
                            <?php
                        }
                        ?>
                        <div class="table-responsive">
                            <table class="table list-table-wp">
                                <thead>
                                    <tr>
                                        <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                        <td><span class="thead-text">STT</span></td>
                                        <td><span class="thead-text">Tên sản phẩm</span></td>
                                        <td><span class="thead-text">Loại danh mục</span></td>
                                        <td><span class="thead-text">Giá sản phẩm</span></td>
                                        <td><span class="thead-text">Số lượng tồn</span></td>
                                        <td><span class="thead-text">Nổi bật</span></td>
                                        <td><span class="thead-text">Trạng thái</span></td>
                                        <td><span class="thead-text">Người tạo</span></td>
                                        <td><span class="thead-text">Thời gian tạo</span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($data['result'])) {
                                        $temp = 0;
                                        $temp = ($page - 1) * $num_per_page;
                                        foreach ($data['result'] as $key => $v) {
                                            $temp++;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['product_id'] ?>]"
                                                        class="checkItem"></td>
                                                <td><span class="tbody-text"><?php echo $temp ?></td></span>
                                                <td class="clearfix">
                                                    <div class="tb-title fl-left">
                                                        <a
                                                            href="?mod=products&action=update&product_id=<?php echo $v['product_id'] ?>"><?php echo get_name_title($v['product_name']); ?></a>
                                                        <ul class="list-operation fl-right">
                                                            <li><a href="?mod=products&action=update&product_id=<?php echo $v['product_id'] ?>"
                                                                    title="Sửa" class="edit"><i class="fa fa-pencil"
                                                                        aria-hidden="true"></i></a></li>
                                                            <li><a href="?mod=products&action=delete&product_id=<?php echo $v['product_id'] ?>"
                                                                    title="Xóa" class="delete"><i class="fa fa-trash"
                                                                        aria-hidden="true"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td><span
                                                        class="tbody-text"><?php echo get_product_category($v['category_id']); ?></span>
                                                </td>
                                                <td><span
                                                        class="tbody-text"><?php echo number_format($v['product_price'], 0, ",", "."); ?>đ</span>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['stock_quantity']; ?></span></td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['is_featured']; ?>"><?php echo get_featured($v['is_featured']); ?></span>
                                                </td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['product_status']; ?>"><?php echo get_status($v['product_status']); ?></span>
                                                </td>
                                                <td><span class="tbody-text"><?php get_user($v['user_id']); ?></span></td>
                                                <td><span class="tbody-text"><?php echo $v['created_at']; ?></span></td>
                                            </tr>
                                            <?php
                                            // echo $v['username'];
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">Không có dữ liệu</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>


            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php
                    $check = check_query_products($query);
                    get_pagging($num_page, $page, "?mod=products&action=index&status={$status}{$check}");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>