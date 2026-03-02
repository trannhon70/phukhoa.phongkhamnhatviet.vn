<?php
ob_start();
session_start();
include 'lib/session.php';
Session::init();

include_once 'classes/bai_viet.php';

spl_autoload_register(function ($className) {
    include_once "classes/" . $className . ".php";
});

$bai_viet = new post();

?>


<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");

// $local = 'http://localhost/_nhatvietnew/phukhoa.nhatvietclinic.vn';
$local = 'https://phukhoa.nhatvietclinic.vn'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Phòng khám Nhật Việt chuyên điều trị bệnh nam khoa, bệnh xã hội, da liễu, hậu môn - trực tràng uy tính tại thành phố Hồ Chí Minh">
    <title>Phòng khám Nhật Việt</title>
    <link rel="icon" href="<?php echo $local ?>/images/icons/icon_logo.png" type="image/x-icon">
    <link rel="preload" as="image" href="<?php echo $local ?>/images/banner/mobile_banner.webp" fetchpriority="high"
        media="(max-width: 768px)">
    <link rel="preload" href="<?php echo $local ?>/css/index.min.css" as="style"
        onload='this.onload=null,this.rel="stylesheet"'>


    <!-- CSS PC -->
    <link rel="preload" href="<?php echo $local ?>/css/header.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?php echo $local ?>/css/footer.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">


    <noscript>
        <link rel="stylesheet" href="<?php echo $local ?>/css/index.min.css">

        <link rel="stylesheet" href="<?php echo $local ?>/css/header.min.css">
        <link rel="stylesheet" href="<?php echo $local ?>/css/footer.min.css">
    </noscript>

    <script>
        // Chỉ tải Google Analytics khi người dùng cuộn xuống
        document.addEventListener('scroll', function loadGA() {
            console.log('Người dùng cuộn xuống - Tải Google Analytics');

            // Tạo thẻ script
            var g = document.createElement('script'),
                s = document.scripts[0];
            g.src = 'https://www.googletagmanager.com/gtag/js?id=G-3424R2LJB5';
            g.async = true;
            s.parentNode.insertBefore(g, s);

            // Cấu hình gtag
            g.onload = function() {
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', 'G-3424R2LJB5');
            };

            // Xóa sự kiện lắng nghe để không tải lại
            document.removeEventListener('scroll', loadGA);
        });
    </script>