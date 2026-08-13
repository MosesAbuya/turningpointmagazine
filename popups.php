<div id="success-s-popup">
    <p>Check your email and click on confirm to verify!</p>
</div>

<div id="success-s-f-popup">
    <p>Email is invalid!</p>
</div>

<div id="success-s-e-f-popup">
    <p>Email is already used!</p>
</div>

<div id="success-popup">
    <p>Message sent successfully!</p>
</div>

<div id="success-c-popup">
    <p>Email verified sucessfully!</p>
</div>

<div id="success-c-f-popup">
    <p>Error validating email!</p>
</div>

<div id="success-c-e-popup">
    <p>Link already used!</p>
</div>

<script>
function handlePopup(e, s, c) {
    var p = new URLSearchParams(window.location.search);
    p.has(e) && p.get(e) === s && (document.getElementById(c).style.display = "block", window.history.pushState({},
        document.title, window.location.pathname), setTimeout(function() {
        document.getElementById(c).style.display = "none"
    }, 3e3))
}
handlePopup("feedback", "success", "success-popup"), handlePopup("feedback-s", "success", "success-s-popup"),
    handlePopup("feedback-s", "failed", "success-s-f-popup"), handlePopup("feedback-s", "e-failed",
        "success-s-e-f-popup"), handlePopup("feedback-c", "success", "success-c-popup"), handlePopup("feedback-c",
        "failed", "success-c-f-popup"), handlePopup("feedback-c", "error", "success-c-e-popup");
</script>