<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page list-slider">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách media</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="filter-wp clearfix">
                        <ul class="post-status fl-left">
                            <li class="all"><a href="?mod=medias&action=index">Tất cả <span class="count">(<?php echo $num_rows; ?>)</span></a></li>
                        </ul>
                        <form method="GET" class="form-s fl-right">
                            <input type="hidden" name="mod" value="medias">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="s" id="s">
                            <input type="submit" name="sm_s" value="Tìm kiếm">
                        </form>
                    </div>
                    <div class="actions">
                        <form method="GET" action="" class="form-actions">
                            <select name="actions">
                                <option value="0">Tác vụ</option>
                                <option value="1">Xóa</option>
                            </select>
                            <input type="submit" name="sm_action" value="Áp dụng">
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Hình ảnh</span></td>
                                    <td><span class="thead-text">Tên file</span></td>
                                    <td><span class="thead-text">Người tạo</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($data['result'])) {
                                    // $temp = 0;
                                    $temp = ($page - 1) * $num_per_page;
                                    foreach ($data['result'] as $result => $v) {
                                        $temp++;
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkItem[<?php echo $v["image_id"]; ?>]"
                                                    class="checkItem"></td>
                                            <td><span class="tbody-text"><?php echo $temp; ?></span>
                                            <td>
                                                <div class="tbody-thumb">
                                                    <img src="../public/images/<?php echo $v["file_name"] ?>" alt="">
                                                </div>
                                            </td>
                                            <td class="clearfix">
                                                <div class="tb-title fl-left">
                                                    <a href="?mod=medias&action=detail&image_id=<?php echo $v["image_id"]; ?>"
                                                        title=""><?php echo $v["file_name"] ?></a>
                                                </div>
                                            </td>
                                            <td><span class="tbody-text"><?php get_user($v["user_id"]); ?></span></td>
                                            <td><span class="tbody-text"><?php echo $v["created_at"]; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section" id="paging-wp">
                    <p id="desc" class="fl-left">Chọn vào checkbox để lựa chọn tất cả</p>
                    <div class="section-detail clearfix">
                        <?php
                        $check = check_query_page($query);
                        get_pagging($num_page, $page, "?mod=medias&action=index{$check}");
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>