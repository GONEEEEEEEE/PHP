<?php get_header(); ?>
<div id="main-content-wp" class="info-account-page">
    <div class="section" id="title-page">
        <div class="clearfix">
            <a href="?mod=users&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
            <h3 id="index" class="fl-left">Cập nhật tài khoản</h3>
        </div>
    </div>
    <div class="wrap clearfix">
        <?php get_sidebar('user') ?>
        <div id="content" class="fl-right">
            <div class="section" id="detail-page">
                <div class="section-detail" id="detail-user">
                    <form method="POST">
                        <label for="display-name">Tên hiển thị</label>
                        <input type="text" name="fullname" id="display-name" value="<?php set_value('fullname') ?>">
                        <?php form_error('fullname'); ?>
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" placeholder="<?php set_value('username') ?>"
                            readonly="readonly">

                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="<?php set_value('email') ?> "
                            readonly="readonly">
                        <label for="tel">Số điện thoại</label>
                        <input type="tel" name="tel" id="tel" value="<?php set_value('tel') ?>">
                        <?php form_error('tel'); ?>
                        <label for="address">Địa chỉ</label>
                        <textarea name="address" id="address"><?php set_value('address') ?></textarea>
                        <?php form_error('address'); ?>
                        <button type="submit" name="btn-update" id="btn-submit">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>