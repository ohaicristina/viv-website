<?php
$img_id = -1;
$vase_id = -1;
if(isset($_POST['vase_id'])) {
    $vase_id = $_POST['vase_id'];
    echo "vaseid: ".$vase_id."<br>";
}
if(isset($_POST['image_id'])) {
    $img_id = $_POST['image_id'];
    echo "imageid: ".$img_id."<br>";
}
?>

<html>
<head lang="en">
    <title>VIV: Visual Information Vase</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/foundation.css"/>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/normalize.min.css"/>
    <link href="style/style.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900"
          rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <h1><a href="index.html">Viv</a></h1>
            </li>
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
            <!-- Right Nav Section -->
            <ul class="right">
                <li><a href="about.html">ABOUT</a></li>
                <li><a href="#">CONTACT US</a></li>
            </ul>
        </section>
    </nav>
</header>
<div id="main" class="row">
    <div class="row">
        <a href="tweak.php">
        <button id="previousbutton">&lt;&lt;</button>
    </a>
    </div>

    <div class="small-6 medium-6 large-12 columns">

        <div class="large-6 columns">
            <h2>Save your 3D vase!</h2>

            <div class="row collapse">
            <div class="small-12 medium-12 large-8 columns">
                <div class="box"></div>
            </div>
            <div class="bottom">
                <a href="download.html">
                <div class="button">Download</div></a>
            </div>
        </div>
    </div>
    <div class="large-6 columns">
        <h2>Share it!</h2>

        <div class="addthis_sharing_toolbox"></div>
    </div>
        <div class="large-6 columns">
            <h2></h2>
        </div>
        <div class="large-6 columns">
            <h2 class="bottom">Try another image!</h2>
            <a href="upload.html">
            <div class="button">Start Over</div></a>
        </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.topbar.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.equalizer.js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5447c09e7b9b877a"
        async="async"></script>


</body>
</html>
