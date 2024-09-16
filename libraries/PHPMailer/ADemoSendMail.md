<?php
ob_start();
session_start();

function redirect_to($url)
{
    if (!empty($url))
        header("Location: $url");
    else
        return false;
}
function set_value($label_field)
{
    global $$label_field;
    if (isset($$label_field))
        echo $$label_field;

}
function form_error($label_field)
{
    global $error;
    if (isset($error[$label_field])) {
        echo "<p style='color:red; '><i>{$error[$label_field]}</i></p>";
    }
}

if (isset($_POST['btn_send'])) {
    $error = array();
    if (!empty($_POST['address'])) {
        $address = $_POST['address'];
    } else {
        $error['address'] = 'Không được để trống trường Người Nhận';
    }

    if (!empty($_POST['topic'])) {
        $topic = $_POST['topic'];
    } else {
        $error['topic'] = 'Không được để trống trường Chủ Đề';
    }

    if (!empty($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        $error['content'] = 'Không được để trống trường Nội Dung';
    }

    if (!empty($_FILES['file']['name'])) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
    } else {
        $file_tmp_name = '';
        $file_name = '';
    }

    if (empty($error)) {
        send_mail($address, "Nguyễn Xuân Khá", $topic, $content, array('file_tmp_name' => $file_tmp_name, 'file_name' => $file_name));
        // echo $file_tmp_name;
        // echo "<br>";
        // echo $file_name;
    }
}
?>

<div id="content">
    <div class="container">
        <div class="col-md-6">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="address" class="form-label">Người Nhận</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="<?php set_value('address') ?>">
                    <?php form_error('address'); ?>
                </div>

                <div class="mb-3">
                    <label for="topic" class="form-label">Chủ Đề</label>
                    <input type="text" class="form-control" id="topic" name="topic" value="<?php set_value('topic') ?>">
                    <?php form_error('topic'); ?>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Nội Dung</label>
                    <input type="text" class="form-control" id="content" name="content"
                        value="<?php set_value('content') ?>">
                    <?php form_error('content'); ?>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Tệp</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                <button type="submit" name="btn_send" class="btn btn-primary">Gửi mail</button>
            </form>
        </div>
    </div>
</div>