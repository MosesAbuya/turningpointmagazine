<style>
.sticky-container {
    padding: 0px;
    margin: 0px;
    position: fixed;
    right: -130px;
    top: 160px;
    width: 210px;
    z-index: 1100;
}

.sticky .li {
    list-style-type: none;
    backdrop-filter: blur(16px) saturate(150%);
    -webkit-backdrop-filter: blur(16px) saturate(150%);
    background-color: rgba(216, 222, 234, 0.75);
    border: 1px solid rgba(255, 255, 255, 0.125);
    color: #efefef;
    height: 43px;
    padding: 0px;
    margin: 0px 0px 1px 0px;
    -webkit-transition: all 0.25s ease-in-out;
    -moz-transition: all 0.25s ease-in-out;
    -o-transition: all 0.25s ease-in-out;
    transition: all 0.25s ease-in-out;
    cursor: pointer;
}

.sticky .li:hover {
    margin-left: -115px;
}

.sticky .li img {
    float: left;
    margin: 5px 4px;
    margin-right: 5px;
}

.sticky .li p {
    padding-top: 5px;
    margin: 0px;
    line-height: 16px;
    font-size: 11px;
}

.sticky .li p a {
    text-decoration: none;
    color: #2c3539;
}

.sticky .li p a:hover {
    text-decoration: underline;
}
</style>

<div class="sticky-container">
    <ul class="sticky">
        <li class="li">
            <img loading="lazy" src="assets/facebook.png" width="32" height="32">
            <p><a href="https://m.facebook.com/profile.php?id=100090750335981" target="_blank">Like Us
                    on<br>Facebook</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/x.png" width="32" height="32">
            <p><a href="https://x.com/" target="_blank">Follow Us on<br>Twitter</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/whatsapp.png" width="32" height="32">
            <p><a href="https://wa.link/hk1c7c" target="_blank">Chat with us on <br>Whatsapp</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/linkedin.png" width="32" height="32">
            <p><a href="https://www.linkedin.com/company/malshe-media/posts/?feedView=all" target="_blank">Follow Us
                    on<br>LinkedIn</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/youtube.png" width="32" height="32">
            <p><a href="http://www.youtube.com/" target="_blank">Subscribe on<br>YouYube</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/instagram.png" width="32" height="32">
            <p><a href="https://www.pinterest.com/" target="_blank">Follow Us on<br>Instagram</a></p>
        </li>
        <li class="li">
            <img loading="lazy" src="assets/gmail.png" width="32" height="32">
            <p><a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@malshemedia.com" target="_blank">Email
                    us<br>Now</a></p>
        </li>
    </ul>
</div>




<script>
function getIPAddress() {
    var t = new XMLHttpRequest;
    t.open("GET", "https://api.ipify.org?format=json", !0), t.onload = function() {
        var e;
        200 === t.status ? (e = JSON.parse(t.responseText).ip, document.getElementById("ipAddress").value = e) :
            console.error("Error getting IP address: " + t.status)
    }, t.send()
}
getIPAddress(), document.addEventListener("DOMContentLoaded", () => {
    const e = document.getElementById("feedbackPopup");
    var t = document.getElementById("closePopup");
    const s = document.getElementById("floatingBtn");
    let n;

    function d() {
        n = setTimeout(() => {
            s.classList.add("visible")
        }, 5e3)
    }
    t.addEventListener("click", () => {
        e.classList.remove("visible"), s.classList.add("visible"), clearTimeout(n)
    }), s.addEventListener("click", () => {
        e.classList.add("visible"), s.classList.remove("visible"), d()
    }), d()
});
</script>
