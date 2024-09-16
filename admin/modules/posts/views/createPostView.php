<?php
// show_array($category);
?>
<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm mới bài viết</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div id="wp-post">
                    <?php set_value('success') ?>
                    <div class="section-detail">
                        <form method="POST" enctype="multipart/form-data">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="post_title" id="title" value="<?php set_value('post_title'); ?>">
                            <?php form_error('post_title'); ?>

                            <label for="title">Slug ( Friendly_url )</label>
                            <input type="text" name="post_slug" id="slug" value="<?php set_value('post_slug'); ?>">
                            <?php form_error('post_slug'); ?>

                            <label for="descc">Mô tả ngắn</label>
                            <textarea name="post_desc" id="descc"
                                class="ckeditor"><?php set_value('post_desc'); ?></textarea>
                            <?php form_error('post_desc'); ?>

                            <label for="content">Nội dung</label>
                            <textarea name="post_content" id="content"
                                class="ckeditor"><?php set_value('post_content'); ?></textarea>
                            <?php form_error('post_content'); ?>

                            <label>Hình ảnh</label>
                            <div id="uploadFile">
                                <input type="file" name="file" id="upload-thumb">
                                <!-- <input type="submit" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb"> -->
                                <img id="imgPreview" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                            </div>
                            <?php form_error('post_img'); ?>

                            <label>Trạng thái</label>
                            <select name="post_status">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="draft" <?php echo set_status($data['post_status'], "draft"); ?>>Bản nháp
                                </option>
                                <option value="published" <?php echo set_status($post_status, "published"); ?>>Công
                                    khai</option>
                                <option value="pending" <?php echo set_status($post_status, "pending"); ?>>Chờ duyệt
                                </option>
                            </select>
                            <?php form_error('post_status'); ?>

                            <label>Danh mục</label>
                            <select name="category_id">
                                <option value="">-- Chọn danh mục --</option>
                                <?php
                                echo show_menu_categories($data['category']);
                                ?>
                            </select>
                            <?php form_error('category_id'); ?>

                            <button type="submit" name="btn-create" id="btn-submit">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    $(document).ready(function () {
        $("#upload-thumb").change(function () {
            var input = $(this)[0];
            var file = input.files[0];

            if (file) {
                var reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = function (e) {
                    $('#imgPreview').attr('src', e.target.result);
                    // console.log(e.target.result);
                }
            }
        });
    });
</script>
