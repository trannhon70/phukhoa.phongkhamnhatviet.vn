<?php
include '../lib/session.php';
Session::checkSession();
// clearstatcache()
?>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    Session::destroy();
}

$local = 'http://localhost/_nhatvietnew/phukhoa.nhatvietclinic.vn';
// $local = 'https://phukhoa.nhatvietclinic.vn';
?>

<?php
function getImagesFromFolder($folderPath)
{
    $images = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    // Mở thư mục
    if ($handle = opendir($folderPath)) {
        while (false !== ($file = readdir($handle))) {
            // Bỏ qua các thư mục đặc biệt
            if ($file != '.' && $file != '..') {
                $filePath = $folderPath . '/' . $file;
                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                // Kiểm tra nếu tệp là hình ảnh
                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $images[] = $filePath;
                }
            }
        }
        closedir($handle);
    }
    return $images;
}

$uploadDir = 'uploads';
$images = getImagesFromFolder($uploadDir);

?>


<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng khám Việt Nhật</title>
    <link rel="icon" href="<?php echo $local ?>/images/icons/icon_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/toastr.min.css" />
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- gắn ckeditor -->
    <script src="<?php echo $local ?>/admin/ckeditor/ckeditor.js" charset="utf-8"></script>
    <!-- datepicker styles -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<style>
    body {
        overflow: hidden;
    }

    .btn-icon {
        font-size: 25px;
        cursor: pointer;
        color: #3866ad;
    }

    .btn-icon:hover {
        color: #204a8b;
        transition: 0.5s;
    }
</style>

<body>

    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex mt-3" style="margin-left: 24px;">
                <div class="sidebar-logo">
                    <a href="<?php echo $local ?>/admin">PK Phòng khám </a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <?php if (Session::get('role') === '1') { ?>
                    <li class="sidebar-item">
                        <a href="" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                            data-bs-target="#benh-ly" aria-expanded="false" aria-controls="benh-ly">
                            <i class="fa-solid fa-viruses"></i>
                            <span>Quản lý bệnh lý</span>
                        </a>
                        <ul id="benh-ly" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li style="padding-left: 10%;" class="sidebar-item">

                                <a href="benh-create.php" class="sidebar-link"> <i class="fa-solid fa-virus"></i>Tạo bệnh
                                    lý</a>
                            </li>
                            <li style="padding-left: 10%;" class="sidebar-item">
                                <a href="benh-list.php" class="sidebar-link"> <i class="fa-solid fa-list-ol"></i>Danh sách
                                    bệnh lý</a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (Session::get('role') === '1') { ?>
                    <li class="sidebar-item">
                        <a href="" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                            data-bs-target="#tai-khoan" aria-expanded="false" aria-controls="tai-khoan">
                            <i class="fa-solid fa-users"></i>
                            <span>Quản lý tài khoản</span>
                        </a>
                        <ul id="tai-khoan" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li style="padding-left: 10%;" class="sidebar-item">

                                <a href="user-create.php" class="sidebar-link"> <i class="fa-solid fa-user-plus"></i>Tạo tài
                                    khoản</a>
                            </li>
                            <li style="padding-left: 10%;" class="sidebar-item">
                                <a href="user-list.php" class="sidebar-link"> <i class="fa-solid fa-list-ol"></i>Danh sách
                                    tài khoản</a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (Session::get('role') === '1' || Session::get('role') === '2') { ?>
                    <li class="sidebar-item">
                        <a href="" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                            data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                            <i class="fa-brands fa-artstation"></i>
                            <span>QL bài viết</span>
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li style="padding-left: 10%;" class="sidebar-item">

                                <a href="bai-viet-create.php" class="sidebar-link"> <i class="fa-solid fa-plus"></i>Tạo bài
                                    viết</a>
                            </li>
                            <li style="padding-left: 10%;" class="sidebar-item">
                                <a href="bai-viet-list.php" class="sidebar-link"> <i class="fa-solid fa-list-ol"></i>Danh
                                    sách bài viết</a>
                            </li>
                        </ul>
                    </li>


                <?php } ?>


            </ul>

        </aside>

        <div class="main ">
            <div
                style="padding: 0px 1rem; background-color: #c18b1f;height: 50px;display: flex;align-items: center;justify-content: space-between;">
                <span class="btn-button " id="toggle-btn ">
                    <i style="color: white;" class="fa-solid fa-bars btn-icon"></i>
                </span>
                <div>
                    <span style="color:white; font-size: 18px; padding-right: 10px; ">hello
                        <?php echo Session::get('full_name') ?> </span>

                    <div class="btn-group">
                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i style="color: white; font-size: 25px; margin-right: 10px; "
                                class="fa-solid fa-user-secret"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Thông tin cá nhân</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="?action=logout"><i class="lni lni-exit"></i> Đăng
                                    xuẩt</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="height: 910px; overflow: auto; display: block; min-height: 500px; max-height: 1000px; "
                class="p-3">