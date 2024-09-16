<div class="section" id="slider-wp">
    <div class="section-detail">
        <?php
        $row = db_num_rows("SELECT * FROM `tbl_sliders` WHERE `slider_status` = 'public'");
        if ($row > 0) {
            $result = db_fetch_array("SELECT i.`file_name`, s.`slider_title`
                FROM
                    `tbl_sliders` s
                INNER JOIN `tbl_images` i ON
                    s.`image_id` = i.`image_id`
                WHERE
                    s.`slider_status` = 'public'
                ORDER BY s.`display_order` ASC");
            foreach ($result as $v) {
                ?>
                <div class="item">
                    <img src="public/images/<?php echo $v['file_name'] ?>" alt="<?php echo $v['slider_title'] ?>">
                </div>
                <?php
            }
        }
        ?>
           <!-- <div class="item">
            <img src="public/images/slider-01.png" alt="">
        </div>
        <div class="item">
            <img src="public/images/slider-02.png" alt="">
        </div>
        <div class="item">
            <img src="public/images/slider-03.png" alt="">
        </div> -->
    </div>
</div>
<div class="section" id="support-wp">
    <div class="section-detail">
        <ul class="list-item clearfix">
            <li>
                <div class="thumb">
                    <img src="public/images/icon-1.png">
                </div>
                <h3 class="title">Miễn phí vận chuyển</h3>
                <p class="desc">Tới tận tay khách hàng</p>
            </li>
            <li>
                <div class="thumb">
                    <img src="public/images/icon-2.png">
                </div>
                <h3 class="title">Tư vấn 24/7</h3>
                <p class="desc">1900.9999</p>
            </li>
            <li>
                <div class="thumb">
                    <img src="public/images/icon-3.png">
                </div>
                <h3 class="title">Tiết kiệm hơn</h3>
                <p class="desc">Với nhiều ưu đãi cực lớn</p>
            </li>
            <li>
                <div class="thumb">
                    <img src="public/images/icon-4.png">
                </div>
                <h3 class="title">Thanh toán nhanh</h3>
                <p class="desc">Hỗ trợ nhiều hình thức</p>
            </li>
            <li>
                <div class="thumb">
                    <img src="public/images/icon-5.png">
                </div>
                <h3 class="title">Đặt hàng online</h3>
                <p class="desc">Thao tác đơn giản</p>
            </li>
        </ul>
    </div>
</div>