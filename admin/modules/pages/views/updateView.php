<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar();
        //  show_array($data);
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Sửa trang</h3>
                </div>
            </div>
            <?php set_value('success') ?>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST">
                        <label for="title">Tiêu đề</label>
                        <input type="text" name="title" id="title" value="<?php set_value('title');
                        set_value_update('page_title');
                        ?>">
                        <?php form_error('title'); ?>

                        <label for="title">Slug ( Friendly_url )</label>
                        <input type="text" name="slug" id="slug" value="<?php set_value('slug');
                        set_value_update('page_slug'); ?>">
                        <?php form_error('slug'); ?>

                        <label for="desc">Nội dung</label>
                        <textarea name="desc" id="desc" class="ckeditor"><?php set_value('desc');
                        set_value_update('page_content'); ?></textarea>
                        <?php form_error('desc'); ?>

                        <label for="status">Trạng thái</label>
                        <select name="status">
                            <option value="">Chọn trạng thái</option>
                            <option value="draft" <?php set_option('page_status', 'draft'); ?>>Bản nháp</option>
                            <option value="published" <?php set_option('page_status', 'published'); ?>>Công khai
                            </option>
                            <option value="pending" <?php set_option('page_status', 'pending'); ?>>Chờ duyệt</option>
                            <option value="archived" <?php set_option('page_status', 'archived'); ?>>Thùng rác</option>
                        </select>

                        <?php form_error('status'); ?>
                        <button type="submit" name="btn-update" id="btn-submit">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>