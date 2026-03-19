<header id="header__mobile" class="header__mobile">
    <div class="header_mobile_container">
        <div class="header_mobile_container-body">
            <a href="<?php echo $local ?>">
                <img loading="lazy" width="40px" height="auto" src="<?php echo $local ?>/images/icons/icon_dot.webp"
                    alt="...">
            </a>
            <div style="color: white; text-align: center; font-size: 18px; ">
                <div style="">PHÒNG KHÁM</div>
                <span style="font-weight: 700;">Chuyên Khoa Nhật Việt</span>
            </div>

            <?php include "layout/sider_bar.php" ?>
        </div>
    </div>
    <div style="position: relative;" class="header__mobile-baner">
        <img fetchpriority=high src="<?php echo $local ?>/images/banner/banner_mobile.webp" alt="..." srcset="">
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 15vh; display: flex;">
            <a style="display: block ; width: 50%; height: 15vh;" href="tel:+0901869945"></a>
            <a style="display: block ; width: 50%; height: 15vh;" href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;"></a>
        </div>
    </div>
</header>