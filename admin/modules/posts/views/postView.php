<?php get_header(); ?>
<div id="main-content-wp" class="list-post-page">
    <div class="wrap clearfix">
        <?php get_sidebar();
        // show_array($data);
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách bài viết</h3>
                    <a href="?mod=posts&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a
                                    href="?mod=posts&action=index&status=all<?php check_query($query); ?>">Tất cả <span
                                        class="count">(<?php echo $num_post; ?>)</span></a>
                                |</li>
                            <li class="draftPages"><a
                                    href="?mod=posts&action=index&status=draft<?php check_query($query); ?>">Bản nháp
                                    <span class="count">(<?php echo $num_post_draft ?>)</span></a> |</li>
                            <li class="publishedPages"><a
                                    href="?mod=posts&action=index&status=published<?php check_query($query); ?>">Công
                                    khai <span class="count">(<?php echo $num_post_published ?>)</span></a> |</li>
                            <li class="pendingPages"><a
                                    href="?mod=posts&action=index&status=pending<?php check_query($query); ?>">Chờ duyệt
                                    <span class="count">(<?php echo $num_post_pending ?>)</span></a> |</li>
                            <li class="archivedPages"><a
                                    href="?mod=posts&action=index&status=archived<?php check_query($query); ?>">Thùng
                                    rác
                                    <span class="count">(<?php echo $num_post_archived ?>)</span></a></li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="hidden" name="mod" value="posts">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s" placeholder="Nhập để tìm kiếm">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <form method="POST" action="?mod=posts&action=updateStatus" class="form-actions">
                        <div class="actions">
                            <select name="actions">
                                <option value="">Tác vụ</option>
                                <option value="draft">Bản nháp</option>
                                <option value="published">Công khai</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="archived">Chuyển vào thùng rác</option>
                            </select>
                            <input type="submit" name="btn_update" value="Áp dụng">
                        </div>
                        <?php
                        if (isset($status) && $status == "archived") {
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
                                        <td><span class="thead-text">Tiêu đề</span></td>
                                        <td><span class="thead-text">Danh mục</span></td>
                                        <td><span class="thead-text">Trạng thái</span></td>
                                        <td><span class="thead-text">Người tạo</span></td>
                                        <td><span class="thead-text">Ngày tạo</span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($data['result'])) {
                                        $temp = 0;
                                        foreach ($data['result'] as $key => $v) {
                                            $temp++;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['post_id'] ?>]"
                                                        class="checkItem"></td>
                                                <td><span class="tbody-text"><?php echo $temp ?></td>
                                                <td class="clearfix">
                                                    <div class="tb-title fl-left">
                                                        <a
                                                            href="?mod=posts&action=update&post_id=<?php echo $v['post_id'] ?>"><?php echo get_name_title($v['post_title']); ?></a>
                                                        <ul class="list-operation fl-right">
                                                            <li><a href="?mod=posts&action=update&post_id=<?php echo $v['post_id'] ?>"
                                                                    title="Sửa" class="edit"><i class="fa fa-pencil"
                                                                        aria-hidden="true"></i></a></li>
                                                            <li><a href="?mod=posts&action=delete&post_id=<?php echo $v['post_id'] ?>"
                                                                    title="Xóa" class="delete"><i class="fa fa-trash"
                                                                        aria-hidden="true"></i></a></li>
                                                        </ul>
                                                    </div>


                                                </td>
                                                <td><span class="tbody-text"><?php get_category($v['category_id']); ?></span>
                                                </td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['post_status'] ?>"><?php get_status_name($v['post_status']); ?></span>
                                                </td>
                                                <td><span class="tbody-text"><?php get_user($v['user_id']); ?></span></td>

                                                <td><span class="tbody-text"><?php echo $v['created_at']; ?></span></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">Không có dữ liệu</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                            </table>
                        </div>
                    </form>
                </div>
            </div>


            <div class="section" id="paging-wp">
                <div class="section-detail clearfix">
                    <?php
                    $check = check_query_post($query);
                    get_pagging($num_page, $page, "?mod=posts&action=index&status={$status}{$check}");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>