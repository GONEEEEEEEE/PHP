<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar();
        // show_array($data);
        
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Cập nhật danh mục</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <?php set_value('success') ?>
                    <form method="POST">
                    <label for="title">Tên danh mục</label>
                        <input type="text" name="title" id="title" value="<?php set_value('title') ?>">
                        <?php form_error('title'); ?>
                        <label for="title">Slug ( Friendly_url )</label>
                        <input type="text" name="slug" id="slug" value="<?php set_value('slug') ?>">
                        <?php form_error('slug'); ?>
                        <label for="desc">Mô tả ngắn</label>
                        <textarea name="desc" id="desc" class="ckeditor"><?php set_value('desc') ?></textarea>
                        <?php form_error('desc'); ?>
                        <label>Danh mục cha <span style="color: #a1a1a1">(Không chọn sẽ mặc định là danh mục
                                gốc)</span></label>

                        <select name="parent-Cat">
                            <option value="">-- Chọn danh mục --</option>
                            <?php
                            echo show_menu_categories($data["parent"]);
                            ?>
                        </select>
                        <label>Trạng thái</label>
                        <select name="category_status">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="public" <?php get_category_status('category_status', 'public'); ?>>Công khai</option>
                            <option value="pending" <?php get_category_status('category_status', 'pending'); ?>>Chờ duyệt</option>
                            <option value="unpublic" <?php get_category_status('category_status', 'unpublic'); ?>>Thùng rác</option>
                        </select>
                        <?php form_error('category_status'); ?>
                        <button type="submit" name="btn-update" id="btn-submit">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>