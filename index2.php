<head>
    <!-- Preconnect to Fonts and External Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon and App Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>


    <!-- Google Fonts (Preload for Faster Access) -->
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400&display=swap"
        as="style">

    <!-- Critical CSS Files (Minified if possible) -->
    <link rel="stylesheet" href="style.css"> <!-- Ensure this is minified -->
    <link rel="stylesheet" href="dflip/css/dflip.min.css" type="text/css"> <!-- Minified version -->
    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css"> <!-- Minified version -->

    <!-- External Libraries (use CDN for better performance) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Preload JavaScript and Libraries -->
    <script src="https://kit.fontawesome.com/f65faecb5f.js" crossorigin="anonymous" defer></script>
    <!-- Use defer for non-blocking JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    <!-- Use the latest jQuery from Google CDN -->

    <!-- Preload your custom PHP includes -->
    <?php include 'includes/preloader.php'; ?>

    <!-- Meta Tags for Mobile Optimization -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">






</head>

<section class="et-hero-tabs">
    <div class="tab-1">
        <h1 id="b-h1">Turning Point</h1>
        <h3 class="b-h3">The Magazine of Choice</h3>
    </div>
    <div class="scroll-down-icon">
        <a href="#latest"> <i class="bi bi-chevron-down"></i></a>

    </div>

    <?php include 'includes/nav2.php' ?>
</section>


