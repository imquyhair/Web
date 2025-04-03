let currentSlide = 0;
const slides = document.querySelectorAll('.song-card');
const totalSlides = slides.length;
const wrapper = document.querySelector('.songs-wrapper');
const slideWidth = () => slides[0].offsetWidth + 20;

const moveSlider = (direction) => {
    const nextSlide = currentSlide + direction;

    if (nextSlide < 0 || nextSlide >= totalSlides) {
        return;
    }

    currentSlide = nextSlide;

    const newTransform = -currentSlide * slideWidth();
    wrapper.style.transition = 'transform 0.5s ease-in-out';
    wrapper.style.transform = `translateX(${newTransform}px)`;
};

const updateSliderWidth = () => {
    const newSlideWidth = slideWidth();
    const newTransform = -currentSlide * newSlideWidth;
    wrapper.style.transition = 'none';
    wrapper.style.transform = `translateX(${newTransform}px)`;
    wrapper.offsetHeight;
    wrapper.style.transition = 'transform 0.5s ease-in-out';
};


document.querySelector('.btn-slide.left').addEventListener('click', () => moveSlider(-1));
document.querySelector('.btn-slide.right').addEventListener('click', () => moveSlider(1));
document.addEventListener("DOMContentLoaded", function () {
    let songCards = document.querySelectorAll(".song-card");
    let audioPlayer = document.getElementById("audio-player");
    let songTitle = document.getElementById("current-song-title");
    let songArtist = document.getElementById("current-song-artist");
    let songImage = document.getElementById("current-song-img");
    let playPauseBtn = document.getElementById("play-pause-btn");
    let seekBar = document.getElementById("seek-bar");

    songCards.forEach(card => {
        card.addEventListener("click", function () {
            let audio = this.getAttribute("data-audio");
            let title = this.getAttribute("data-title");
            let artist = this.getAttribute("data-artist");
            let image = this.getAttribute("data-image");

            audioPlayer.src = audio;
            songTitle.textContent = title;
            songArtist.textContent = artist;
            songImage.src = image;

            audioPlayer.play();
            playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("searchForm").addEventListener("submit", function (event) {
            let searchInput = document.getElementById("searchInput").value.trim();

            if (searchInput === "") {
                alert("Vui lòng nhập từ khóa tìm kiếm!");
                event.preventDefault(); // Ngăn form gửi đi
            }

        });

    });
    AOS.init({
        duration: 1000,
        once: true
    });


});
function toggleChat(event) {
    if (event) event.preventDefault();
    let chatbox = document.getElementById("chatbot");
    chatbox.style.display = (chatbox.style.display === "none" || chatbox.style.display === "") ? "flex" : "none";
}

function sendMessage() {
    let inputField = document.getElementById("chatbot-text");
    let message = inputField.value.trim();
    if (message === "") return;

    appendMessage("user", message);

    // Gửi dữ liệu đến chatbot.php
    fetch("chatbot.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message })
    })
        .then(response => response.json())
        .then(data => {
            appendMessage("ai", data.response); // Hiển thị phản hồi từ AI
        })
        .catch(error => {
            appendMessage("ai", "⚠️ Có lỗi xảy ra, vui lòng thử lại!");
        });

    inputField.value = "";
}


function appendMessage(sender, message) {
    let chatboxMessages = document.getElementById("chatbot-messages");

    if (!chatboxMessages) {
        console.error("Không tìm thấy phần hiển thị tin nhắn!");
        return;
    }

    let messageDiv = document.createElement("div");
    messageDiv.className = sender === "user" ? "message user-message" : "message ai-message";
    messageDiv.innerHTML = `<b>${sender === "user" ? "Bạn" : "AI"}:</b> ${message}`;

    chatboxMessages.appendChild(messageDiv);
    chatboxMessages.scrollTop = chatboxMessages.scrollHeight; // Cuộn xuống tin nhắn mới nhất
}

function playSong(songId) {
    alert("Phát bài hát có ID: " + songId);
}

document.addEventListener("DOMContentLoaded", function () {
    // 🌟 Kiểm tra xem trang có đang được load từ logo hoặc quay lại từ "Nghe ngay" không
    let isReloadFromLogo = sessionStorage.getItem("reloadFromLogo");
    let isReturning = sessionStorage.getItem("returningFromMusic");

    if (isReloadFromLogo || isReturning) {
        // Nếu quay lại từ "Nghe ngay" hoặc reload từ logo, khôi phục tin nhắn chatbot
        let savedMessages = localStorage.getItem("chatbotHistory");
        if (savedMessages) {
            let chatbox = document.getElementById("chatbot-messages");
            if (chatbox) {
                chatbox.innerHTML = savedMessages;
            }
        }
    } else {
        // Nếu bấm F5 hoặc refresh trình duyệt → Xóa hết dữ liệu chatbot
        localStorage.removeItem("chatbotHistory");
    }

    // Sau khi trang tải xong, xóa cờ tránh giữ trạng thái vĩnh viễn
    sessionStorage.removeItem("reloadFromLogo");
    sessionStorage.removeItem("returningFromMusic");
});

// 🌟 Lưu tin nhắn vào localStorage trước khi tải lại trang từ logo
function reloadWithoutLosingChat(event) {
    event.preventDefault(); // Ngăn trang load lại toàn bộ

    let chatbotMessages = document.getElementById("chatbot-messages").innerHTML;
    localStorage.setItem("chatbotHistory", chatbotMessages);

    // Đánh dấu rằng trang đang reload từ logo
    sessionStorage.setItem("reloadFromLogo", "true");

    // Tải lại trang mà không mất dữ liệu chatbot
    location.reload();
}

// 🌟 Xử lý khi người dùng nhấn vào "Nghe ngay"
document.addEventListener("click", function (event) {
    let target = event.target.closest("a");

    if (target && target.href.includes("playlist.php?id=")) {
        // Trước khi chuyển trang, lưu lịch sử tin nhắn
        let chatbotMessages = document.getElementById("chatbot-messages").innerHTML;
        localStorage.setItem("chatbotHistory", chatbotMessages);

        // Đánh dấu rằng người dùng đang đi đến trang nghe nhạc
        sessionStorage.setItem("returningFromMusic", "true");
    }
});


// 🌟 Lưu tin nhắn khi chuyển trang
document.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", function (event) {
        let chatbotMessages = document.getElementById("chatbot-messages").innerHTML;
        localStorage.setItem("chatbotHistory", chatbotMessages);

        // Đánh dấu rằng trang đang chuyển hướng
        sessionStorage.setItem("navigated", "true");
    });
});

