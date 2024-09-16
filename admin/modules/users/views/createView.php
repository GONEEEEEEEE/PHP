<?php get_header(); ?>
<div id="main-content-wp" class="info-account-page">
    <div class="wrap clearfix">
        <?php get_sidebar() ?>
        
        <div id="content" class="fl-right">
        <div class="section" id="title-page">
            <div class="clearfix">
                <h3 id="index" class="fl-left">Nhóm quản trị</h3>
                <a href="?mod=users&action=create" title="" id="add-new" class="fl-left">Thêm mới</a>
            </div>
        </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <?php
                    set_value('success');
                    ?>
                    <form method="POST" id="create_user">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Nhập họ và tên"
                            value="<?php set_value('fullname') ?>">
                        <?php form_error('fullname'); ?>
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" placeholder="Nhập tên đăng nhập 6 đến 32 kí tự"
                            value="<?php set_value('username') ?>">
                        <?php form_error('username'); ?>
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu 6 đến 32 kí tự">
                        <?php form_error('password'); ?>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Nhập email"
                            value="<?php set_value('email') ?>">
                        <?php form_error('email'); ?>
                        <button type="submit" name="btn-create" id="btn-submit">Tạo mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>