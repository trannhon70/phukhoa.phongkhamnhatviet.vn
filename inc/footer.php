 <footer class="footer">
     <div class="footer_top">
         <img loading="lazy" style="width: 50px; height: auto;" src="<?php echo $local ?>/images/logo/logo-note.webp"
             alt="...">
         <div class="footer_top_right">
             <div class="footer_top_right_title">PHÒNG KHÁM</div>
             <div class="footer_top_right_title1">JV NHẬT VIỆT</div>
         </div>
     </div>
     <div class="footer_list">
         <div class="footer_list_item">
             <img loading="lazy" style="width: 20px; height: auto;" src="<?php echo $local ?>/images/icons/icon-9.webp"
                 alt="...">
             <span>73 Kinh Dương Vương, P. Phú Lâm, TP.HCM </span>
         </div>
         <div class="footer_list_item">
             <img loading="lazy" style="width: 20px; height: auto;" src="<?php echo $local ?>/images/icons/icon-10.webp"
                 alt="...">
             <span>Hotline: <strong>0901 869 945</strong> </span>
         </div>
         <!-- <div class="footer_list_item">
             <img loading="lazy" style="width: 20px; height: auto;" src="<?php echo $local ?>/images/icons/icon-11.webp"
                 alt="...">
             <span>pknhatviet@gmail.com </span>
         </div> -->
     </div>
     <img loading="lazy" style="width: 100%; height: auto; margin-top: 10px;"
         src="<?php echo $local ?>/images/banner/map.webp" alt="..." />
 </footer>
 <div class="footer_fixed">
     <div class="footer__mobile-body">
         <div class="footer__mobile-container">
             <a href="tel:0901869945" class="footer__mobile-container-left">
                 <div class="footer__mobile-container-left-top">TƯ VẤN TRỰC TUYẾN 24/7</div>
                 <span class="footer__mobile-container-left-bottom">0901-869-945</span>
                 <div class="footer__mobile-container-left-icon">
                     <img loading="lazy" width="65px" height="auto"
                         src="<?php echo $local ?>/images/icons/icon_phone_red.png" alt="...">
                 </div>
                 <div class="footer__mobile-container-left-icon-top">
                     <span>GỌI NGAY</span>
                 </div>
             </a>
             <a href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;" class="footer__mobile-container-right">
                 <div>ĐẶT LỊCH</div>
                 <img loading="lazy" width="70px" height="auto" src="<?php echo $local ?>/images/icons/icon_lich.png"
                     alt="...">
             </a>
         </div>
     </div>
 </div>
 <div class="footer_list_icon">
     <div>
         <a class="footer_icon_happy" href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;">
             <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_happy.gif" height="50px" width="50px"
                 alt="..."></img>
         </a>
     </div>
     <div style="margin-top:10px">
         <a class="footer_icon_zalo" href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;">
             <img loading="lazy" src="<?php echo $local ?>/images/icons/icon_zalo.png" height="50px" width="50px"
                 alt="..."></img>
             <div class="ping_zalo"></div>
         </a>
     </div>
     <div style="margin-top:10px">
         <a class="footer_icon_mess" href="javascript:void(0)" onclick="openZoosUrl('chatwin'); return false;">
             <img loading="lazy" style="margin-left:3px" src="<?php echo $local ?>/images/icons/icon_message.webp"
                 height="45px" width="45px" alt="..."></img>
             <div class="ping"></div>
             <div class="footer_list_icon_number">10</div>
         </a>
     </div>
 </div>

 <?php include_once "layout/live_chat.php" ?>
 <script language="javascript" src="https://tuvan.mayo.com.vn/JS/LsJS.aspx?siteid=KUK38256576&float=1&lng=en"></script>
 <script defer src="<?php echo $local ?>/js/random_number.min.js"></script>

 <script defer>
     let element = document.querySelector('.footer__mobile-container-left-icon-top');

     function hidenIconFooterCall() {
         if (element) {
             element.style.display = 'none'
         }
     }

     function showIconFooterCall() {
         if (element) {
             element.style.display = 'block'
         }
     }

     setInterval(() => {
         hidenIconFooterCall();
         setTimeout(showIconFooterCall, 1000);
     }, 3000);
 </script>
 <script>
     document.addEventListener('DOMContentLoaded', () => {
         function updateHeaderScripts() {
             // Xóa các script cũ nếu có
             const existingMobileScripts = document.querySelectorAll('script[id^="mobile-"]');
             const existingDesktopScripts = document.querySelectorAll('script[id^="desktop-"]');
             existingMobileScripts.forEach(script => script.remove());
             existingDesktopScripts.forEach(script => script.remove());

             if (window.innerWidth < 1000) {
                 const mobileScripts = [
                     // {
                     //      src: '<?php echo $local ?>/js/mobile.min.js',
                     //      id: 'mobile-0'
                     //  },
                     // {
                     //     src: 'js/siderbar_mobile.min.js',
                     //     id: 'mobile-1'
                     // },

                 ];
                 mobileScripts.forEach(({
                     src,
                     id
                 }) => {
                     const script = document.createElement('script');
                     script.src = src;
                     script.id = id;
                     document.body.appendChild(script);
                 });
             } else {
                 const desktopScripts = [
                     // {
                     //      src: '<?php echo $local ?>/js/slider.min.js',
                     //      id: 'desktop-0'
                     //  },

                 ];
                 desktopScripts.forEach(({
                     src,
                     id
                 }) => {
                     const script = document.createElement('script');
                     script.src = src;
                     script.id = id;
                     document.body.appendChild(script);
                 });
             }
         }

         updateHeaderScripts();

         window.addEventListener('resize', () => {
             updateHeaderScripts();
         });
     });
 </script>
 </body>

 </html>