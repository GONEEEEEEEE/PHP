<?php

function construct()
{

}

// Giao diện hiển thị
function indexAction()
{
   $slug = $_GET['slug'];
   $result = db_fetch_row("SELECT * FROM `tbl_pages` WHERE `page_slug` = '{$slug}'");
   $data['content'] = $result['page_content'];
   $data['page_title'] = $result['page_title'];
   load_view("index", $data);
}
