<?php get_header(); ?>
<div id="main-content-wp" class="add-cat-page">
    <div class="wrap clearfix">
        <?php get_sidebar();
        //  show_array($data);
        ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Cập nhật thông tin khách hàng</h3>
                </div>
            </div>
            <?php set_value('success') ?>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <form method="POST">
                        <label for="fullname">Tên Khách Hàng</label>
                        <input type="text" name="fullname" id="fullname" value="<?php set_value('fullname');
                        set_value_update('fullname');
                        ?>">
                        <?php form_error('fullname'); ?>

                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php set_value('v');
                        set_value_update('email'); ?>">
                        <?php form_error('email'); ?>

                        <label for="phone_number">Số điện thoại</label>
                        <input type="text" name="phone_number" id="phone_number" value="<?php set_value('phone_number');
                        set_value_update('phone_number'); ?>">
                        <?php form_error('phone_number'); ?>

                        <label for="address">Địa Chỉ</label>
                        <input type="text" name="address" id="address" value="<?php set_value('address');
                        set_value_update('address'); ?>">
                        <?php form_error('address'); ?>

                        <label for="created_at">Ngày đặt</label>
                        <input type="text" name="created_at" id="created_at" disabled value="<?php set_value_update('created_at'); ?>">
               

                        <button type="submit" name="btn-update" id="btn-submit">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>