<?php get_header(); ?>
<div id="main-content-wp" class="list-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar();

        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách danh mục</h3>
                    <a href="?mod=products&controller=category&action=create" title="" id="add-new" class="fl-left">Thêm
                        mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class=""><a href="?mod=products&controller=category&action=index&status=all">Tất Cả
                                    <span class="count">(<?php echo $num; ?>)</span></a>
                                |</li>
                            <li class=""><a href="?mod=products&controller=category&action=index&status=public">Hoạt
                                    động
                                    <span class="count">(<?php echo $num_public; ?>)</span></a>
                                |</li>
                            <li class=""><a href="?mod=products&controller=category&action=index&status=pending">Chờ
                                    duyệt
                                    <span class="count">(<?php echo $num_pending; ?>)</span></a>
                                |</li>
                            <li class=""><a href="?mod=products&controller=category&action=index&status=unpublic">Thùng
                                    rác <span class="count">(<?php echo $num_unpublic; ?>)</span></a></li>

                        </ul>
                    </div>
                    <form method="POST" action="?mod=products&controller=category&action=updateStatus" class="form-actions">
                        <div class="actions">
                            <select name="actions">
                                <option value="">Tác vụ</option>
                                <option value="public">Hoạt động</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="unpublic">Thùng rác</option>
                            </select>
                            <input type="submit" name="btn_action" value="Áp dụng">
                        </div>
                        <?php
                        if (isset($status) && $status == "unpublic") {
                            ?>
                            <p style="background-color: lightcoral; color: white; padding: 5px;"><strong>Lưu ý</strong>: Tất
                                cả
                                danh mục nằm ở <strong>THÙNG RÁC</strong> sẽ bị xóa hoàn toàn sau 7 ngày. Vui
                                lòng chuyển tất cả các sản phẩm còn sử dụng sang danh mục khác</p>
                            <?php
                        }
                        ?>
                        <div class="table-responsive">
                            <?php set_value('success') ?>
                            <table class="table list-table-wp">
                                <thead>
                                    <tr>
                                        <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                        <td><span class="thead-text">STT</span></td>
                                        <td><span class="thead-text">Tên danh mục</span></td>
                                        <td><span class="thead-text">Người tạo</span></td>
                                        <td><span class="thead-text">Trạng thái</span></td>
                                        <td><span class="thead-text">Thời gian</span></td>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (isset($result)) {
                                        $temp = ($page - 1) * $num_per_page;
                                        foreach ($result as $v) {
                                            $temp++;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['category_id'] ?>]"
                                                        class="checkItem"></td>
                                                <td><span class='tbody-text'><?php echo $temp; ?></span></td>
                                                <td class='clearfix'>
                                                    <div class='tb-title fl-left'>
                                                        <a href="?mod=products&controller=category&action=update&id=<?php echo $v['category_id'] ?>"
                                                            title=''><?php echo $v['category_name'] ?></a>
                                                    </div>
                                                    <ul class='list-operation fl-right'>

                                                        <li><a href="?mod=products&controller=category&action=update&id=<?php echo $v['category_id'] ?>"
                                                                title='Sửa' class='edit'><i class='fa fa-pencil'
                                                                    aria-hidden='true'></i></a></li>
                                                        <?php if ($v['category_status'] != "unpublic") {
                                                            ?>
                                                            <li><a href="?mod=products&controller=category&action=delete&id=<?php echo $v['category_id'] ?>"
                                                                    title='Xóa' class='delete'><i class='fa fa-trash'
                                                                        aria-hidden='true'></i></a></li>
                                                            <?php
                                                        }
                                                        ?>


                                                    </ul>
                                                </td>
                                                <td><span class='tbody-text'><?php echo get_user($v['user_id']); ?></span></td>
                                                <td><span
                                                        class='tbody-text <?php echo $v['category_status']; ?>'><?php echo category_status_name($v['category_status']) ?></span>
                                                </td>
                                                <td><span class='tbody-text'><?php echo $v['created_at'] ?></span></td>
                                            </tr>
                                            <?php
                                        }
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
                    get_pagging($num_page, $page, "?mod=products&controller=category&action=index&status={$status}");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>