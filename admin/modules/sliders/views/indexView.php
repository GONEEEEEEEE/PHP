<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page list-slider">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách slider</h3>
                    <a href="?mod=sliders&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li><a href="?mod=sliders&action=index&status=all<?php check_query($query); ?>">Tất cả <span
                                        class="count">(<?php echo $num_slider ?>)</span></a> |</li>
                            <li><a href="?mod=sliders&action=index&status=public<?php check_query($query); ?>">Đã đăng
                                    <span class="count">(<?php echo $num_active ?>)</span></a> |</li>
                            <li><a href="?mod=sliders&action=index&status=pending<?php check_query($query); ?>">Chờ xét
                                    duyệt<span class="count">(<?php echo $num_pending ?>)</span></a></li>
                            <li><a href="?mod=sliders&action=index&status=trash<?php check_query($query); ?>">Thùng
                                    rác<span class="count">(<?php echo $num_trash ?>)</span></a></li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="hidden" name="mod" value="sliders">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <form method="POST" action="?mod=sliders&action=updateStatus" class="form-actions">
                        <div class="actions">
                            <select name="actions">
                                <option value="">Tác vụ</option>
                                <option value="public">Công khai</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="trash">Bỏ vào thủng rác</option>
                            </select>
                            <input type="submit" name="sm_action" value="Áp dụng">
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
                        <?php set_value('success'); ?>
                        <div class="table-responsive">
                            <table class="table list-table-wp">
                                <thead>
                                    <tr>
                                        <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                        <td><span class="thead-text">STT</span></td>
                                        <td><span class="thead-text">Hình ảnh</span></td>
                                        <td><span class="thead-text">Link</span></td>
                                        <td><span class="thead-text">Thứ tự</span></td>
                                        <td><span class="thead-text">Trạng thái</span></td>
                                        <td><span class="thead-text">Người tạo</span></td>
                                        <td><span class="thead-text">Thời gian</span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($data['result'])) {
                                        $temp = 0;
                                        foreach ($data['result'] as $result => $v) {
                                            $temp++;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['slider_id']; ?>]"
                                                        class="checkItem"></td>
                                                <td><span class="tbody-text"><?php echo $temp; ?></h3></span>
                                                <td>
                                                    <div class="tbody-thumb">
                                                        <img src="../public/images/<?php echo get_images($v['image_id']); ?>"
                                                            alt="">
                                                    </div>
                                                </td>
                                                <td class="clearfix">
                                                    <div class="tb-title fl-left">
                                                        <a href="<?php echo $v['slider_url']; ?>" title=""
                                                            target="_blank"><?php echo $v['slider_url']; ?></a>
                                                    </div>
                                                    <ul class="list-operation fl-right">
                                                        <li><a href="?mod=sliders&action=update&slider_id=<?php echo $v['slider_id']; ?>"
                                                                title="Sửa" class="edit"><i class="fa fa-pencil"
                                                                    aria-hidden="true"></i></a></li>
                                                        <li><a href="?mod=sliders&action=delete&slider_id=<?php echo $v['slider_id']; ?>"
                                                                title="Xóa" class="delete"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['display_order']; ?></span></td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['slider_status']; ?>"><?php get_status_name($v['slider_status']); ?></span>
                                                </td>
                                                <td><span class="tbody-text"><?php echo get_user($v['user_id']); ?></span></td>
                                                <td><span class="tbody-text"><?php echo $v['created_at']; ?></span></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "kk";
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
                    <p id="desc" class="fl-left">Chọn vào checkbox để lựa chọn tất cả</p>
                    <!-- <ul id="list-paging" class="fl-right">
                        <li>
                            <a href="" title=""><</a>
                        </li>
                        <li>
                            <a href="" title="">1</a>
                        </li>
                        <li>
                            <a href="" title="">2</a>
                        </li>
                        <li>
                            <a href="" title="">3</a>
                        </li>
                        <li>
                            <a href="" title="">></a>
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>