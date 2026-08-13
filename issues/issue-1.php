<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/f65faecb5f.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
 <!-- Flipbook StyleSheet -->
 <link href="../dflip/css/dflip.min.css" rel="stylesheet" type="text/css">
  <?php include '../style.css'?>
  <!-- Icons Stylesheet -->
  <link href="../dflip/css/themify-icons.min.css" rel="stylesheet" type="text/css">


<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
    <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  	<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../magnify/magnify.min.css">

    <section class="et-hero-tabs-x">
  <div class="et-hero-tabs-container-x fixed-bottom">
        <a class="logo" href="#">Turning Point</a>
        <div class="menu-icon" id="menu-icon">&#9776;</div>
        <div class="et-right" id="nav-links">
            <a class="et-hero-tab" href="../index.php#tab-es6">Home</a>
            <a class="et-hero-tab" href="../index.php#collection" id="nv">Library</a>
            <a class="et-hero-tab" href="../index.php#subscribe" id="nv">Subscribe</a>
            <a class="et-hero-tab" href="../index.php#advertise" id="nv">Advertise</a>
        </div>
        <span class="et-hero-tab-slider"></span>
    </div>
    
    
  </section>

  <section class="et-slide" id="about">
    <?php include '../includes/sidebar.php' ?>
    <div class="container "id="lates">

<div class="row">
  <div class="col-xs-12">
    <h1>Latest Issue in our list of publications</h1>

  </div>
  <div class="col-xs-12" style="padding-bottom:30px">
    <!--Normal FLipbook-->
    <div class="_df_book" height="500" webgl="true" backgroundcolor="transparent"
            source="../assets/magazine.pdf"
            id="df_manual_book">
    </div>

  </div>
</div>
</div>
    </section>


 <?php include '../includes/footer.php' ?>
 <script>
 document.getElementById('menu-icon').addEventListener('click', function() {
    var navLinks = document.getElementById('nav-links');
    if (navLinks.style.display === 'flex') {
        navLinks.style.display = 'none';
    } else {
        navLinks.style.display = 'flex';
    }
});

// Add event listeners to each .et-hero-tab to close the menu when clicked
var heroTabs = document.querySelectorAll('.et-hero-tab');
heroTabs.forEach(function(tab) {
    tab.addEventListener('click', function() {
        var navLinks = document.getElementById('nav-links');
        navLinks.style.display = 'none';
    });
});


</script>

<!-- jQuery  -->
<script src="../dflip/js/libs/jquery.min.js" type="text/javascript"></script>
<!-- Flipbook main Js file -->
<script src="../dflip/js/dflip.min.js" type="text/javascript"></script>