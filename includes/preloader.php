<head>
    <style>
    /* --- 1. THEME FONT --- */
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    /* --- 2. THEME VARS --- */
    :root {
        --brand-red: #ff0000;
        --brand-teal: #008080;
    }

    /* --- 3. NEW PRELOADER STYLES --- */
    .preloader {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        /* Better background: clean white */
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 9999999;
        transition: opacity 0.5s ease;
        /* Matches your JS fade-out */
    }

    .preloader-inner {
        position: relative;
        /* We don't need the circle div anymore */
    }

    /* The Text Logo */
    .preloader .preloader-img {
        position: relative;
        z-index: 10;
        text-align: center;
        /* Reset all old positioning */
        top: auto;
        left: auto;
        right: auto;
        transform: none;
        padding: 0;
        margin: 0;
    }

    .preloader .preloader-img h6 {
        font-family: 'florania', sans-serif;
        /* Applied theme font */
        font-size: 3rem;
        /* Increased size */
        color: var(--brand-red);
        /* Applied theme color */
        line-height: 1.1;
        font-weight: 600;
        margin: 0;

        /* New Animation */
        animation: preloader-bounce 1.4s infinite ease-in-out;
    }

    /* Stagger the animation for the second word */
    .preloader .preloader-img h6:last-child {
        animation-delay: 0.2s;
    }

    /* Removed .preloader-circle styles */

    /* --- 4. NEW "BOUNCE" ANIMATION --- */
    @-webkit-keyframes preloader-bounce {

        0%,
        80%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        40% {
            transform: scale(1.15);
            /* "Thump" */
            opacity: 0.8;
        }
    }

    @keyframes preloader-bounce {

        0%,
        80%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        40% {
            transform: scale(1.15);
            /* "Thump" */
            opacity: 0.8;
        }
    }
    </style>
</head>

<body>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-img pere-text">
                    <h6>Turning</h6>
                    <h6>Point</h6>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Check if preloader has already been shown
        const preloaderShown = localStorage.getItem("preloaderShown");

        if (!preloaderShown) {
            // Show preloader
            const preloader = document.getElementById("preloader-active");
            preloader.style.opacity = 1; // Ensure initial opacity is 1

            // Add a short delay to simulate a smoother transition
            setTimeout(() => {
                preloader.style.opacity = '0'; // Fade out effect
                preloader.style.transition = 'opacity 0.5s';

                setTimeout(() => {
                    preloader.style.display = 'none'; // Hide the preloader completely
                }, 500); // Wait for fade-out animation to complete
            }, 1000); // Delay for 1 second

            // Mark preloader as shown in localStorage
            localStorage.setItem("preloaderShown", "true");
        } else {
            // Immediately hide preloader if already shown
            document.getElementById("preloader-active").style.display = "none";
        }
    });
    </SCripT>
</body>