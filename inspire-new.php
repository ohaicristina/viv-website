<?php
$sess_return = session_start();

$ses_id = session_id();
if($sess_return && !empty($ses_id)) {
    echo $ses_id."<br>";
}
else
{
    $ses_id = -1;
    echo $ses_id."<br>";
}

$img = -1;
$selection = -1;
if(isset($_POST['img_data'])) {
    $img = $_POST['img_data'];
    echo $img;
}
if(isset($_POST['chosen_pic'])) {
    $selection = $_POST['chosen_pic'];
    echo $selection;
}
?>


<!DOCTYPE html>
<html>
<head lang="en">
    <title>VIV: Visual Information Vase</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/foundation.css"/>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/normalize.min.css"/>
    <link href="style/style.css" rel="stylesheet"/>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,900,900italic,100,100italic'
          rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700'
          rel='stylesheet' type='text/css'>
</head>
<body>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<div id="main">
  <div class="row">
    <div class="small-12 columns">
      <h2>I'm performing a color palette analysis...</h2>
      <div class="row">
        <div class="small-12 medium-6 large-6 columns">
          <div class="box" id="uploadedimage">
              <?php
                  if($selection != -1)
                  {
                      echo '<img src="./assets/img/sample'.$selection.'.jpg">';
                  } else {
                      $imgsrc = 'data:image/jpeg;base64,'.$img;
                      echo '<img src="'.$imgsrc.'" alt="" />';
                  }
              ?>
          </div>
        </div>
        <div class="small-12 medium-6 columns">
          <img class="loading" src="./assets/img/loading.gif"/>
        </div>
      </div>
    </div>
    <div class="row">
      <a href="upload.html">
        <button type="button" class="button tiny" id="choosePredef">&#60;&#60; BACK</button>
      </a>
    </div>
  </div>
</div>

<script type="text/javascript" src="js/bubbles.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.topbar.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.equalizer.js"></script>

<script type="text/javascript">
function analyzeImage(img) {
    console.error("sending image to server");
    var new_url = "http://localhost:8080/cgi-bin/python/WebImageImport.py";

    console.error("session:" + "<?php echo $ses_id ?>");
    $.ajax({
        type: "POST",
        url: new_url,
        async: true,
        data: {session_id: "<?php echo $ses_id ?>", image: img}
    }).done(function(response) {
        console.error("done:" + response);
        var vaseid = $(response).find('vaseid').text();
        var imageid = $(response).find('imageid').text();
        forwardToAnalyze(vaseid, imageid);
      });
};

function analyzePredef(chosen_pic) {
    console.error("sending image to server");
    var new_url = "http://localhost:8080/cgi-bin/python/WebImageImport.py";

    console.error("session:" + "<?php echo $ses_id ?>");
    $.ajax({
        type: "POST",
        url: new_url,
        async: true,
        data: {session_id: "<?php echo $ses_id ?>", chosen_pic: chosen_pic}
    }).done(function(response) {
        console.error("done:" + response);
        var vaseid = $(response).find('vaseid').text();
        var imageid = $(response).find('imageid').text();
        forwardToAnalyze(vaseid, imageid);
      });
};

if(<?php echo $selection ?> == -1) {
    analyzeImage("<?php echo $img ?>");
} else {
    analyzePredef("<?php echo $selection ?>");
}

function forwardToAnalyze(vase_id, image_id) {
    console.error("vase: " + vase_id + ", image: " + image_id);
    var postFormStr = "<form method='POST' action='/analyze.php'>\n";

    postFormStr += "<input type='hidden' name='vase_id' value='" + vase_id + "'></input>";
    postFormStr += "<input type='hidden' name='image_id' value='" + image_id + "'></input>";
    postFormStr += "<input type='hidden' name='chosen_pic' value='<?php echo $selection ?>'></input>";

    postFormStr += "</form>";

    var formElement = $(postFormStr);

    $('body').append(formElement);
    $(formElement).submit();
};
</script>

</body>
</html>
