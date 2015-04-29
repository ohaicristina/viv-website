<?php
$img_id = -1;
$vase_id = -1;
$selection = -1;
if(isset($_POST['vase_id'])) {
    $vase_id = $_POST['vase_id'];
    echo "vaseid: ".$vase_id."<br>";
}
if(isset($_POST['image_id'])) {
    $img_id = $_POST['image_id'];
    echo "imageid: ".$img_id."<br>";
}
if(isset($_POST['chosen_pic'])) {
    $selection = $_POST['chosen_pic'];
    echo $selection;
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
<div id="main">
    <a href="upload.html">
    <div class="button" id="previousbutton">&lt;&lt;</div>
</a>
    <div class="button" id="nextbutton" onclick="forwardToTweak()">&gt;&gt;</div>

    <div class="row">
        <div class="small-12 medium-12 large-12 columns">
            <h2>I'm performing a color palette analysis...</h2>
        </div>
    </div>

    <div class="row">
        <div class="small-12 medium-6 large-6 columns">
            <p class="top">I'm getting inspired by your image's colors to
            create your vase.</p>
        </div>
        <div class="small-12 medium-6 large-6 columns">
            <p class="top">These are the colors that stood out to me.</p>
        </div>
    </div>

    <div class="row">
        <div class="small-12 medium-6 large-6 columns">
            <div class="box">
                    <?php
                        if($selection != -1) {
                            echo '<img src="./assets/img/sample'.$selection.'.jpg">';
                        } else {
                            $_viv_sess_db = new mysqli('localhost', 'root', 'Muellne1!', 'viv');
                            if (mysqli_connect_errno()) {
                                echo "Failed to connect to DB: " . mysqli_connect_error();
                            }
                            $query = "SELECT image_data from image where image_id = '$img_id';";
                            if ($result = mysqli_query($_viv_sess_db, $query)) {
                                $image_data = -1;
                                while ($row = mysqli_fetch_row($result)) {
                                    $image_data = $row[0];
                                }
                            }
                            $imgsrc = 'data:image/jpeg;base64,'.$image_data;
                            echo '<img src="'.$imgsrc.'" alt="" />';
                        }
                    ?>
            </div>
        </div>
        <div class="small-12 medium-6 large-6 columns">
            <div class="canvas"></div>
        </div>
        <div class="small-12 medium-6 large-6 columns">
            <div class="control">
                <div class="small-5 columns">
                    <div class="tiny secondary button" id="single">Main Colors</div>
                </div>
                <div class="small-5 columns">
                    <div class="tiny secondary button" id="multi">Dominance & Salience</div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script src="js/lib/jquery/jquery-1.11.1.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="js/lib/queue/queue.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.topbar.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.equalizer.js"></script>
<script src="js/bubbles.js"></script>

<script type="text/javascript">
function forwardToTweak() {
    var postFormStr = "<form method='POST' action='/tweak.php'>\n";

    postFormStr += "<input type='hidden' name='vase_id' value='<?php echo $vase_id ?>'/>";
    postFormStr += "<input type='hidden' name='image_id' value='<?php echo $img_id ?>'/>";


    postFormStr += "</form>";

    var formElement = $(postFormStr);

    $('body').append(formElement);
    $(formElement).submit();

};
</script>

</body>
</html>
