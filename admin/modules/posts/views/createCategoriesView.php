<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm mới danh mục</h3>
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
                            echo show_menu_categories(get_postt());
                            ?>
                        </select>
                        <button type="submit" name="btn-create" id="btn-submit">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>