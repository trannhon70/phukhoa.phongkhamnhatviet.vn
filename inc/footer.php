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
             <span>Hotline: <strong>0968 063 109</strong> </span>
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
             <a href="tel:+02877767777" class="footer__mobile-container-left">
                 <div class="footer__mobile-container-left-top">TƯ VẤN TRỰC TUYẾN 24/7</div>
                 <span class="footer__mobile-container-left-bottom">028-7776-7777</span>
                 <div class="footer__mobile-container-left-icon">
                     <img loading="lazy" width="65px" height="auto"
                         src="<?php echo $local ?>/images/icons/icon_phone_red.png" alt="...">
                 </div>
                 <div class="footer__mobile-container-left-icon-top">
                     <span>GỌI NGAY</span>
                 </div>
             </a>
             <a href="<?php echo $local ?>" class="footer__mobile-container-right">
                 <div>ĐẶT LỊCH</div>
                 <img loading="lazy" width="70px" height="auto" src="<?php echo $local ?>/images/icons/icon_lich.png"
                     alt="...">
             </a>
         </div>
     </div>
 </div>

 <script defer src="https://livechat.phongkhamnhatviet.vn/chat-box-ai.js"></script>
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
                 const desktopScripts = [{
                         src: '<?php echo $local ?>/js/slider.min.js',
                         id: 'desktop-0'
                     },

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