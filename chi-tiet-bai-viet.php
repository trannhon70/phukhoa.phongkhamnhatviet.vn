<?php include_once "inc/header.php" ?>
<?php
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$current_url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$slug = basename(parse_url($current_url, PHP_URL_PATH), '.html');


$getPostDetail = $bai_viet->getBaiViet_bySlug($slug);


// var_dump($getPostDetail);
if (isset($getPostDetail["hiden"]) && $getPostDetail["hiden"] === "1") {
    http_response_code(404);
    include '404.html'; // hoặc '404.php'
    exit();
}
function setTitleAndScroll()
{
    global $getPostDetail; // Đảm bảo truy cập biến toàn cục
    if ($getPostDetail && isset($getPostDetail['tieu_de'])) {
        // Lấy các giá trị từ $getPostDetail
        $title = isset($getPostDetail['tieu_de']) ? $getPostDetail['tieu_de'] : 'Default Title';
        $description = isset($getPostDetail['descriptions']) ? $getPostDetail['descriptions'] : 'Default Description';
        $keywords = isset($getPostDetail['keyword']) ? $getPostDetail['keyword'] : 'default, keywords';
        $image = isset($getPostDetail['img']) ? $getPostDetail['img'] : '/path/to/default-image.jpg';

        // Chuyển đổi các giá trị sang dạng an toàn cho HTML và JavaScript
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeDescription = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $safeKeywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');
        $safeImage = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');

        // Đảm bảo rằng bạn chèn vào trong thẻ <head>
        echo "<title>$safeTitle</title>\n";
        echo "<meta name='description' content='$safeDescription'>\n";
        echo "<meta name='keywords' content='$safeKeywords'>\n";
        echo "<meta property='og:title' content='$safeTitle'>\n";
        echo "<meta property='og:description' content='$safeDescription'>\n";
        echo "<meta property='og:image' content='https://phukhoa.nhatvietclinic.vn/admin/uploads/$safeImage'>\n";
        echo "<meta property='og:image:width' content='1200'>\n";
        echo "<meta property='og:image:height' content='630'>\n";
        echo "<meta property='og:type' content='article'>\n";
        echo "<meta property='og:url' content='https://phukhoa.nhatvietclinic.vn/{$getPostDetail['slug']}.html'>\n";
    }
}
setTitleAndScroll();
?>

<link rel="stylesheet" href="css/chi_tiet_bai_viet.min.css">

</head>

