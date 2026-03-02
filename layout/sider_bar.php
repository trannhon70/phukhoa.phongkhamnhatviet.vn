 <img class="header__mobile-top-left-icon" loading="lazy" onclick="showSidebar()" width="35px" height="auto"
     loading="lazy" src="<?php echo $local ?>/images/icons/icon_menu.webp" alt="...">
 <img loading="lazy" onclick="hidenSidebar()" class="header__mobile-top-left-icon-close"
     src="<?php echo $local ?>/images/icons/icon_close1.webp" width="35px" height="auto" alt="..."></img>

 <ul class="sidebar_mobile">
     <li>
         <a href="<?php echo $local ?>">Giới thiệu</a>
     </li>

     <li>
         <a href="<?php echo $local ?>">Đội ngũ bác sĩ</a>
     </li>
     <li>
         <a href="<?php echo $local ?>">lịch khám bệnh</a>
     </li>
     <li>
         <a href="https://npa.zoosnet.net/LR/Chatpre.aspx?id=NPA46777247&lng=en">tư vấn trực tuyến</a>
     </li>
     <li>
         <a href="tel:02877779888">đặt lịch</a>
     </li>

 </ul>

 <style>
     .header__mobile-top-left-icon-close {
         display: none;
     }

     /* sider bar */
     @keyframes slideIn {
         0% {
             transform: translateX(-100%);
         }

         100% {
             transform: translateX(0%);
         }
     }

     @keyframes slideOut {
         0% {
             transform: translateX(0%);
         }

         100% {
             transform: translateX(-100%);
         }
     }

     .sidebar_mobile {
         position: fixed;
         top: 61px;
         left: 0;
         height: 90vh;
         width: 280px;
         z-index: 1000;
         background-color: #e880a1;
         backdrop-filter: blur(10px);
         box-shadow: -10px 0 10px rgb(0, 0, 0, 0.1);
         color: white;
         display: none;
         flex-direction: column;
         align-items: flex-start;
         justify-content: flex-start;
         transition: transform 0.5s ease-in-out;
         list-style: none;
         overflow: scroll;
         padding: 0px;
         margin: 0px;
     }

     .sidebar_mobile.active_mobile {
         display: flex;
         animation: slideIn 0.5s forwards;
     }

     .sidebar_mobile.inactive_mobile {
         animation: slideOut 0.5s forwards;
     }

     .sidebar_mobile>li {
         width: 100%;
         padding: 10px;
         border-bottom: 1px solid white;
         box-sizing: border-box;
     }

     .sidebar_mobile>li>a {
         display: inline-block;
         width: 100%;
         font-size: 16px;
         font-weight: 700;
         color: white;
         text-decoration: none;
         text-transform: uppercase;
     }

     .sidebar_mobile>li>div {
         width: 100%;
         font-size: 16px;
         font-weight: 700;
         color: white;
         text-transform: uppercase;
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 0px 0px;
     }

     .sidebar_mobile_li-option {
         background-color: white;
         list-style: none;
         margin-top: 5px;
     }

     .sidebar_mobile_li-option-li {
         width: 100%;
         padding: 3px 10px;
         box-sizing: border-box;
         border-bottom: 2px solid #00D8D8;
     }

     .sidebar_mobile_li-option-li-div {
         margin-top: 5px;
         font-size: 14px;
         color: #01969A;
         font-weight: 700;
         display: flex;
         align-items: center;
         justify-content: space-between;
         text-transform: uppercase;
     }

     .sidebar_mobile_li-option-li-div>img {
         width: 10px;
     }

     .sidebar_mobile_li-option-li>ul {
         list-style: none;
         width: 100%;
     }

     .sidebar_mobile_li-option-li>ul>li {
         width: 100%;
         padding: 6px 5px;
         box-sizing: border-box;
         border-bottom: 2px solid #00D8D8;
     }

     .sidebar_mobile_li-option-li>ul>li>a {
         text-decoration: none;
         display: block;
         width: 100%;
         text-transform: capitalize;
         color: #085e60;
         font-weight: 600;
         font-size: 14px;
     }

     .option__hidden {
         display: none;
     }

     .option__show {
         display: block;
         border-bottom: 0px;
     }
 </style>

 <script defer nonce="abc123">
     function showSidebar() {
         const sidebar = document.querySelector('.sidebar_mobile');
         const icons_menu = document.querySelector('.header__mobile-top-left-icon');
         const icons_sclose = document.querySelector('.header__mobile-top-left-icon-close');
         sidebar.classList.add('active_mobile');
         sidebar.classList.remove('inactive_mobile');
         icons_menu.style.display = "none";
         icons_sclose.style.display = "block"
     }

     function hidenSidebar() {
         const sidebar = document.querySelector('.sidebar_mobile');
         const icons_menu = document.querySelector('.header__mobile-top-left-icon');
         const icons_sclose = document.querySelector('.header__mobile-top-left-icon-close');
         sidebar.classList.add('inactive_mobile');
         setTimeout(() => {
             sidebar.classList.remove('active_mobile');
         }, 500);
         icons_menu.style.display = "block";
         icons_sclose.style.display = "none"
     }
 </script>