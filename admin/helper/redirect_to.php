<?php
function redirect_to($url)
{
    if (!empty($url))
        header("Location: $url");
    else
        return false;
}
?>