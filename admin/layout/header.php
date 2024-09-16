<!DOCTYPE html>
<html>

<head>
    <title>Quản lý ISMART</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />
    <link href="public/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="public/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="public/reset.css" rel="stylesheet" type="text/css" />
    <link href="public/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="public/style.css" rel="stylesheet" type="text/css" />
    <link href="public/responsive.css" rel="stylesheet" type="text/css" />
    <link href="public/pagging.css" rel="stylesheet" type="text/css" />


    <script src="public/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="public/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="public/js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="public/js/main.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">


</head>

<body>
    <style>
        .activeUser {
            background: rgb(46, 204, 113);
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .inactiveUser {
            background: #f1c40f !important;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .bannedUser {
            background: #e74c3c !important;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .cke_notifications_area {
            display: none;
        }

        /* status pages */
        .draft,
        .shipped {
            background: #f1c40f;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .published,
        .public,
        .yes,
        .delivered {
            background: rgb(46, 204, 113);
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .pending,
        .inactive {
            background: #3498db;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .archived,
        .unpublic,
        .trash,
        .canceled {
            background: #34495e;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .out_of_stock,
        .processing {
            background: #e74c3c;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .wp-inner{
            height: 53px;
        }
    </style>
    <div id="toast"></div>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div class="wp-inner clearfix">
                    <a href="?" title="" id="logo" class="fl-left">Admin</a>
                    <ul id="main-menu" class="fl-left">
                        <li>
                            <a href="?mod=pages&action=index" title="">Trang</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="?mod=pages&action=create" title="">Thêm mới</a>
                                </li>
                                <li>
                                    <a href="?mod=pages&action=index" title="">Danh sách trang</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="?mod=posts&action=index" title="">Bài viết</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="?mod=posts&action=create" title="">Thêm mới</a>
                                </li>
                                <li>
                                    <a href="?mod=posts&action=index" title="">Danh sách bài viết</a>
                                </li>
                                <li>
                                    <a href="?mod=posts&controller=categories&action=index" title="">Danh mục bài
                                        viết</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="?mod=products&action=index" title="">Sản phẩm</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="?mod=products&action=create" title="">Thêm mới</a>
                                </li>
                                <li>
                                    <a href="?mod=products&action=index" title="">Danh sách sản phẩm</a>
                                </li>
                                <li>
                                    <a href="?mod=products&controller=category&action=index" title="">Danh mục sản
                                        phẩm</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="" title="">Bán hàng</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="?mod=orders&action=index" title="">Danh sách đơn hàng</a>
                                </li>
                                <li>
                                    <a href="?mod=customers&action=index" title="">Danh sách khách hàng</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div id="dropdown-user" class="dropdown dropdown-extended fl-right">
                        <button class="dropdown-toggle clearfix" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">
                            <div id="thumb-circle" class="fl-left">
                                <img src="public/images/img-admin.png">
                            </div>
                            <h3 id="account" class="fl-right"><?php if (!empty(user_login()))
                                echo user_login(); ?></h3>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="?mod=users&action=update" title="Thông tin cá nhân">Thông tin tài khoản</a>
                            </li>
                            <li><a href="?mod=users&action=logout" title="Thoát">Thoát</a></li>
                        </ul>
                    </div>
                </div>
            </div>