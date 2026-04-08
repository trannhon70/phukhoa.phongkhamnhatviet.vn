<?php include_once "inc/header.php" ?>
<?php
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$current_url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$slug = basename(parse_url($current_url, PHP_URL_PATH), '.html');


// $getPostDetail = $bai_viet->getBaiViet_bySlug($slug);
$data = $bai_viet->getBaiViet_bySlug($slug);
$getPostDetail = $data['post'];
$post_connection = $data['related'];


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
        echo "<meta property='og:image' content='https://phukhoa.phongkhamnhatviet.vn/admin/uploads/$safeImage'>\n";
        echo "<meta property='og:image:width' content='1200'>\n";
        echo "<meta property='og:image:height' content='630'>\n";
        echo "<meta property='og:type' content='article'>\n";
        echo "<meta property='og:url' content='https://phukhoa.phongkhamnhatviet.vn/{$getPostDetail['slug']}.html'>\n";
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
                        <div style="padding-top:10px">
                            <?php if (Session::get('role') === '1' || Session::get('role') === '2') {
                            ?>

                                <a class="chinh-sua"
                                    href="<?php echo $local ?>/admin/bai-viet-edit.php?edit=<?php echo $getPostDetail['id'] ?>"><i
                                        style="font-size:19px" class="bx bxs-pencil"></i>chỉnh sửa</a>

                            <?php } ?>
                        </div>
                        <div id="category__right-breadcrumb" class="category__right-breadcrumb">
                            Trang chủ > <?php echo $getPostDetail['name_khoa'] ?>
                        </div>
                        <h1 id="titleBaiViet" class="category__right-title">
                            <?php echo $getPostDetail['tieu_de'] ?>
                        </h1>
                        <div id="cardbs">
                            <div
                                style="padding: 10px; display: flex; align-items: center; justify-content: space-between; background-color: aliceblue; ">
                                <div style="display: flex; align-items: center; gap: 2px; ">
                                    <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_star.webp" alt="..."
                                        style="width: 15px; height: 15px;">
                                    <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_star.webp" alt="..."
                                        style="width: 15px; height: 15px;">
                                    <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_star.webp" alt="..."
                                        style="width: 15px; height: 15px;">
                                    <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_star.webp" alt="..."
                                        style="width: 15px; height: 15px;">
                                    <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_star.webp" alt="..."
                                        style="width: 15px; height: 15px;">
                                    <div style="color: #ff9900; font-weight: 700;">
                                        9.5/10 <span style="color: #999999; font-weight: 500;"> điểm</span>
                                    </div>
                                </div>
                                <div id="views" style="color: #999999; font-weight: 700;">
                                    Lượt xem: ...
                                </div>
                            </div>

                        </div>
                        <a href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;" id="bg_mobile_km">
                            <img width="100%" height="auto" src="<?php echo $local ?>/images/banner/bg_mobile_km.gif"
                                alt="...">
                        </a>
                        <hr>

                        <div id="bai-viet" class="body-placeholder">

                        </div>

                    <?php } else { ?>
                        <div><?php echo $getPostDetail ?></div>
                    <?php } ?>
                </div>
                <div class="post_connection">
                    <div class="post_connection_title">Danh sách bài viết liên quan :</div>
                    <?php foreach ($post_connection as $index => $item) { ?>
                        <a class="post_connection_item" href="<?php echo $item["slug"] ?>.html"><span><?php echo $index + 1; ?> .</span> <?php echo $item['title']; ?></a>
                    <?php } ?>

                </div>
                <div class="carousel" id="carousel">
                    <div class="carousel-track">

                        <!-- Slide 1 (LCP) -->
                        <div class="slide">
                            <div style="width:100%;max-width:100%;">
                                <img
                                    src="<?php echo $local ?>/images/banner/bs1.webp"
                                    fetchpriority="high"
                                    decoding="async"
                                    width="1920"
                                    height="600"
                                    style="width:100%;object-fit:cover;"
                                    alt="banner phòng khám">
                                <div>
                                    <h5 style="color: #fa7014; font-size: 16px; margin-top: 5px;  "><strong>BS.</strong> Lê Nguyễn Minh Ngọc</h5>
                                    <div style="margin-top: 5px;"><strong>Chuyên khoa:</strong> Sản Phụ Khoa</div>
                                    <div style="margin-top: 5px;"><strong>Kinh nghiệm:</strong> công tác tại các bệnh viện: Từ Dũ, Hùng Vương, Phụ sản Cần Thơ.</div>
                                    <a href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;" style="display: flex; align-items: center; gap: 10px; width: 100%; margin-top: 5px; text-decoration: none; color: black; ">
                                        <img loading="lazy" style="width: 30px; height:auto;" src="<?php echo $local ?>/images/icons/icon_card.webp" alt="...">
                                        <span style="border-bottom: 1px solid black;">ĐẶT LỊCH HẸN KHÁM</span>
                                    </a>
                                </div>

                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="slide">
                            <div style="width:100%;max-width:100%;">
                                <img
                                    src="<?php echo $local ?>/images/banner/bs2.webp"
                                    fetchpriority="high"
                                    decoding="async"
                                    width="1020"
                                    height="400"
                                    style="width:100%;object-fit:cover;"
                                    alt="banner phòng khám">
                                <div>
                                    <h5 style="color: #fa7014; font-size: 16px; margin-top: 5px;  "><strong>BS.</strong> Huy</h5>
                                    <div style="margin-top: 5px;"><strong>Chuyên khoa:</strong> Sản Phụ Khoa</div>
                                    <div style="margin-top: 5px;"><strong>Kinh nghiệm:</strong> công tác tại các bệnh viện: Từ Dũ, Hùng Vương, Phụ sản Cần Thơ.</div>
                                    <a href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;" style="display: flex; align-items: center; gap: 10px; width: 100%; margin-top: 5px; text-decoration: none; color: black; ">
                                        <img loading="lazy" style="width: 30px; height:auto;" src="<?php echo $local ?>/images/icons/icon_card.webp" alt="...">
                                        <span style="border-bottom: 1px solid black;">ĐẶT LỊCH HẸN KHÁM</span>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>

                    <button style=" display:none;" class="nav prev">❮</button>
                    <button style="display:none;" class="nav next">❯</button>
                    <div class="dots"></div>
                </div>
            </article>
        </main>
        <script defer src="<?php echo $local ?>/js/carousel.min.js"></script>
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
                            divWrapper.href = "javascript:void(0)";
                            divWrapper.addEventListener("click", function() {
                                openZoosUrl('chatwin');
                            });
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
                            'https://phukhoa.phongkhamnhatviet.vn/ckfinder/userfiles/images/Chat/Chat-Dakhoa.gif')) {
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
        <script defer>
            function getRandomViews() {
                return Math.floor(Math.random() * (10000 - 2000 + 1)) + 2000;
            }

            const viewElement = document.getElementById('views');
            viewElement.textContent = `Lượt xem: ${getRandomViews()}`;
        </script>

        <?php include_once "inc/footer.php" ?>

    <?php } else { ?> <div
            style="display:flex;align-items:center;justify-content:center;color:red;font-size:30px;height:100vh">link bài
            viết này không tồn tại!</div> <?php } ?>