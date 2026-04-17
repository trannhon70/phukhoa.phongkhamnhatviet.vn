<style>
    .live {
        position: fixed;
        bottom: 0;
        left: 0;
        z-index: 2147483648 !important;
        width: 100%;
        height: 80vh;
        animation: slideUp 0.5s ease forwards;
    }


    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }

        to {
            transform: translateY(0);
        }
    }

    .live_top {
        width: 100%;
        height: 25vh;
        display: block;
    }

    .live_top_icon {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
    }

    .live_center {
        width: 100%;
        height: 47vh;
        padding: 10px;
        box-sizing: border-box;
        background-color: #fafafa;
        overflow: auto;
    }

    .live_center_card {
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        width: 90%;
        gap: 10px;
    }

    .live_center_card_text {
        font-size: 14px;
        font-weight: 400;
        color: black;
        background-color: white;
        padding: 10px;
        box-sizing: border-box;
        border-radius: 4px;
        box-shadow: 0px 0px 6px 1px rgba(181, 174, 174, 0.86);
        -webkit-box-shadow: 0px 0px 6px 1px rgba(181, 174, 174, 0.86);
        -moz-box-shadow: 0px 0px 6px 1px rgba(181, 174, 174, 0.86);
    }

    .live_bottom {
        height: 8vh;
        width: 100%;
        background-color: white;
        border-top: 1px solid #dedede;
        padding: 10px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
    }

    .live_bottom_input {
        width: 85%;
        height: 8vh;
        padding: 10px;
        box-sizing: border-box;
        outline: none;
        border: 0px;
        overflow-y: auto;
    }

    .live_bottom_input:empty:before {
        content: attr(placeholder);
        color: #999;
    }

    .live_bottom>button {
        width: 12%;
        height: 6vh;
        box-sizing: border-box;
        display: block;
        outline: none;
        border: 0px;
        background-color: white;
    }

    @media only screen and (min-width: 1000px) {
        .live {
            display: none;
        }
    }

    @media only screen and (max-width: 999px) {
        .live {
            display: block;
        }
    }
</style>

<div class="live show" id="live">
    <a id="closeBtn" class="live_top">
        <img loading="lazy" style="width: 100%; height: 25vh;" src="<?php echo $local ?>/images/live_chat/banner.webp" alt="...">
        <button><img class="live_top_icon" loading="lazy" src="<?php echo $local ?>/images/live_chat/icon_x.webp" alt="..."></button>
    </a>
    <div class="live_center" id="chatBox">

    </div>
    <a id="closeBtn1" class="live_bottom">
        <div
            class="live_bottom_input"
            placeholder="Nhắn tin liên hệ với chuyên gia"></div>
        <button><img style="width: 100%; height: 6vh;" loading="lazy" src="<?php echo $local ?>/images/live_chat/gui.gif" alt="..."></button>
    </a>
</div>

<script>
    const target = document.body;

    if (!window.lrObserver) {
        window.lrObserver = new MutationObserver(() => {
            const el = document.getElementById("LRfloater2");
            if (el) {
                el.style.zIndex = "2147483646";
            }
        });

        window.lrObserver.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true
        });
    }
    document.addEventListener("DOMContentLoaded", () => {
        const live = document.getElementById("live");
        const closeBtn = document.getElementById("closeBtn");
        const closeBtn1 = document.getElementById("closeBtn1");

        let reopenTimeout = null;
        let isFirstLoad = true;

        // Ẩn lúc đầu
        live.style.display = "none";

        // 👉 Lần đầu: hiện sau 3s
        setTimeout(() => {
            live.style.display = "block";
            isFirstLoad = false;
        }, 10000);

        const handleClose = (e) => {
            if (e) e.preventDefault(); // chặn hành vi của <a>

            live.style.display = "none";

            // 👉 gọi luôn chat ngoài
            if (typeof openZoosUrl === "function") {
                openZoosUrl('chatwin');
            }

            if (reopenTimeout) {
                clearTimeout(reopenTimeout);
            }

            reopenTimeout = setTimeout(() => {
                live.style.display = "block";
            }, 15000);
        };

        closeBtn.onclick = handleClose;
        closeBtn1.onclick = handleClose;
    });

    const chatBox = document.getElementById("chatBox");

    const messages = [{
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Chào em.
            </div>
        </div>`,
            delay: 11000
        },
        {
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Em có vấn đề sức khỏe cần tư vấn?
            </div>
        </div>`,
            delay: 2000
        },
        {
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Em hãy chia sẻ, thông tin sẽ được bảo mật hoàn toàn
            </div>
        </div>`,
            delay: 2000
        },
        {
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Nếu không tiện chat em có thể để lại <strong style="color: #f59064;">Số điện thoại</strong> hoặc <strong style="color: #f59064;">Zalo</strong> để được kết nối chuyên viên tư vấn
            </div>
        </div>`,
            delay: 2000
        },
        {
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Có vấn đề thắc mắc cứ nhắn tin để được hỗ trợ kịp thời
            </div>
        </div>`,
            delay: 2000
        },
        {
            text: `<div class="live_center_card">
            <img loading="lazy" style="width: 45px; height: 45px;" src="<?php echo $local ?>/images/live_chat/icon-doctor.webp" alt="...">
            <div class="live_center_card_text">
                Nếu bài viết chưa cung cấp được thông tin mong muốn, em liên hệ bác sĩ để được giải đáp chi tiết hơn.
            </div>
        </div>`,
            delay: 2000
        },
        {
            text: ` <div style="display: flex; align-items: center; justify-content: center;">
            <a href="tel:0901869945" style="width: 70%; display: block; height: auto;">
                <img style="width: 100%; height: auto;" loading="lazy" src="<?php echo $local ?>/images/live_chat/call.gif" alt="...">
            </a>
        </div>`,
            delay: 2000
        },

    ];

    let totalDelay = 0;

    messages.forEach((msg) => {
        totalDelay += msg.delay;

        setTimeout(() => {
            const div = document.createElement("div");
            div.innerHTML = `
                ${msg.text}
            `;

            chatBox.appendChild(div);

            // auto scroll
            chatBox.scrollTop = chatBox.scrollHeight;

        }, totalDelay);
    });
</script>