<body>

    <style>
    .mag {
        height: 400px;
        background-color: transparent;
    }

    #latest {
        height: fit-content;
    }
    </style>

    <!-- Main -->
    <main class="et-main">
        <?php include 'includes/sidebar.php'?>
        <section class="et-slide" id="latest">

            <div class="container " id="lates">

                <div class="row">
                    <div class="col-xs-12">
                        <h1>Latest Issue in our list of publications</h1>

                    </div>
                    <div class="col-xs-12" style="padding-bottom:30px">
                        <!--Normal FLipbook-->
                         
                                <img loading="lazy" src="assets/mag.jpg" class="cover-image" style="height: 300px; width: auto;" />
                            

                    </div>


                </div>
            </div>


        </section>

        <section class="et-slide" id="collection">
            <h1>Our Magazine Library</h1>
            <h3></h3>

            <div class="card-container">
                <div class="card-container-inner">
                    <a href="issue-1.php" alt="Mythrill" target="_blank">
                        <div class="card">
                            <div class="wrapper">
                                <img loading="lazy" src="assets/mag.jpg" class="cover-image" />
                            </div>
                            <img loading="lazy" src="assets/title2.png" class="title" />
                            <img loading="lazy" src="assets/barnice.png" class="character" />
                        </div>
                    </a>
                </div>




            </div>

            <div class="rectangle-b"></div>
            <div class="rectangle"></div>



        </section>


        <script src="newjs.js">

        </script>

        <div class="et-slidess">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 199" class="bv-11">
                <path fill="#d44b4b" fill-opacity="1" d="M0,192L1440,64L1440,320L0,320Z"></path>
            </svg>
            <div class="counter-wrapper">
                <div class="counter">
                    <div class="c-out">
                        <h1 class="count">
                            100+
                        </h1>

                        <p class="p-1">Advertisements</p>
                    </div>
                </div>

                <div class="counter">
                    <h1 class="count">300+</h1>
                    <p class="p-1">Visitors</p>
                </div>

                <div class="counter c-a">
                    <h1 class="count h1">500+</h1>
                    <p class="p-1">Subscriptions</p>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 190" class="bv-3">
                <path fill="#d44b4b" fill-opacity="1" d="M0,192L1440,64L1440,320L0,320Z"></path>
            </svg>
        </div>

        </div>

        <section class=" et-slide" id="subscribe">
            <h1>Don't miss out</h1>
            <p class="f-head">Subscribe now to get regular updates whenever we have a new publication</p>


            <div class="form form-sm">
                <img loading="lazy" class="img-f" src="assets/s1s1.webp" alt="">
                <form id="subscribeForm" name="sub" method="POST">
                    <div class="form-item">
                        <input type="text" placeholder="First Name" class="input" name="f-name" required>
                    </div>
                    <div class="form-item">
                        <input type="text" placeholder="Last Name" class="input" name="l-name" required>
                    </div>
                    <div class="form-item">
                        <input type="text" placeholder="Address" class="input" name="address" required>
                    </div>
                    <div class="form-item">
                        <input type="text" placeholder="Email" class="input" name="email" required>
                    </div>
                    <button type="submit" class="btn">
                        <span class="btn-text">Subscribe</span>
                        <span class="btn-shine"></span>
                    </button>
                    <div id="subscribe-response" style="width: 300px"></div>
                </form>


                <!-- Include jQuery -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                $(document).ready(function() {
                    $('#subscribeForm').submit(function(e) {
                        e.preventDefault(); // Prevent default form submission

                        var formData = $(this).serialize(); // Serialize form data

                        // Send form data via AJAX
                        $.ajax({
                            type: "POST",
                            url: "subscribe.php",
                            data: formData,
                            success: function(response) {
                                $('#subscribe-response').html('<p style="color: green;">' +
                                    response + '</p>');
                                $('#subscribeForm')[0].reset(); // Clear the form
                            },
                            error: function() {
                                $('#subscribe-response').html(
                                    '<p style="color: red;">An error occurred. Please try again.</p>'
                                );
                            }
                        });
                    });
                });
                </script>



            </div>

            <p class="p-s">
                <span class="white">By subscribing to our website, you agree to our <a href="#">Terms and
                        Conditions</a>. Your personal
                    information will be used in accordance with our <a href="#">Privacy Policy</a>. You
                </span>understand that we
                may collect and analyze <span class="white"> your usage data to improve our services.</span>
            </p>

        </section>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 199" class="promo-background bv-111">
            <path fill="#d44b4b" fill-opacity="1" d="M0,192L1440,64L1440,320L0,320Z"></path>
        </svg>
        <section class="promo-section" id="promo-advertise">

            <div class="promo-items">
                <div class="promo-item">
                    <div class="promo-image">
                        <img loading="lazy" class="img-f" src="assets/c1.webp" class="image-full" alt="">
                    </div>

                    <div class="promo-text">
                        <h1>Advertise</h1>
                        <p>Reach your target audience with our magazine. Our publication offers a unique opportunity to
                            showcase your brand and connect with potential customers. Contact us today to learn more
                            about our advertising options.</p>

                        <p class="promo-reach">Reach out</p>
                        <button class="btn">
                            <span class="btn-text"><a class="promo-link" href="story.php">Advertise</a></span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                </div>

                <div class="promo-item">
                    <div class="promo-image">
                        <img loading="lazy" class="img-f" src="assets/c2.webp" class="image-full" alt="">
                    </div>
                    <div class="promo-text">
                        <h1>Contribute</h1>
                        <p>Join our community and share your unique experiences! Contribute your photos, stories, and
                            insights to be featured in our magazine. We'll give you the credit you deserve and showcase
                            your work to a global audience.</p>

                        <p class="promo-reach">Reach out</p>
                        <button class="btn">
                            <span class="btn-text"><a class="promo-link" href="story.php">Share your story
                                    now</a></span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                </div>
            </div>

        </section>


        <!-- <section class="et-slide" id="advertise">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 199" class="bv-111">
                <path fill="#d44b4b" fill-opacity="1" d="M0,192L1440,64L1440,320L0,320Z"></path>
            </svg>
            <div class="advertise-items">
                <div class="advertise-item">
                    <div class="ad-item">
                        <img loading="lazy" src="assets/c1.webp" class="img-f" alt="">
                    </div>

                    <div class="ad-item">
                        <h1>
                            Advertise</h1>

                        <p>Reach your target audience with our magazine. Our publication offers a unique opportunity to
                            showcase your brand and connect with potential customers. Contact us today to learn more
                            about
                            our advertising options.</p>

                        <p class="p-reach">Reach out</p>
                        <button class="btn">
                            <span class="btn-text"><a class="a" href="story.php">Advertise</a></span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                </div>


                <div class="advertise-item">
                    <div class="ad-item">
                        <img loading="lazy" src="assets/c2.webp" class="img-f" alt="">
                    </div>
                    <div class="ad-item">
                        <h1>
                            Contribute
                        </h1>
                        <p>Join our community and share your unique experiences! Contribute your photos, stories, and
                            insights to be featured in our magazine. We'll give you the credit you deserve and showcase
                            your
                            work to a global audience.</p>

                        <p class="p-reach">Reach out</p>
                        <button class="btn">
                            <span class="btn-text"><a class="a" href="story.php">Share your story now</a></span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                </div>
            </div>

        </section> -->


        <section class="et-slide" id="patners">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="sv1">
                <path fill="#d44b4b" fill-opacity="1"
                    d="M0,128L480,320L960,192L1440,64L1440,320L960,320L480,320L0,320Z"></path>
            </svg>
            <h1>Patners</h1>
            <div class="p-logos">
                <div class="p-logo"><a href="https://topwellnessafrica.co.ke/" target="_blank"><img loading="lazy" class="p-img"
                            src="assets/l1.png" alt=""></a></div>
                <div class="p-logo"><a href="https://malshemedia.com" target="_blank"><img loading="lazy" class="p-img"
                            src="assets/l2.png" alt=""></a>
                </div>
                <div class="p-logo"><img loading="lazy" class="p-img" src="assets/l3.png" alt=""></div>
                <div class="p-logo"><a href="https://impactalentconsulting.com/" target="_blank"><img loading="lazy" class="p-img"
                            src="assets/l4.png" alt=""></a></div>
                <div class="p-logo"><img loading="lazy" class="p-img" src="assets/l5.png" alt=""></div>
                <div class="p-logo"><a href="https://www.kimisitusacco.or.ke/" target="_blank"><img loading="lazy" class="p-img"
                            src="assets/l6.png" alt=""></a></div>
                <div class="p-logo"><a href="https://jitoleegoodfriendsfoundation.org/" target="_blank"><img loading="lazy"
                            class="p-img" src="assets/l7.png" alt=""></a></div>
                <div class="p-logo"><img loading="lazy" class="p-img p-img-s" src="assets/l8.png" alt=""></div>
                <div class="p-logo"><a href="https://www.kimisitusacco.or.ke/" target="_blank"><img loading="lazy" class="p-img"
                            src="assets/l6b.png" alt=""></a></div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 318" class="sv2">
                <path fill="#d44b4b" fill-opacity=" 1"
                    d="M0,128L480,320L960,192L1440,64L1440,320L960,320L480,320L0,320Z">
                </path>
            </svg>
        </section>




        <section class="et-slide" id="feedback">
            <h1>Feedback</h1>
            <p>Have an opinion? Let us know! Your feedback will help us make our website even better.</p>

            <div class="iform" id="iform">
                <form id="feedbackForm" class="feedback-form" name="feedback" method="POST">
                    <textarea placeholder="Share your ideas here" rows="20" name="comments" id="comments" cols="40"
                        class="input-n input-feed" autocomplete="off" role="textbox" aria-autocomplete="list"
                        aria-haspopup="true"></textarea>
                    <button type="submit" class="btn btn-feed"><span class="btn-text">Submit</span>
                        <span class="btn-shine"></span></button>
                    <div id="feedback-response" style="margin-top: 0px;"></div>
                </form>

            </div>
        </section>

        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#feedbackForm').submit(function(e) {
                e.preventDefault(); // Prevent form from submitting the normal way

                var formData = $(this).serialize(); // Serialize form data

                // Send form data via AJAX
                $.ajax({
                    type: "POST",
                    url: "feedback.php",
                    data: formData,
                    success: function(response) {
                        $('#feedback-response').html('<p style="color: green;">' +
                            response + '</p>');
                        $('#feedbackForm')[0].reset(); // Clear the form
                    },
                    error: function() {
                        $('#feedback-response').html(
                            '<p style="color: red;">An error occurred. Please try again.</p>'
                        );
                    }
                });
            });
        });
        </script>


        <div id="back-top">
            <a title="Go to Top" href="#latest"> <i class="fas fa-level-up-alt"></i></a>
        </div>
        <script>
        function getIPAddress() {
            var t = new XMLHttpRequest;
            t.open("GET", "https://api.ipify.org?format=json", !0), t.onload = function() {
                var e;
                200 === t.status ? (e = JSON.parse(t.responseText).ip, document.getElementById("ipAddress").value =
                        e) :
                    console.error("Error getting IP address: " + t.status)
            }, t.send()
        }
        getIPAddress(), window.addEventListener("scroll", function() {
            var e = document.querySelector(".et-hero-tabs-container"),
                t = document.querySelector(".et-hero-tabs"),
                t = t.offsetTop + t.offsetHeight;
            window.scrollY >= t ? e.classList.add("sticky") : e.classList.remove("sticky")
        }), document.addEventListener("DOMContentLoaded", function() {
            var e = document.getElementById("menu-icon"),
                t = document.getElementById("nav-links");
            e.addEventListener("click", function() {
                t.classList.toggle("show")
            }), window.addEventListener("resize", function() {
                768 <= window.innerWidth ? t.classList.add("show") : t.classList.contains("show") || t
                    .classList
                    .remove("show")
            })
        });
        const sub_active = document.getElementById("sub_class");

        function onClick() {
            sub_active.classList.add("active")
        }
        sub_active.addEventListener("click", onClick);
        </script>




        <!-- jQuery  -->
        <script src="dflip/js/libs/jquery.min.js" type="text/javascript"></script>
        <!-- Flipbook main Js file -->
        <script src="dflip/js/dflip.min.js" type="text/javascript"></script>



        <?php include 'includes/footer.php' ?>
    </main>







</body>
