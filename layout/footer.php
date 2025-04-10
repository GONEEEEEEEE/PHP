<div id="footer-wp">
    <div id="foot-body">
        <div class="wp-inner clearfix">
            <div class="block" id="info-company">
                <h3 class="title">ISMART</h3>
                <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng, chính sách ưu đãi
                    cực lớn cho khách hàng có thẻ thành viên.</p>
                <div id="payment">
                    <div class="thumb">
                        <img src="public/images/img-foot.png" alt="">
                    </div>
                </div>
            </div>
            <div class="block menu-ft" id="info-shop">
                <h3 class="title">Thông tin cửa hàng</h3>
                <ul class="list-item">
                    <li>
                        <p>08 - NGUYỄN VĂN BÉ - QUY NHƠN - BÌNH ĐỊNH</p>
                    </li>
                    <li>
                        <p>0766.635.399 - 0989.989.989</p>
                    </li>
                    <li>
                        <p>anhchanghotran85@gmail.com</p>
                    </li>
                </ul>
            </div>
            <div class="block menu-ft policy" id="info-shop">
                <h3 class="title">Chính sách mua hàng</h3>
                <ul class="list-item">
                    <li>
                        <a href="" title="">Quy định - chính sách</a>
                    </li>
                    <li>
                        <a href="" title="">Chính sách bảo hành - đổi trả</a>
                    </li>
                    <li>
                        <a href="" title="">Chính sách hội viện</a>
                    </li>
                    <li>
                        <a href="" title="">Giao hàng - lắp đặt</a>
                    </li>
                </ul>
            </div>
            <div class="block" id="newfeed">
                <h3 class="title">Bảng tin</h3>
                <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                <div id="form-reg">
                    <form method="POST" action="">
                        <input type="email" name="email" id="email" placeholder="Nhập email tại đây">
                        <button type="submit" id="sm-reg">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="foot-bot">
        <div class="wp-inner">
            <p id="copyright">© Bản quyền thuộc về <a style="color: white"
                    href="https://www.facebook.com/hoang.huy.56863/" target="_blank">Trần Huy Hoàng</a></p>
        </div>
    </div>
</div>
</div>

<?php

function has_child_respon($data, $id)
{
    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            return true;
        }
    }

    return false;
}

function array_categories_respon($data, $parent_id = 0)
{
    $result = "<ul class='' id='main-menu-respon'>";
    foreach ($data as $v) {
        if ($v['parent_id'] == $parent_id) {
            $result .= "<li>";
            $category_name = $v['category_name'];
            $slug = $v['category_slug'];
            $result .= "<a href='san-pham/$slug.html' title=''>$category_name</a>";
            // Nếu có phần tử con, gọi đệ quy và gộp kết quả vào mảng kết quả chính
            if (has_child_respon($data, $v['category_id'])) {
                $result .= "<ul class='sub-menu'>";
                $result .= array_categories_respon($data, $v['category_id']);
                $result .= "</ul>";
            }
            $result .= "</li>";
        }
    }
    $result .= "</ul>";
    return $result;
}
?>
<div id="menu-respon">
    <a href="?page=home" title="" class="logo">VSHOP</a>
    <div id="menu-respon-wp">
        <?php
        $result_menu = db_fetch_array("SELECT * FROM `tbl_product_categories` WHERE `category_status` = 'public'");
        if ($result_menu) {
            echo array_categories_respon($result_menu);
        } else {
            echo "kkk";
        }
        ?>
    </div>
</div>
<div id="btn-top"><img src="public/images/icon-to-top.png" alt="" /></div>
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script>
    $(document).ready(function () {

        $("#sm-reg").click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Thông báo",
                text: "Chức năng đang được hoàn thiện",
                icon: "warning",
                cancelButtonColor: "#d33",
            })
        });
    // $(window).on("load", function () {
    //     // Khi trang đã tải xong, ẩn màn hình loading với hiệu ứng fadeOut
    //     $("#loading-screen").fadeOut(1000, function () {
    //         // Khi fadeOut hoàn tất, có thể thực hiện thêm các hành động nếu cần
    //         // Ví dụ: Hiển thị nội dung chính
    //         $("#site").fadeIn();
    //     });
    // });

    $(".add-cart").click(function (e) {
        let product_id = $(this).data("product-id");
        e.preventDefault();
        let num_order = 1;
        if ($("#num-order").length) {
            num_order = $("#num-order").val();
        }
        let data = { product_id: product_id, num_order: num_order }
        console.log(data)
        $.ajax({
            type: "POST",
            url: "?mod=cart&action=addCartAjax",
            data: data,
            dataType: "json",
            success: function (response) {
                $("span#num").text(response.count);
                $("span#num_product").text(response.count);
                $("#total").text(response.total);
                $('#list-cart-js').empty();
                $.each(response.buy, function (index, item) {
                    var listItem = `
                        <li class="clearfix">
                            <a href="san-pham/${item.product_slug}-${item.product_id}.html" title="" class="thumb fl-left">
                                <img src="public/images/${item.file_name}" alt="${item.file_name}">
                            </a>
                            <div class="info fl-right">
                                <a href="san-pham/${item.product_slug}-${item.product_id}.html" title="" class="product-name" style="min-height: 50px;">${item.product_name}</a>
                                 <p class="price">${item.product_price_fomart}</p>
                                <p class="qty">Số lượng: <span>${item.qty}</span></p>
                            </div>
                        </li>
                    `;

                    // Thêm listItem vào ul
                    // console.log(listItem);empty()

                    $('#list-cart-js').append(listItem);
                });
            }
        });


        // console.log(product_id);
        Swal.fire({
            title: "Thành Công",
            text: "Thêm sản phẩm vào giỏ hàng thành công",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tiếp tục mua sắm",
            confirmButtonText: "Xem giỏ hàng",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "gio-hang";
            }
        });
    });
    })
</script>
</body>

</html>