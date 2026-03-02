<?php

include 'inc/header.php';
include '../classes/khoa.php';
include '../classes/benh.php';
include '../classes/bai_viet.php';
if (Session::get('role') === '1' || Session::get('role') === '2') {
    $khoa = new khoa();
    $benh = new Benh();
    $bai_viet = new post();
?>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $message = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $message = $bai_viet->insert_post($_POST, $_FILES);
        if ($message['status'] == 'success') {
            $_SESSION['message'] = $message;
        }
    }

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>


    <?php
    $list_khoa = $khoa->getAllKhoa();

    $list_benh = $benh->getByIdKhoa();
    ?>

    <style>
        .list-img.activeImg {
            border: 4px solid red;
            transform: scale(1.1);
        }

        .list-img {
            cursor: pointer;
            transition: transform 0.3s;
            border: 1px solid black;
            width: 125px;
            height: 125px;
        }
    </style>


    <form action="bai-viet-create.php" method="post" enctype="multipart/form-data">

        <fieldset>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Bài viết</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo bài viết</li>
                </ol>
            </nav>
            <div class="row">
                <div class="mb-3 col-sm-6">
                    <label for="titleInput" class="form-label">Tiêu đề bài viết</label>
                    <input name="tieu_de" type="text" id="titleInput" class="form-control" placeholder="Nhập tiêu đề bài viết">
                </div>
                <div class="mb-3 col-sm-6">
                    <label for="slugInput" class="form-label">slug</label>
                    <input type="hidden" name="slug" id="slugHiddenInput">
                    <input disabled type="text" id="slugInput" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-sm-6">
                    <label for="idKhoa" class="form-label">Chọn khoa:</label>
                    <select id="idKhoa" class="form-select" name="id_khoa">
                        <option value="">--- Chọn khoa ---</option>
                        <?php if ($list_khoa) {
                            while ($result = $list_khoa->fetch_assoc()) {
                                $selected = '';
                                if (isset($_GET['id_khoa']) && $_GET['id_khoa'] == $result['id']) {
                                    $selected = 'selected';
                                }
                        ?>
                                <option value="<?php echo $result['id']; ?>" <?php echo $selected; ?>><?php echo $result['name']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="mb-3 col-sm-6">
                    <label for="disabledSelect" class="form-label">Chọn bệnh:</label>
                    <select id="disabledSelect" class="form-select" name="id_benh">
                        <option value="">--- Chọn bệnh ---</option>
                        <?php if ($list_benh) {
                            while ($result = $list_benh->fetch_assoc()) {

                        ?>
                                <option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                        <?php }
                        } ?>
                    </select>

                </div>
            </div>
            <div style="height: 350px;" class="row">
                <div class="mb-3 col-sm-6">
                    <label for="image" class="form-label">Hình ảnh:</label>
                    <input accept="image/*" type="file" id="image" name="image" class="form-control" placeholder="">
                    <input type="hidden" id="selectedImageInput" name="selectedImage">
                    <div id="selectedImageContainer" style="margin-top: 10px; height: 200px; width: 200px;  ">
                        <!-- Nút xóa sẽ được thêm vào đây -->
                    </div>
                </div>
                <div class="mb-3 col-sm-6">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Xem hình ảnh
                    </button>

                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-12">
                    <label for="disabledSelect" class="form-label">Nội dung bài viết:</label>
                    <textarea id="content" name="content" class="tinymce"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-12">
                    <label for="title" class="form-label">Title: </label>
                    <input name="title" type="text" id="title" class="form-control" placeholder="Nhập title">
                </div>
                <div class="mb-3 col-sm-12">
                    <label for="keyword" class="form-label">keyword :</label>
                    <input name="keyword" type="text" id="keyword" class="form-control" placeholder="Nhập keyword">
                </div>
                <div class="mb-3 col-sm-12">
                    <label for="description" class="form-label">Description :</label>
                    <input name="description" type="text" id="description" class="form-control" placeholder="Nhập description">

                </div>
            </div>

            <button name="submit" type="submit" class="btn btn-primary">Tạo bài viết</button>
        </fieldset>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hình ảnh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="image" class="form-label">Chọn hình ảnh:</label>
                    <div style="height: 400px; display: flex; gap:10px; flex-wrap: wrap; overflow: auto;">
                        <?php foreach ($images as $image) : ?>
                            <img class="list-img" src="<?php echo htmlspecialchars($image); ?>" alt="Image" onclick="selectImage(this)">
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="saveSelectedImage()" data-bs-dismiss="modal">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getFileNameFromPath(path) {
            const parts = path.split('/');
            return parts[parts.length - 1];
        }

        function saveSelectedImage() {
            const selectedImage = document.querySelector('.list-img.activeImg');
            if (selectedImage) {
                const selectedImageSrc = selectedImage.src;
                const fileName = selectedImageSrc.split('/').pop();
                const imageInput = document.getElementById('image');
                const selectedImageInput = document.getElementById('selectedImageInput');
                const selectedImageContainer = document.getElementById('selectedImageContainer');
                // Xóa hình ảnh đã chọn trước đó
                selectedImageContainer.innerHTML = '';
                // Tạo phần tử hình ảnh xem trước
                const previewImage = document.createElement('img');
                previewImage.src = `uploads/${fileName}`;
                previewImage.alt = 'Selected Image';
                previewImage.style.width = '200px';
                previewImage.style.height = '200px';
                // Tạo nút xóa
                const deleteButton = document.createElement('button');
                deleteButton.innerText = 'Xóa';
                deleteButton.style.display = 'block';
                deleteButton.style.marginTop = '10px';
                deleteButton.onclick = function() {
                    // Xóa hình ảnh đã chọn
                    selectedImageContainer.innerHTML = '';
                    selectedImageInput.value = '';
                    // Kích hoạt lại input file
                    imageInput.disabled = false;
                };
                // Thêm hình ảnh xem trước và nút xóa vào container
                selectedImageContainer.appendChild(previewImage);
                selectedImageContainer.appendChild(deleteButton);
                // Cập nhật giá trị của input ẩn với tên file hình ảnh được chọn
                selectedImageInput.value = fileName;
                // Vô hiệu hóa input file
                imageInput.disabled = true;

            } else {
                console.log("No image selected");
            }
        }

        function selectImage(imageSrc) {
            const images = document.querySelectorAll('.list-img');
            images.forEach(img => {
                img.classList.remove('activeImg');
            });
            // Thêm lớp activeImg vào hình ảnh được chọn
            imageSrc.classList.add('activeImg');

        }
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const titleInput = document.getElementById("titleInput");
            const slugInput = document.getElementById("slugInput");
            function removeVietnameseTones(str) {
                str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                str = str.replace(/đ/g, 'd').replace(/Đ/g, 'D');

                str = str.replace(/[^a-zA-Z0-9\s]/g, '');

                return str;
            }

            function generateSlug(title) {
                let slug = removeVietnameseTones(title.trim())
                    .toLowerCase() // Chuyển thành chữ thường
                    .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng dấu gạch ngang
                    .replace(/-+/g, '-'); // Loại bỏ các dấu gạch ngang thừa
                return slug;
            }

            titleInput.addEventListener("input", function() {
                let slug = generateSlug(titleInput.value);
                slugInput.value = slug;
                document.getElementById("slugHiddenInput").value = slug;
            });


            <?php if ($message) : ?>
                toastr.<?php echo $message['status']; ?>('<?php echo $message['message']; ?>');
            <?php endif; ?>
        });
    </script>

    ﻿<?php include 'inc/footer.php'; ?>

<?php } else { ?>
    <div style="display: flex; align-items: center; justify-content: center; font-size: 30px; text-transform: uppercase; font-weight: 600; height: 90vh; color: red; ">Trang này không tồn tại</div>
<?php } ?>