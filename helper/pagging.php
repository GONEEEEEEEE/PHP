<?php
function get_pagging($num_page, $page, $base_url = "")
{
    // global $num_page, $page;
    $str_pagging = "<ul class='pagging fl-right' id='list-paging'>";

    if ($page > 1) {
        $page_prev = $page - 1;
        $str_pagging .= "<li><a href=\"{$base_url}&page={$page_prev}\">Trước</a></li>";
    }else{
        $str_pagging .= "<li><a href='' class=\"disabled\">Trước</a></li>";
    }

    for ($i = 1; $i <= $num_page; $i++) {
        $active ="";
        if($page == $i){
            $active = "class=\"active\"";
        }
        $str_pagging .= "<li {$active}><a href=\"{$base_url}&page={$i}\">{$i}</a></li>";
        // unset($active);
    }

    if ($page < $num_page) {
        $page_next = $page + 1;
        $str_pagging .= "<li><a href=\"{$base_url}&page={$page_next}\">Sau</a></li>";
    }else{
        $str_pagging .= "<li><a href='' class=\"disabled\">Sau</a></li>";
    }

    $str_pagging .= "</ul>";
    echo $str_pagging;
}


?>