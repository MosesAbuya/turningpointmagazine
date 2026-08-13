<div class="et-hero-tabs-container-x">
    <a class="logo" href="#">Turning Point</a>
    <div class="search-module">
        <input type="text" placeholder="Search..." class="search-input" />
        <button class="search-button"><i class="fas fa-search" id="fa"></i></button>
    </div>
    <div class="menu-icon" id="menu-icon">&#9776;</div>
    <div class="et-right et-right-1" id="nav-links">
        <a class="et-hero-tab" href="index.php#latest">Home</a>
        <a class="et-hero-tab" href="about.php" id="nv-1">About</a>
        <a class="et-hero-tab" href="contact.php" id="nv">Contact</a>
        <a class="et-hero-tab" href="story.php" id="nv">Contribute</a>
    </div>
    <span class="et-hero-tab-slider"></span>
</div>



<style>
@font-face {
    font-family: florania;
    src: url(assets/fonts/florania.otf) format("opentype");
}

#b-h1 {
    font-size: 5rem;
    font-weight: 900;
    font-family: florania;
    color: red;
}

#b-h1,
.b-h3 {
    text-align: center;
}

.b-h3 {
    font-size: 1.5rem;
}

.et-hero-tabs-container-x {
    display: flex;
    flex-direction: row;
    /* position: sticky; */
    justify-content: space-between;
    align-items: center;
    top: 0;
    width: 100%;
    height: 70px;
    transition: all 0.5s ease;
    z-index: 10000;
    border-radius: 30px;
    background-color: #f7f7f7;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.344);

    .logo {
        font-size: 1.4rem;
        width: fit-content;
    }
}

.et-hero-tabs-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    position: absolute;
    bottom: 0;
    width: 80%;
    height: 70px;
    z-index: 100;
    transition: all 1s ease;
    border-radius: 10px;
    background-color: #fff;
}

.et-hero-tabs-container.sticky {
    width: 80%;
    position: fixed;
    top: 0;
    bottom: auto;
    border-radius: 20px;
    background-color: #fff;
    transition: all 0.5s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.344);

    .logo {
        font-size: 1.4rem;
    }
}

.menu-icon {
    display: none;
    font-size: 24px;
    cursor: pointer;
    color: rgb(0, 0, 0);
}

.logo {
    font-family: "florania";
    font-size: 2.5rem;
    display: flex;
    margin-left: 20px;
    flex: 2;
    letter-spacing: 0.1px;
    font-weight: 900;
    color: red;
    transition: all 0.5s ease;
}

.et-right {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-right: 10px;
    width: 60%;
}

#nav-links {
    display: none;
}

#nav-links.show {
    display: flex;
}

.et-hero-tabs {
    background-image: url(assets/counter3.jpg);
    background-size: cover;
    background-position: center center;
    transition: all 0.5s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100dvh;
    position: relative;
    text-align: center;
    padding: 0 2em;
}

.et-hero-tabs h1 {
    font-size: 2rem;
    margin: 0;
    letter-spacing: 0.1rem;
    margin-top: 20px;
}

.et-hero-tabs h3 {
    font-size: 1rem;
    letter-spacing: 0.1rem;
    opacity: 0.6;
}

nav {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    align-self: center;
    width: fit-content;
    padding: 20px;
    margin: auto;
    position: sticky;
    top: 0;
    z-index: 1000;
    padding-bottom: 50px;
}

.et-hero-tab {
    display: flex;
    justify-content: center;
    align-items: center;

    flex: 1;
    color: #000;
    letter-spacing: 0.1rem;
    transition: all 0.5s ease;
    font-size: 0.4rem;
    background: transparent;

    &:hover {
        color: rgb(255, 0, 0);
        font-weight: 900;
        transition: all 0.5s ease;
        width: 100%;
    }
}

.et-hero-tab-slider {
    position: absolute;
    bottom: 0;
    width: 0;
    height: 6px;
    background: #66b1f1;
    transition: left 0.3s ease;
}

.nav-item {
    position: relative;
    display: inline-block;
}

.nav-item:hover .dropdown-menu {
    display: block;
    border-radius: 10px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    border-color: #fff;
    min-width: 180px;
    z-index: 1;
    list-style: none;
    padding: 0;
    margin: 0;
    border-radius: 10px;
    transition: all 1s ease;
    background-color: #fff;
}

.dropdown-item {
    color: #000;
    padding: 8px 10px;
    text-decoration: none;
    display: block;
    transition: all 1s ease;
}

.dropdown-item:hover {
    background-color: #da2020;
    color: white;
    border-radius: 10px;
    transition: all 0.1s ease;
}

@media (min-width: 768px) {
    #nav-links {
        display: flex !important;
    }

    .tab-1 {
        height: fit-content;
    }
}

.nav-item {
    position: relative;
    display: inline-block;
}

.et-hero-tabs-container {
    width: 100%;
}

.et-hero-tabs-container-x {
    border-radius: 0;
}

.et-hero-tabs-container.sticky {
    width: 100%;
    border-radius: 0;

    .logo {
        font-size: 1.3rem;
    }
}

.nav-link {
    text-decoration: none;
    color: #000;
    padding: 10px 15px;
    display: block;
}

#nv {
    padding-right: 8px;
}

#nv-1 {
    margin-right: 2px;
}

@media (min-width: 800px) {

    .et-hero-tabs,
    .et-slide {
        h1 {
            font-size: 3rem;
        }

        h3 {
            font-size: 1rem;
        }
    }

    .et-hero-tab {
        font-size: 1rem;
    }

    .logo {
        font-size: 30px;
    }
}

@media (max-width: 768px) {
    .et-hero-tabs {
        background-image: none;
    }

    .et-right {
        background-color: #fff;
        display: none;
        flex-direction: column;
        width: 90%;
        position: absolute;
        bottom: -230px;
        border-color: #fff;
        z-index: 100;
        transition: all 1s ease;
        right: 0;
    }

    .logo {
        font-size: 1.3rem;
    }

    .et-right-1 {
        bottom: -170px;
    }

    .et-hero-tabs {
        height: 8dvh;
    }

    .et-hero-tab {
        margin: 10px 0;
        border: 2px #f66;
    }

    .menu-icon {
        display: block;
        margin-right: 20px;

        &:hover {
            transform: scale(1.2);
            transition: 0.5s;
        }
    }

    .logo {
        margin-left: 20px;
    }
}
</style>