<body>

    <?php if (isset($getPostDetail['hiden_khoa']) && $getPostDetail['hiden_khoa'] === '1'): ?>
        <script type="text/javascript">
            window.location.href = "<?php echo $local ?>/404.php";
        </script>
    <?php endif; ?>

    <?php if (isset($getPostDetail)) { ?>
        <?php include "layout/header_layout.php" ?>
        <main>
            <article>
                <div class="category">
                    <?php if ($getPostDetail !== 'Hiện tại dữ liệu này chưa có bài viết!') { ?>
                        <h1 id="titleBaiViet" class="category__right-title">
                            <?php echo $getPostDetail['tieu_de'] ?>
                        </h1>
                        <div style="padding-top:10px">
                            <?php if (Session::get('role') === '1' || Session::get('role') === '2') {
                            ?>

                                <a class="chinh-sua"
                                    href="<?php echo $local ?>/admin/bai-viet-edit.php?edit=<?php echo $getPostDetail['id'] ?>"><i
                                        style="font-size:19px" class="bx bxs-pencil"></i>chỉnh sửa</a>

                            <?php } ?>
                        </div>
                        <div id="bai-viet" class="body-placeholder">

                        </div>
                        <div class="bai-viet-footer">Nội dung bài viết cung cấp nhằm mục đích tham khảo thêm kiến thức y tế,
                            một số nội dung có thể không thuộc nghiệp vụ của phòng khám chúng tôi, Hiệu quả của việc hỗ trợ
                            điều trị phụ thuộc vào cơ địa của mỗi người. Cần biết thông tin liên hệ để được tư vấn trực
                            tuyến miễn phí.<a aria-label="tư vấn"
                                href="https://npa.zoosnet.net/LR/Chatpre.aspx?id=NPA46777247&lng=en">[TƯ VẤN
                                TRỰC TUYẾN]</a>
                        </div>
                    <?php } else { ?>
                        <div><?php echo $getPostDetail ?></div>
                    <?php } ?>
                </div>

            </article>
        </main>

        <script defer>
            function applyCSSandJS() {

                //images gây shock
                const shockElements = document.querySelectorAll('.shock_img');
                shockElements.forEach(shockElement => {
                    shockElement.classList = 'hiden_img'
                    const viewdiv = document.createElement('div');
                    viewdiv.id = 'viewdiv';
                    viewdiv.className = 'view';
                    viewdiv.textContent = 'Hình ảnh có nội dung gây shock !! cân nhất trước khi xem';

                    const viewbutton = document.createElement('button');
                    viewbutton.id = 'viewbutton';
                    viewbutton.className = 'view_button';
                    viewbutton.textContent = 'click vào xem';
                    // Append the button to the parent of the shockElement (image-container)
                    shockElement.appendChild(viewdiv);
                    shockElement.appendChild(viewbutton);

                    // Add click event listener to the button
                    viewbutton.addEventListener('click', () => {
                        // Remove the blur effect
                        shockElement.classList.remove('blurred');
                        shockElement.classList.remove('hiden_img');
                        // Hide the button
                        viewdiv.classList.add('hidden');
                        viewbutton.classList.add('hidden');
                    });
                })

                let baiVietElement = document.getElementById('bai-viet');
                if (baiVietElement) {
                    let pElements = baiVietElement.getElementsByTagName('p');
                    for (let i = 0; i < pElements.length; i++) {
                        pElements[i].style.color = '#000000'; // Ghi đè CSS trước đó
                        pElements[i].style.fontWeight = 400;
                        pElements[i].style.fontSize = '13px';
                        pElements[i].style.lineHeight = '27px';
                    }
                }

                let imgElements = baiVietElement.getElementsByTagName('img');
                if (imgElements) {
                    for (let i = 0; i < imgElements.length; i++) {
                        // convert link img
                        if (imgElements[i].src.startsWith('<?php echo $local ?>/ckeditor/uploads/') == true) {
                            let urlParts = imgElements[i].src.split('/');
                            let fileName = urlParts[urlParts.length - 1];
                            imgElements[i].src = '<?php echo $local ?>/admin/ckeditor/uploads/' + fileName;
                        }

                        //hiển thị css img chatbox
                        if (imgElements[i].src.startsWith(
                                '<?php echo $local ?>/ckfinder/userfiles/images/Chat/Chat-Dakhoa.gif') ==
                            // if (imgElements[i].src.startsWith('http://localhost/ckfinder/userfiles/images/Chat/Chat-Dakhoa.gif') ==
                            true) {
                            imgElements[i].style.borderRadius = '8px';
                            imgElements[i].style.setProperty('display', 'block', 'important');
                            let divWrapper = document.createElement('a');
                            divWrapper.className = 'glow-on-hover';
                            divWrapper.href = "https://npa.zoosnet.net/LR/Chatpre.aspx?id=NPA46777247&lng=en";
                            divWrapper.setAttribute("aria-label", "Chat da khoa");
                            imgElements[i].parentNode.insertBefore(divWrapper, imgElements[i]);
                            divWrapper.appendChild(imgElements[i])
                        }

                    }

                }

                if (baiVietElement) {
                    let h2Elements = baiVietElement.getElementsByTagName('h2');
                    for (let i = 0; i < h2Elements?.length; i++) {

                        h2Elements[i].classList.add('custom-h2-style');

                    }

                    let h3Element = baiVietElement.getElementsByTagName('h3');

                    for (let i = 0; i < h3Element.length; i++) {
                        h3Element[i].style.color = 'rgb(255 0 80)';
                        h3Element[i].style.fontWeight = '700';
                        h3Element[i].style.fontSize = '18px';
                        h3Element[i].style.textTransform = 'capitalize';
                        h3Element[i].style.background =
                            'url("<?php echo $local ?>/images/icons/icon_mui.gif") no-repeat left center';
                        h3Element[i].style.backgroundSize = '21px 21px';
                        h3Element[i].style.paddingLeft = '25px';
                        h3Element[i].style.margin = '7px 0px';
                    }
                }

            }
        </script>

        <script>
            function checkImgMobile() {
                let baiVietElement = document.getElementById('bai-viet');
                if (!baiVietElement || window.innerWidth > 900) return;
                let elements = document.querySelectorAll('.glow-on-hover');
                let imgElements = baiVietElement.getElementsByTagName('img');
                let h2Elements = baiVietElement.getElementsByTagName('h2');
                let h3Elements = baiVietElement.getElementsByTagName('h3');
                if (elements) {
                    elements.forEach(element => {
                        element.classList.add('glow-on-hover')
                    })
                }
                Array.from(imgElements).forEach(img => {
                    const src = img.getAttribute('src');
                    if (src && !src.startsWith('/ckfinder/userfiles/images/Icon')) {
                        img.classList.add('img-responsive')
                    }
                    if (img.src.startsWith(
                            'https://phukhoa.nhatvietclinic.vn/ckfinder/userfiles/images/Chat/Chat-Dakhoa.gif')) {
                        // if (img.src.startsWith('http://localhost/ckfinder/userfiles/images/Chat/Chat-Dakhoa.gif')) {
                        img.classList.add('img-gif');

                    }
                });
                Array.from(h2Elements).forEach(h2 => {
                    h2.classList.add('h2-custom')
                });
                Array.from(h3Elements).forEach(h3 => {
                    h3.classList.add('h3-custom')
                })
            }
            const bodyPlaceholder = document.getElementById("bai-viet");

            const loadBody = () => {
                let content = `<?php echo htmlspecialchars_decode($getPostDetail['content']); ?>`;
                // Gán tạm nội dung vào DOM ẩn để xử lý
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = content;

                // Duyệt tất cả text node
                const walker = document.createTreeWalker(tempDiv, NodeFilter.SHOW_TEXT, null, false);
                while (walker.nextNode()) {
                    const node = walker.currentNode;
                    // Thay số điện thoại
                    // node.nodeValue = node.nodeValue.replace(/0968\s*063\s*109/g, '0968 063 109, 028 7777 9888');
                }

                // Gán ra DOM chính
                bodyPlaceholder.innerHTML = tempDiv.innerHTML;
                bodyPlaceholder.classList.add("loaded");
            };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {

                        loadBody();
                        applyCSSandJS();
                        checkImgMobile()

                    }
                });
            });

            // Khởi tạo tải content ban đầu và bắt đầu quan sát bodyPlaceholder

            observer.observe(bodyPlaceholder);
        </script>

        <?php include_once "inc/footer.php" ?>




    <?php } else { ?> <div
            style="display:flex;align-items:center;justify-content:center;color:red;font-size:30px;height:100vh">link bài
            viết này không tồn tại!</div> <?php } ?>