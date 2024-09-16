<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm trang</h3>
                </div>
            </div>
            <?php set_value('success') ?>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST">
                        <label for="title">Tiêu đề</label>
                        <input type="text" name="title" id="title" value="<?php set_value('title') ?>">
                        <?php form_error('title'); ?>
                        <label for="title">Slug ( Friendly_url )</label>
                        <input type="text" name="slug" id="slug" value="<?php set_value('slug') ?>">
                        <?php form_error('slug'); ?>
                        <label for="desc">Nội dung</label>
                        <textarea name="desc" id="desc" class="ckeditor"><?php set_value('desc') ?></textarea>
                        <?php form_error('desc'); ?>
                        <label for="status">Trạng thái</label>
                        <select name="status">
                            <option value="">Chọn trạng thái</option>
                            <option value="draft">Bản nháp</option>
                            <option value="published">Công khai</option>
                            <option value="pending">Chờ duyệt</option>
                            <!-- <option value="archived">Đã lưu trữ</option> -->
                        </select>
                        <?php form_error('status'); ?>
                        <button type="submit" name="btn-create" id="btn-submit">Thêm trang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>