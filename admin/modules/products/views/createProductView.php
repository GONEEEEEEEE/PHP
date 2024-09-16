<?php get_header(); ?>

<style>
    #uploadFile img {
        display: inline;
        border: 2px solid black;
    }

    #uploadFile img.active {
        border: 2px solid red;
    }

    #featured {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    #featured input {
        margin-right: 5px;
    }

    #featured label {
        margin-right: 15px;
        padding: 0px !important;
    }

    input[type="number"] {
        margin-bottom: 20px;
    }
</style>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Thêm mới sản phẩm</h3>
                </div>
            </div>

            <div class="section" id="detail-page">
                <div id="wp-post">
                    <?php set_value('success') ?>
                    <div class="section-detail">

                        <form method="POST" id="form-create" enctype="multipart/form-data">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" id="title" value="<?php set_value('title'); ?>">
                            <?php form_error('title'); ?>

                            <label for="title">Slug ( Friendly_url )</label>
                            <input type="text" name="slug" id="slug" value="<?php set_value('slug'); ?>">
                            <?php form_error('slug'); ?>

                            <label for="descc">Mô tả ngắn</label>
                            <textarea name="desc" id="descc" class="ckeditor"><?php set_value('desc'); ?></textarea>
                            <?php form_error('desc'); ?>

                            <label for="content">Nội dung</label>
                            <textarea name="content" id="content"
                                class="ckeditor"><?php set_value('content'); ?></textarea>
                            <?php form_error('content'); ?>

                            <label for="price">Giá</label>
                            <input type="number" name="price" id="price" min="0" value="<?php set_value('price'); ?>">
                            <?php form_error('price'); ?>

                            <label for="stock">Số lượng trong kho</label>
                            <input type="number" name="stock" id="stock" min="0" value="<?php set_value('stock'); ?>">
                            <?php form_error('stock'); ?>

                            <label for="featured">Sản phẩm nổi bật</label>
                            <div id="featured">
                                <input type="radio" name="featured" checked id="no" value="no">
                                <label for="no">Bình thường</label>
                                <input type="radio" name="featured" id="yes" value="yes">
                                <label for="yes">Nổi bật</label>
                            </div>


                            <label>Hình ảnh</label>
                            <div id="uploadFile">
                                <input type="file" name="file[]" id="upload-thumb" multiple><br>
                                <!-- <input type="submit" name="btn-upload-thumb" value="Upload" id="btn-upload-thumb"> -->
                                <img id="imgPreview1" name="0" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                                <img id="imgPreview2" name="1" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                                <img id="imgPreview3" name="2" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                                <img id="imgPreview4" name="3" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                                <img id="imgPreview5" name="4" src="public/images/img-thumb.png" alt="Hình ảnh thumb">
                                <input type="text" name="pin" id="pin" value="0" hidden>
                            </div>
                            <?php form_error('img'); ?>

                            <label>Trạng thái</label>
                            <select name="status">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="public">Công khai</option>
                                <option value="inactive">Chờ duyệt</option>
                                <option value="out_of_stock">Hết hàng</option>
                                <option value="trash">Thùng rác</option>
                            </select>
                            <?php form_error('status');?>

                            <label>Danh mục</label>
                            <select name="category_id">
                                <option value="">-- Chọn danh mục --</option>
                                <?php
                                echo show_menu_categories($data['parent']);
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
        $('#imgPreview1').addClass("active");
        $("#upload-thumb").change(function () {
            var input = $(this)[0];
            var files = input.files;

            // Kiểm tra nếu số lượng file vượt quá 5
            if (files.length > 5) {
                alert('Chỉ được upload tối đa 5 ảnh.');
                $(this).val(''); // Reset lại input file
                return; // Dừng việc xử lý tiếp theo
            }
            // Xóa các ảnh preview trước đó
            $('#uploadFile img').attr('src', 'public/images/img-thumb.png').hide();

            // Hiển thị ảnh preview cho từng file
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();

                // Khi file được đọc thành công
                reader.onload = (function (index) {
                    return function (e) {
                        $('#imgPreview' + (index + 1)).attr('src', e.target.result).show();
                    };
                })(i);

                reader.readAsDataURL(files[i]); // Đọc file
            }


        });

        $("#uploadFile img").click(function () {
            $("#uploadFile img").removeClass("active");
            $(this).addClass("active");
            var pin = 0;
            pin = $(this).attr('name');
            $("#pin").val(pin);
            console.log($("#pin").val());
        });



    });
</script>