<?php
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

function is_username($username)
{
    $parttern = "/^[A-Za-z0-9_\.]{6,32}$/";
    if (preg_match($parttern, $username))
        return true;
}
function is_password($password)
{
    $parttern = "/^([\w_\.!@#$%^&*()]+){6,32}$/";
    if (preg_match($parttern, $password))
        return true;

}

function is_email($email)
{
    $partten = "/^[A-Za-z0-9_\.]{6,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
    if (preg_match($partten, $email))
        return true;
}
function is_tel($tel)
{
    $parttern = "/^(03|09|08|07|05|)+([0-9]{8})$/";
    if (preg_match($parttern, $tel))
        return true;

}
?>