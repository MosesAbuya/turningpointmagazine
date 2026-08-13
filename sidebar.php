<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<style>
/* Floating Social Media Bar Style Starts Here */

.fl-fl {
    border: 1px solid black;
    background: transparent;
    text-transform: uppercase;
    margin-top: 10px;
    letter-spacing: 3px;
    padding: 4px;
    width: 190px;
    position: fixed;
    right: -150px;
    z-index: 1000;
    -webkit-transition: all .25s ease;
    -moz-transition: all .25s ease;
    -ms-transition: all .25s ease;
    -o-transition: all .25s ease;
    transition: all .25s ease;
}

.fa {
    font-size: 20px;
    color: #1f2b7b;
    padding: 10px 0;
    width: 40px;
    margin-left: 8px;
}

.fl-fl:hover {
    right: 0;
}

.fl-fl a {
    font-size: 1rem;
    color: #1f2b7b !important;
    text-decoration: none;
    text-align: center;
    line-height: 43px !important;
    vertical-align: top !important;
}

.float-fb {
    top: 160px;
}

.float-tw {
    top: 215px;
}

.float-gp {
    top: 270px;
}

.float-rs {
    top: 325px;
}

.float-ig {
    top: 380px;
}

.float-pn {
    top: 435px;
}

.float-sm {
    top: 0;
}


/* Tablet desktop :768px. */
@media (min-width: 768px) and (max-width: 991px) {}


/* small mobile :320px. */
@media (max-width: 767px) {}

/* Large Mobile :480px. */
@media only screen and (min-width: 480px) and (max-width: 767px) {
    .container {
        width: 450px
    }

    .float-fb {
        top: 190px;
    }

    .float-tw {
        top: 245px;
    }

    .float-gp {
        top: 300px;
    }

    .float-rs {
        top: 355px;
    }

    .float-ig {
        top: 410px;
    }

    .float-pn {
        top: 465px;
    }

    .float-sm {
        top: 0;
    }

}
</style>


<!-- Floating Social Media bar Starts -->
<div class="float-sm">
    <div class="fl-fl float-fb">
        <i class="fa fa-facebook"></i>
        <a href="" target="_blank"> Like us!</a>
    </div>
    <div class="fl-fl float-tw">
        <i class="fa fa-twitter"></i>
        <a href="" target="_blank">Follow us!</a>
    </div>
    <div class="fl-fl float-gp">
        <i class="fa fa-youtube"></i>
        <a href="" target="_blank">Subscribe</a>
    </div>
    <div class="fl-fl float-rs">
        <i class="fa fa-linkedin"></i>
        <a href="" target="_blank">Follow us!</a>
    </div>
    <div class="fl-fl float-ig">
        <i class="fa fa-instagram"></i>
        <a href="" target="_blank">Follow us!</a>
    </div>

</div>
<!-- Floating Social Media bar Ends -->