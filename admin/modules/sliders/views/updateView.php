<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page slider-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Cập nhật Slider</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <?php set_value('success'); 
                    // show_array($data);
                    ?>
                    <form method="POST" enctype="multipart/form-data">
                        <label for="title">Tên slider</label>
                        <input type="text" name="title" id="title" value="<?php set_value('title') ?>">
                        <?php form_error('title'); ?>
                        <label for="link">Link</label>
                        <input type="text" name="link" id="link" value="<?php set_value('link') ?>">
                        <?php form_error('link'); ?>
                        <label for="descc">Mô tả</label>
                        <textarea name="desc" id="descc" class="ckeditor"><?php set_value('desc') ?></textarea>
                        <?php form_error('desc'); ?>
                        <label for="num_order">Thứ tự</label>
                        <input type="number" min="0" name="num_order" id="num_order" value="<?php set_value('num_order') ?>">
                        <?php form_error('num_order'); ?>
                        <label>Hình ảnh</label>
                        <div id="uploadFile">
                            <input type="file" name="file" id="upload-thumb">
                            <img id="imgPreview" src="../public/images/<?php echo $image_name; ?>" alt="Hình ảnh thumb">
                        </div>
                        <?php form_error('slider_img'); ?>

                        <label>Trạng thái</label>
                        <select name="status">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="public" <?php echo set_status($status, "public") ?>>Công khai</option>
                            <option value="pending" <?php echo set_status($status, "pending") ?>>Chờ duyệt</option>
                            <option value="trash" <?php echo set_status($status, "trash") ?>>Chuyển vào thùng rác</option>
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