// 🌟 Gắn sự kiện khi bấm vào logo
document.addEventListener("DOMContentLoaded", function () {
    let logo = document.getElementById("logo");
    if (logo) {
        logo.addEventListener("click", reloadWithoutLosingChat);
    }
});
document.addEventListener("DOMContentLoaded", function () {
    // 🌟 Kiểm tra xem trang có đang được load từ logo hoặc chuyển trang không
    let isReloadFromLogo = sessionStorage.getItem("reloadFromLogo");
    let isNavigated = sessionStorage.getItem("navigated");

    if (isReloadFromLogo || isNavigated) {
        // Nếu làm mới từ logo hoặc chuyển trang, khôi phục tin nhắn chatbot
        let savedMessages = localStorage.getItem("chatbotHistory");
        if (savedMessages) {
            let chatbox = document.getElementById("chatbot-messages");
            if (chatbox) {
                chatbox.innerHTML = savedMessages;
            }
        }

        // Xóa cờ để lần refresh tiếp theo sẽ reset toàn bộ trang
        sessionStorage.removeItem("reloadFromLogo");
        sessionStorage.removeItem("navigated");
    } else {
        // Nếu làm mới từ F5 hoặc refresh trình duyệt, xóa dữ liệu cũ để trang reset toàn bộ
        localStorage.removeItem("chatbotHistory");
    }
});

// 🌟 Lưu tin nhắn vào localStorage trước khi tải lại trang từ logo
function reloadWithoutLosingChat(event) {
    event.preventDefault(); // Ngăn trang load lại toàn bộ

    let chatbotMessages = document.getElementById("chatbot-messages").innerHTML;
    localStorage.setItem("chatbotHistory", chatbotMessages);

    // Đánh dấu rằng trang đang reload từ logo
    sessionStorage.setItem("reloadFromLogo", "true");

    // Tải lại trang mà không mất dữ liệu chatbot
    location.reload();
}

// 🌟 Lưu tin nhắn khi chuyển trang
document.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", function (event) {
        let chatbotMessages = document.getElementById("chatbot-messages").innerHTML;
        localStorage.setItem("chatbotHistory", chatbotMessages);

        // Đánh dấu rằng trang đang chuyển hướng
        sessionStorage.setItem("navigated", "true");
    });
});

// 🌟 Gắn sự kiện khi bấm vào logo
document.addEventListener("DOMContentLoaded", function () {
    let logo = document.getElementById("logo");
    if (logo) {
        logo.addEventListener("click", reloadWithoutLosingChat);
    }
});

document.addEventListener("mousemove", (e) => {
    const iframe = document.querySelector("iframe");
    const x = (e.clientX / window.innerWidth - 0.5) * 20;
    const y = (e.clientY / window.innerHeight - 0.5) * 20;

    iframe.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
});




