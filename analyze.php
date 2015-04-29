<?php
$img_id = -1;
$vase_id = -1;
$selection = -1;
if(isset($_POST['vase_id'])) {
    $vase_id = $_POST['vase_id'];
//    echo "vaseid: ".$vase_id."<br>";
}
if(isset($_POST['image_id'])) {
    $img_id = $_POST['image_id'];
//    echo "imageid: ".$img_id."<br>";
}
if(isset($_POST['chosen_pic'])) {
    $selection = $_POST['chosen_pic'];
//    echo $selection;
}
$_viv_sess_db = new mysqli('localhost', 'root', 'Muellne1!', 'viv');
if (mysqli_connect_errno()) {
	echo "Failed to connect to DB: " . mysqli_connect_error();
}

$query = "SELECT distinct_colors FROM image, vase WHERE vase_id = '$vase_id' and vase.image_id = image.image_id;";
if ($result = mysqli_query($_viv_sess_db, $query)) {
		
    while ($row = mysqli_fetch_row($result)) {
        $color_string = $row[0];
    }
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
            <div class="one-time">
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
            <canvas id="ColorCanvas" height="400" width="800">
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
    var selection = <?php echo $selection ?>;
    var postFormStr = "<form method='POST' action='/tweak.php'>\n";

    postFormStr += "<input type='hidden' name='vase_id' value='<?php echo $vase_id ?>'/>";
    postFormStr += "<input type='hidden' name='image_id' value='<?php echo $img_id ?>'/>";
    if (selection != -1) {
        postFormStr += "<input type='hidden' name='chosen_pic' value='<?php echo $selection ?>'></input>";
    }

    postFormStr += "</form>";

    var formElement = $(postFormStr);

    $('body').append(formElement);
    $(formElement).submit();

};
</script>
<script>
var c=document.getElementById("ColorCanvas");
var ctx=c.getContext("2d");
var numColors = 8;
var startX = 0;
var colorWidth = 50;
var color = "#FF0000";

var colorString = '<?php echo $color_string ?>';
var colorArray = colorString.split('&');
for(var i=0;i<colorArray.length-1;i++)
{
    var color = colorArray[i].split(',').map(Number);
    var result = d3.lab(color[0],color[1],color[2]);
    result = d3.rgb(result);
    ctx.beginPath();
    ctx.fillStyle=result.toString();
    ctx.fillRect(startX, 0, colorWidth, 400);
    startX += colorWidth;
}
</script>

</body>
</html>
