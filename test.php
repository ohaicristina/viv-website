<!DOCTYPE html>
<?php
$_viv_sess_db = new mysqli('localhost', 'root', 'Muellne1!', 'viv');
if (mysqli_connect_errno()) {
	echo "Failed to connect to DB: " . mysqli_connect_error();
}
?>
<html>
<head lang="en">
    <title>VIV: Visual Information Vase</title>
    <meta charset="utf-8">
    <link href="style/style.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <header id="banner">
        <nav>
            <ul>
                <li>About</li>
                <li>Contact</li>
            </ul>
        </nav>
    </header>
    <main id="content">
        <div class="canvas">
            <a href="upload.html"><button id="homebutton">Get started!</button></a>
        </div>
        <footer id="footer">
            <span class="copyright">Copyright &copy; 2014 Visual Information Vases</span>
        </footer>
    </main>
</div>
        <div class="row">
            <div class="large-6 columns">
                <button type="button" class="button secondary tiny postfix" onclick="applychanges()">Apply</a>
            </div>
        </div>


<script>
    function applychanges() {
	console.error("Inside function");
        var new_url = "./cgi-bin/python/WebImageImport.py";

        $.ajax({
            type: "POST",
            url: new_url,
            async: true,
            data: {chosen_pic: "1", session_id: "123412341234"}
        }).done(function(response) {
            console.error("done:" + response);
          });

    };

</script>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="js/jquery-ui-slider-pips.min.js"></script>
</body>
</html>
