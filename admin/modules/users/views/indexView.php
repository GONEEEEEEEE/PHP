<?php get_header(); ?>
<div id="main-content-wp" class="list-post-page">
    <div class="wrap clearfix">
        
        <?php get_sidebar();
        // show_array($data);
        ?>
        <div id="content" class="fl-right">
        <div class="section" id="title-page">
            <div class="clearfix">
                <h3 id="index" class="fl-left">Nhóm quản trị</h3>
                <a href="?mod=users&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
            </div>
        </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class=""><a
                                    href="?mod=users&controller=team&action=index&status=all<?php check_query($query); ?>">Tất
                                    cả <span class="count">(<?php echo $num_users ?>)</span></a>
                                |</li>
                            <li class=""><a
                                    href="?mod=users&controller=team&action=index&status=active<?php check_query($query); ?>">Hoạt
                                    động <span class="count">(<?php echo $num_user_active ?>)</span></a> |</li>
                            <li class=""><a
                                    href="?mod=users&controller=team&action=index&status=inactive<?php check_query($query); ?>">Khóa
                                    tạm thời <span class="count">(<?php echo $num_user_inactive ?>)</span></a></li>
                            <li class=""><a
                                    href="?mod=users&controller=team&action=index&status=banned<?php check_query($query); ?>">Tài
                                    khoản bị khóa <span class="count">(<?php echo $num_user_banned ?>)</span></a></li>
                        </ul>
                        <form action="?mod=users&controller=team&action=index" method="GET" class="form-s fl-right"
                            enctype="multipart/form-data">
                            <input type="hidden" name="mod" value="users">
                            <input type="hidden" name="controller" value="team">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s" placeholder="Nhập để tìm kiếm">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <form method="POST" action="?mod=users&controller=team&action=status" class="form-actions">
                        <div class="actions">
                            <select name="actions">
                                <option value="">Tác vụ</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Khóa tạm thời</option>
                                <option value="banned">Khóa tài khoản</option>
                            </select>
                            <input type="submit" name="btn_action" value="Áp dụng">
                        </div>
                        <div class="table-responsive">
                            <table class="table list-table-wp">
                                <thead>
                                    <tr>
                                        <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                        <td><span class="thead-text">STT</span></td>
                                        <td><span class="thead-text">Họ và tên</span></td>
                                        <td><span class="thead-text">Tên tài khoản</span></td>
                                        <td><span class="thead-text">Trạng thái</span></td>
                                        <td><span class="thead-text">Số điện thoại</span></td>
                                        <td><span class="thead-text">Email</span></td>
                                        <td><span class="thead-text">Thời gian tạo</span></td>
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
                                                <td><input type="checkbox" name="checkItem[<?php echo $v['user_id'] ?>]"
                                                        class="checkItem"></td>
                                                <td><span class="tbody-text"><?php echo $temp ?></td></span>
                                                <td class="clearfix">
                                                    <div class="tb-title fl-left"><?php echo $v['fullname']; ?>
                                                    </div>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['username']; ?></span></td>
                                                <td><span
                                                        class="tbody-text <?php echo $v['status'] . 'User'; ?>"><?php get_status_user($v['status']) ; ?></span>
                                                </td>
                                                <td><span class="tbody-text"><?php echo $v['tel']; ?></span></td>
                                                <td><span class="tbody-text"><?php echo $v['email']; ?></span></td>
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
                    $check = check_query_pages($query);
                        get_pagging($num_page, $page, "?mod=users&controller=team&action=index&status=$status{$check}");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>