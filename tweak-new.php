<?php
$img_id = -1;
$vase_id = -1;
$tweaked_vase_id = -1;
$selection = -1;
if(isset($_POST['vase_id'])) {
    $vase_id = $_POST['vase_id'];
    echo "vaseid: ".$vase_id."<br>";
}
if(isset($_POST['tweaked_vase_id'])) {
    $tweaked_vase_id = $_POST['tweaked_vase_id'];
    echo "tweakedvaseid: ".$tweaked_vase_id."<br>";
}
if(isset($_POST['image_id'])) {
    $img_id = $_POST['image_id'];
    echo "imageid: ".$img_id."<br>";
}
if(isset($_POST['chosen_pic'])) {
    $selection = $_POST['chosen_pic'];
    echo $selection;
}

$left_vase_id = $vase_id;
$right_vase_id = $tweaked_vase_id;

$_viv_sess_db = new mysqli('localhost:3306', 'vivuser', 'aq4s*3.4P', 'viv');
if (mysqli_connect_errno()) {
	echo "Failed to connect to DB: " . mysqli_connect_error();
}

$query = "SELECT xml_data, num_base_pts FROM vase WHERE vase_id = '$left_vase_id';";
	if ($result = mysqli_query($_viv_sess_db, $query)) {
		
    		while ($row = mysqli_fetch_row($result)) {
	        	$left_vase_xml = $row[0];
			$left_base_pts = $row[1];
	    	}
	} else {
		
	}

	if ($tweaked_vase_id != -1) {
	$query = "SELECT xml_data, num_base_pts FROM vase WHERE vase_id = '$right_vase_id';";
	if ($result = mysqli_query($_viv_sess_db, $query)) {
    	while ($row = mysqli_fetch_row($result)) {
        	$right_vase_xml = $row[0];
		$right_base_pts = $row[1];
    	}
	}
} else {
$right_vase_xml = $left_vase_xml;
$right_base_pts = $left_base_pts;
	}
	// echo "left vase:".$left_vase_xml;
	$_viv_sess_db->close();
?>

<html>
<head lang="en">
    <title>VIV: Visual Information Vase</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/foundation.css"/>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/css/normalize.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="style/jquery-ui-slider-pips.css">
    <style>#weight, #temp, #act, #hardness {
        margin: 10px;
    }    </style>
    <link href="style/style.css" rel="stylesheet"/>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,900,900italic,100,100italic'
          rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700'
          rel='stylesheet' type='text/css'>
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
                <li><a href="contact.html">CONTACT US</a></li>
            </ul>
        </section>
    </nav>
</header>
<div id="main" class="row">
    <div class="row">
        <a href="analyze.php" class="button" id="previousbutton">&lt;&lt;</a>
    </div>

    <div class="small-12 medium-12 large-12 columns">
        <div class="row collapse">
            <div class="row">
                <h2>I sculpted a vase for you! <br/>It's based on these 4 feelings I had about your image.
                </h2>
            </div>
            <div class="top">
                <div class="row">
                    <div class="row collapse">
                        <div class="large-4 columns">
                            <div class="row collapse">
                                <div class="large-9 columns">
                                    <p>Do you agree that this vase feels like your image?</p>
                                </div>
                                <div class="small-2 columns">
                                    <button type="button" class="button tiny postfix" onclick="shareorig()">Yes &gt;&gt;</a>
                                </div>
                            </div>
                        </div>
                        <div class="large-6 columns">
                            <div class="large-8 columns end">
                                <p>Not quite? Sculpt with me to show how the image feels to you.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-2 medium-2 large-2 columns">
        <div class="tweaklabel">
            <p>Weight: 13%</p>
            <?xml version="1.0" encoding="utf-8"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="86.476px" height="19.223px" viewBox="0 0 86.476 19.223" enable-background="new 0 0 86.476 19.223" xml:space="preserve">
<g opacity="0.68">
	<path fill="#707B86" d="M19.189,19.223c-6.396,0-12.792,0-19.188,0c0.001-0.038,0.001-0.077,0.002-0.117
		c0.001-0.025,0.011-0.044,0.042-0.025c0.006-0.02,0.011-0.034,0.014-0.05c0.145-0.573,0.294-1.148,0.432-1.722
		c0.31-1.292,0.617-2.585,0.922-3.877c0.324-1.379,0.645-2.76,0.968-4.139c0.282-1.197,0.563-2.393,0.844-3.59
		c0.01-0.043,0.025-0.055,0.069-0.055c0.644,0.002,1.291,0.002,1.933,0c0.315,0,0.631-0.006,0.946-0.013
		c0.076-0.004,0.151-0.018,0.225-0.027c0.036-0.005,0.054-0.03,0.047-0.068C6.441,5.519,6.441,5.499,6.435,5.483
		C6.391,5.361,6.354,5.238,6.302,5.12C6.129,4.748,6.049,4.354,6.02,3.949c-0.026-0.334,0-0.665,0.066-0.994
		C6.171,2.533,6.312,2.13,6.545,1.763c0.176-0.277,0.405-0.506,0.64-0.733c0.188-0.178,0.384-0.347,0.613-0.478
		c0.399-0.222,0.821-0.374,1.271-0.452c0.036-0.007,0.073-0.018,0.106-0.026c0-0.003-0.001-0.008-0.001-0.013
		c-0.02-0.003-0.039-0.008-0.059-0.013c0-0.004,0-0.009,0-0.014c0.338-0.045,0.672-0.05,1.009,0.014
		c-0.02,0.005-0.039,0.01-0.061,0.015c0.033,0.021,0.068,0.03,0.102,0.038c0.173,0.04,0.346,0.072,0.515,0.122
		c0.548,0.162,1.015,0.458,1.427,0.849c0.484,0.464,0.822,1.019,1.002,1.668c0.063,0.229,0.076,0.464,0.086,0.7
		c0.012,0.265,0.012,0.53-0.02,0.792c-0.04,0.341-0.142,0.665-0.281,0.979c-0.04,0.091-0.071,0.188-0.098,0.286
		c-0.023,0.086-0.006,0.109,0.082,0.117c0.152,0.015,0.306,0.026,0.458,0.028c0.893,0.003,1.79,0.004,2.683,0.006
		c0.024,0,0.046,0,0.069,0c0.008,0.03,0.013,0.058,0.019,0.083c0.113,0.509,0.229,1.018,0.341,1.526
		c0.417,1.864,0.831,3.728,1.253,5.591c0.436,1.92,0.88,3.841,1.321,5.76c0.035,0.149,0.077,0.298,0.115,0.45
		c0.016-0.013,0.026-0.021,0.034-0.027c0.005,0.001,0.01,0.002,0.015,0.002C19.189,19.098,19.189,19.16,19.189,19.223z
		 M10.664,8.543c-0.564,0-1.123,0-1.682,0c0,2.441,0,4.88,0,7.319c0.562,0,1.12,0,1.685,0c0-0.249,0-0.496,0-0.744
		c0.007,0.004,0.011,0.004,0.012,0.006c0.01,0.01,0.017,0.021,0.025,0.03c0.182,0.229,0.391,0.431,0.644,0.581
		c0.283,0.166,0.59,0.245,0.916,0.263c0.519,0.029,0.994-0.09,1.401-0.422c0.48-0.391,0.756-0.904,0.889-1.501
		c0.076-0.35,0.098-0.705,0.079-1.062c-0.018-0.32-0.066-0.633-0.168-0.937c-0.151-0.446-0.394-0.836-0.755-1.145
		c-0.32-0.275-0.69-0.424-1.112-0.456c-0.377-0.028-0.74,0.018-1.086,0.176c-0.327,0.149-0.586,0.384-0.809,0.664
		c-0.01,0.014-0.021,0.025-0.039,0.05C10.664,10.413,10.664,9.477,10.664,8.543z M11.748,3.671c-0.004,0-0.006,0-0.009,0
		c0-0.059,0.003-0.116,0-0.174c-0.011-0.129-0.002-0.262-0.034-0.387c-0.04-0.144-0.105-0.281-0.176-0.415
		c-0.487-0.931-1.6-1.456-2.694-1.024C7.884,2.047,7.315,3.062,7.493,4.074c0.209,1.178,1.314,1.855,2.345,1.752
		c0.539-0.054,0.992-0.302,1.37-0.68c0.173-0.171,0.28-0.388,0.382-0.604c0.053-0.113,0.104-0.23,0.123-0.352
		C11.741,4.019,11.739,3.844,11.748,3.671z M5.673,15.863c0.563,0,1.122,0,1.682,0c0-2.441,0-4.881,0-7.321
		c-0.562,0-1.121,0-1.682,0C5.673,10.982,5.673,13.421,5.673,15.863z"/>
	<path fill="#707B86" d="M12.896,13.233c0,0.29-0.025,0.576-0.127,0.85c-0.095,0.252-0.243,0.463-0.491,0.586
		c-0.329,0.169-0.941,0.179-1.274-0.228c-0.149-0.182-0.238-0.391-0.279-0.62c-0.086-0.438-0.086-0.875,0.03-1.307
		c0.071-0.257,0.193-0.48,0.412-0.64c0.222-0.16,0.475-0.201,0.737-0.18c0.471,0.042,0.743,0.321,0.887,0.749
		C12.873,12.701,12.896,12.967,12.896,13.233z"/>
</g>
<g opacity="0.3">
	<path fill="#707B86" d="M41.113,13.379c0,0.771-0.63,1.4-1.4,1.4h-8.641c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h8.641c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.3">
	<path fill="#707B86" d="M86.476,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h54.004c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
</svg>

            <p>Temperature: 71%</p>
            <?xml version="1.0" encoding="utf-8"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="86.476px" height="19.223px" viewBox="0 0 86.476 19.223" enable-background="new 0 0 86.476 19.223" xml:space="preserve">
<g opacity="0.3">
	<path fill="#707B86" d="M64.113,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h31.641c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.3">
	<path fill="#707B86" d="M86.476,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h54.004c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.68">
	<g id="Layer_1_40_">
		<g>
			<path fill="#707B86" d="M14.695,10.588c-0.121-0.919-0.369-1.803-0.698-2.631c-0.329-0.831-0.745-1.605-1.206-2.333
				c-0.465-0.728-0.982-1.407-1.542-2.044c-0.281-0.316-0.568-0.628-0.874-0.926c-0.152-0.149-0.307-0.296-0.469-0.444L9.659,1.988
				L9.529,1.876L9.369,1.744c0,0-0.953-0.875-2.477-1.759c0.24,1.764-1.522,4.357-1.619,4.55c-0.113,0.216-0.268,0.468-0.44,0.74
				L4.257,6.144C3.846,6.755,3.409,7.427,3.008,8.177C2.812,8.556,2.619,8.951,2.456,9.373c-0.166,0.421-0.308,0.866-0.411,1.33
				c-0.105,0.464-0.167,0.948-0.177,1.436c-0.01,0.487,0.032,0.978,0.13,1.446c0.097,0.471,0.245,0.92,0.434,1.335
				c0.188,0.416,0.409,0.804,0.663,1.152c0.256,0.348,0.519,0.674,0.813,0.964c0.293,0.289,0.589,0.561,0.91,0.798
				c0.312,0.247,0.645,0.46,0.978,0.662c0.169,0.099,0.342,0.194,0.513,0.284c0.172,0.088,0.348,0.168,0.526,0.247l-0.507-1.028
				c-0.164-0.336-0.318-0.67-0.458-1.001c-0.28-0.662-0.506-1.314-0.631-1.938c-0.064-0.311-0.098-0.613-0.111-0.901
				c-0.01-0.289,0.003-0.562,0.043-0.822c0.081-0.521,0.251-0.984,0.499-1.449c0.125-0.232,0.268-0.465,0.429-0.699
				c0.163-0.234,0.336-0.474,0.532-0.715c0.189-0.244,0.401-0.487,0.618-0.744l0.686-0.796c0.24-0.278,0.487-0.571,0.738-0.894
				C8.687,8.023,8.7,8.005,8.713,7.988c0.092,0.108,0.182,0.217,0.271,0.327C9.39,8.814,9.76,9.33,10.09,9.853
				c0.325,0.525,0.605,1.061,0.829,1.608c0.219,0.549,0.377,1.108,0.461,1.694c0.076,0.585,0.083,1.199-0.018,1.839
				c-0.019,0.162-0.055,0.322-0.086,0.486c-0.039,0.162-0.079,0.326-0.128,0.491c-0.086,0.335-0.215,0.661-0.346,1.002
				c-0.069,0.168-0.142,0.337-0.22,0.506c-0.074,0.173-0.16,0.341-0.245,0.509c-0.167,0.346-0.359,0.681-0.547,1.038
				c0.374-0.143,0.734-0.318,1.087-0.52c0.349-0.202,0.689-0.431,1.013-0.689c0.327-0.257,0.633-0.546,0.923-0.862
				s0.553-0.667,0.793-1.041c0.239-0.376,0.447-0.779,0.615-1.208c0.168-0.426,0.304-0.872,0.395-1.331
				C14.796,12.459,14.817,11.505,14.695,10.588z"/>
		</g>
	</g>
</g>
</svg>

            <p>Activity: 93%</p>
            <?xml version="1.0" encoding="utf-8"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="86.476px" height="19.223px" viewBox="0 0 86.476 19.223" enable-background="new 0 0 86.476 19.223" xml:space="preserve">
<g opacity="0.3">
	<path fill="#707B86" d="M76.513,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h44.041c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.3">
	<path fill="#707B86" d="M86.476,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h54.004c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.68">
	<path fill-rule="evenodd" clip-rule="evenodd" fill="#707B86" d="M9.371-0.029c1.232,3.887,2.465,7.775,3.698,11.662
		c0.195,0.059,0.39,0.116,0.585,0.174c1.027-1.81,2.054-3.621,3.317-5.847c0.724,1.208,1.346,2.061,1.773,3.002
		c0.648,1.425,1.614,2.163,3.215,1.963c0.281-0.035,0.571,0.003,0.857,0.02c0.829,0.051,1.956-0.149,1.919,1.097
		c-0.034,1.209-1.129,1.022-1.972,1.023c-3.247,0.001-3.247,0.001-5.712-2.975c-0.702,1.528-1.338,2.866-1.937,4.223
		c-0.62,1.402-1.203,2.819-2.026,4.758c-1.378-4.259-2.603-8.046-3.825-11.832c-0.12-0.027-0.239-0.054-0.357-0.081
		c-0.506,1.466-1.075,2.916-1.5,4.405c-0.317,1.119-0.903,1.592-2.081,1.53c-1.252-0.065-2.52,0.116-3.766,0.011
		c-0.501-0.041-0.963-0.557-1.443-0.856c0.408-0.382,0.812-0.767,1.224-1.144c0.035-0.032,0.112-0.033,0.167-0.024
		c3.129,0.474,4.183-1.372,4.865-4.022c0.622-2.411,1.66-4.716,2.516-7.068C9.05-0.017,9.21-0.024,9.371-0.029z"/>
</g>
</svg>

            <p>Hardness: 48%</p>
            <?xml version="1.0" encoding="utf-8"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="86.476px" height="19.223px" viewBox="0 0 86.476 19.223" enable-background="new 0 0 86.476 19.223" xml:space="preserve">
<g opacity="0.3">
	<path fill="#707B86" d="M53.7,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797c0-0.771,0.63-1.4,1.4-1.4
		H52.3c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<g opacity="0.3">
	<path fill="#707B86" d="M86.476,13.379c0,0.771-0.63,1.4-1.4,1.4H31.072c-0.771,0-1.4-0.63-1.4-1.4v-1.797
		c0-0.771,0.63-1.4,1.4-1.4h54.004c0.771,0,1.4,0.63,1.4,1.4V13.379z"/>
</g>
<path opacity="0.68" fill="#707B86" d="M0.114,17.454c0.705-0.699,1.41-1.397,2.116-2.097c2.621-2.605,5.244-5.211,7.866-7.818
	c0.026-0.027,0.05-0.062,0.082-0.083c0.108-0.073,0.111-0.161,0.033-0.251c-0.12-0.139-0.086-0.236,0.041-0.362
	c0.505-0.503,1-1.015,1.49-1.53c0.159-0.165,0.316-0.337,0.334-0.586c0.014-0.195-0.031-0.376-0.17-0.512
	c-0.449-0.438-0.89-0.886-1.361-1.3c-1.446-1.271-3.126-2.123-4.95-2.707C5.557,0.195,5.521,0.18,5.482,0.165
	c0.02-0.069,0.037-0.131,0.054-0.194c0.046,0,0.09,0,0.136,0C6.043,0.05,6.418,0.116,6.787,0.209
	c2.44,0.608,4.661,1.653,6.504,3.391c1.82,1.716,3.606,3.467,5.407,5.204c0.025,0.023,0.056,0.039,0.098,0.069
	c-0.639,0.671-1.26,1.322-1.895,1.99c-1.093-1.074-2.179-2.142-3.3-3.243c-0.22,0.232-0.438,0.464-0.658,0.691
	c-0.184,0.188-0.377,0.37-0.558,0.563c-0.081,0.088-0.157,0.12-0.256,0.042c-0.105-0.084-0.178-0.053-0.269,0.038
	c-2.183,2.174-4.367,4.345-6.551,6.517c-1.157,1.15-2.315,2.303-3.472,3.454c-0.04,0.039-0.068,0.09-0.127,0.168
	c-0.266-0.276-0.502-0.529-0.746-0.773c-0.28-0.281-0.568-0.556-0.852-0.832C0.114,17.476,0.114,17.467,0.114,17.454z"/>
</svg>

        </div>
    </div>

    <div class="small-12 medium-8 large-8 columns">
        <canvas id="viv-canvas" style="border: none;" width="600" height="400"></canvas>
    </div>
    <div class="small-2 medium-2 large-2 columns">
        <p>Weight</p>
        <span class="sliderLabelLeft">Lighter</span>
        <span class="sliderLabelRight">Heavier</span>

        <div class="control">
            <div id="weight"></div>
        </div>
        <div class="control">
            <p>Temperature</p>
            <span class="sliderLabelLeft">Cooler</span>
            <span class="sliderLabelRight">Warmer</span>

            <div id="temp"></div>
        </div>
        <div class="control">
            <p>Activity</p>
            <span class="sliderLabelLeft">Passive</span>
            <span class="sliderLabelRight">Active</span>

            <div id="act"></div>
        </div>
        <div class="control">
            <p>Hardness</p>
            <span class="sliderLabelLeft">Softer</span>
            <span class="sliderLabelRight">Harder</span>


            <div id="hardness"></div>
        </div>
        <div class="row">
            <div class="large-6 columns">
                <button type="button" class="button secondary tiny postfix" onclick="applychanges()">Apply</a>
            </div>
        </div>
        <div class="row">
            <div class="large-8 columns">
                <button type="button" class="button tiny postfix" onclick="sharenew()">Accept &gt;&gt;</a>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.topbar.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.3/js/foundation/foundation.equalizer.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="js/jquery-ui-slider-pips.min.js"></script>
<script>
    // first we need a slider to work with
    var $weight = $("#weight").slider({value: 1, min: 0, max: 2});
    var $temp = $("#temp").slider({value: 1, min: 0, max: 2});
    var $act = $("#act").slider({value: 1, min: 0, max: 2});
    var $hardness = $("#hardness").slider({value: 1, min: 0, max: 2});

    // and then we can apply pips to it!
    $weight.slider("pips", {rest: "label"});
    $temp.slider("pips", {rest: "label"});
    $act.slider("pips", {rest: "label"});
    $hardness.slider("pips", {rest: "label"});

    var weightSteps = [
        "Lighter",
        "Neutral",
        "Heavier"
    ];
    var tempSteps = [
        "Cooler",
        "Neutral",
        "Warmer"
    ];
    var actSteps = [
        "Passive",
        "Neutral",
        "Active"
    ];
    var hardnessSteps = [
        "Softer",
        "Neutral",
        "Harder"
    ];

    $(function weight() {
        $("#weight").slider({
            value: 1,
            min: 0,
            max: 2,
            step: 1,
            slide: function (event, ui) {
                $("#amount").val(weightSteps[slider.value]);
            }
        });
        $("#amount").val(weightSteps[$("#weight").slider("value")]);
    })

    $(function temp() {
        $("#temp").slider({
            value: 1,
            min: 0,
            max: 2,
            step: 1,
            slide: function (event, ui) {
                $("#amount").val(tempSteps[slider.value]);
            }
        });
        $("#amount").val(tempSteps[$("#temp").slider("value")]);
    })

    $(function activity() {
        $("#act").slider({
            value: 1,
            min: 0,
            max: 2,
            step: 1,
            slide: function (event, ui) {
                $("#amount").val(actSteps[slider.value]);
            }
        });
        $("#amount").val(actSteps[$("#act").slider("value")]);
    })

    $(function hardness() {
        $("#hardness").slider({
            value: 1,
            min: 0,
            max: 2,
            step: 1,
            slide: function (event, ui) {
                $("#amount").val(hardnessSteps[slider.value]);
            }
        });
        $("#amount").val(hardnessSteps[$("#hardness").slider("value")]);
    })

    $("#weight").slider("pips", {rest: "label", labels: weightSteps});
    $("#temp").slider("pips", {rest: "label", labels: tempSteps});
    $("#act").slider("pips", {rest: "label", labels: actSteps});
    $("#hardness").slider("pips", {rest: "label", labels: hardnessSteps});

    function applychanges() {
        var submitstring = $("#weight").slider("value").toString() + $("#temp").slider("value").toString() + $("#act").slider("value").toString() + $("#hardness").slider("value").toString();
        console.error("weight: " + $("#weight").slider("value"));
        console.error("warmth: " + $("#temp").slider("value"));
        console.error("activity: " + $("#act").slider("value"));
        console.error("hardness: " + $("#hardness").slider("value"));
        console.error("submit string: " + submitstring);
        console.error("sending image to server");
        var new_url = "http://localhost:8080/cgi-bin/python/WebTweakVase.py";

        $.ajax({
            type: "POST",
            url: new_url,
            async: true,
            data: {vase: "<?php echo $vase_id ?>", image: "<?php echo $img_id ?>", affect_string: submitstring}
        }).done(function(response) {
            console.error("done:" + response);
            var vaseid = $(response).find('vaseid').text();
            reloadthispage(vaseid, <?php echo $vase_id ?>, <?php echo $img_id ?>);
          });

    };

    function reloadthispage(newvaseid, oldvaseid, imageid) {
        console.error("reload page: " + newvaseid + ", " + oldvaseid + ", " + imageid);
        var postFormStr = "<form method='POST' action='/tweak.php'>\n";

        postFormStr += "<input type='hidden' name='vase_id' value='" + oldvaseid + "'></input>";
        postFormStr += "<input type='hidden' name='tweaked_vase_id' value='" + newvaseid + "'></input>";
        postFormStr += "<input type='hidden' name='image_id' value='" + imageid + "'></input>";

        postFormStr += "</form>";

        var formElement = $(postFormStr);

        $('body').append(formElement);
        $(formElement).submit();
    };

    function sharenew() {
        var postFormStr = "<form method='POST' action='/share.php'>\n";

        postFormStr += "<input type='hidden' name='vase_id' value='<?php echo $tweaked_vase_id ?>'></input>";
        postFormStr += "<input type='hidden' name='image_id' value='<?php echo $img_id ?>'></input>";

        postFormStr += "</form>";

        var formElement = $(postFormStr);

        $('body').append(formElement);
        $(formElement).submit();
    };

    function shareorig() {
        var postFormStr = "<form method='POST' action='/share.php'>\n";

        postFormStr += "<input type='hidden' name='vase_id' value='<?php echo $vase_id ?>'></input>";
        postFormStr += "<input type='hidden' name='image_id' value='<?php echo $img_id ?>'></input>";

        postFormStr += "</form>";

        var formElement = $(postFormStr);

        $('body').append(formElement);
        $(formElement).submit();
    };

</script>
<script type="text/javascript">
        function getQueryVariable(variable)
	{
		var query = window.location.search.substring(1);
		var vars = query.split("&");
		for (var i=0;i<vars.length;i++)
		{
               		var pair = vars[i].split("=");
               		if(pair[0] == variable)
			{
				return pair[1];
			}
       		}
       		return(false);
		}
    </script>


<script type="text/javascript" src="js/glMatrix-0.9.5.min.js"></script>
<script type="text/javascript" src="js/webgl-utils.js"></script>


<script id="shader-fs" type="x-shader/x-fragment">
    precision mediump float;

    varying vec2 vTextureCoord;
    varying vec3 vTransformedNormal;
    varying vec4 vPosition;

    uniform float uMaterialShininess;

    uniform bool uShowSpecularHighlights;
    uniform bool uUseLighting;
    uniform bool uUseTextures;

    uniform vec3 uAmbientColor;

    uniform vec3 uPointLightingLocation;
    uniform vec3 uPointLightingSpecularColor;
    uniform vec3 uPointLightingDiffuseColor;

    uniform sampler2D uSampler;


    void main(void) {
        vec3 lightWeighting;
        if (!uUseLighting) {
            lightWeighting = vec3(1.0, 1.0, 1.0);
        } else {
            vec3 lightDirection = normalize(uPointLightingLocation - vPosition.xyz);
            vec3 normal = normalize(vTransformedNormal);

            float specularLightWeighting = 0.0;
            if (uShowSpecularHighlights) {
                vec3 eyeDirection = normalize(-vPosition.xyz);
                vec3 reflectionDirection = reflect(-lightDirection, normal);

                specularLightWeighting = pow(max(dot(reflectionDirection, eyeDirection), 0.0), uMaterialShininess);
            }

            float diffuseLightWeighting = max(dot(normal, lightDirection), 0.0);
            lightWeighting = uAmbientColor
                + uPointLightingSpecularColor * specularLightWeighting
                + uPointLightingDiffuseColor * diffuseLightWeighting;
        }

        vec4 fragmentColor;
        if (uUseTextures) {
            fragmentColor = texture2D(uSampler, vec2(vTextureCoord.s, vTextureCoord.t));
        } else {
            fragmentColor = vec4(1.0, 1.0, 1.0, 1.0);
        }
        gl_FragColor = vec4(fragmentColor.rgb * lightWeighting, fragmentColor.a);
    }
</script>

<script id="shader-vs" type="x-shader/x-vertex">
    attribute vec3 aVertexPosition;
    attribute vec3 aVertexNormal;
    attribute vec2 aTextureCoord;

    uniform mat4 uMVMatrix;
    uniform mat4 uPMatrix;
    uniform mat3 uNMatrix;

    varying vec2 vTextureCoord;
    varying vec3 vTransformedNormal;
    varying vec4 vPosition;


    void main(void) {
        vPosition = uMVMatrix * vec4(aVertexPosition, 1.0);
        gl_Position = uPMatrix * vPosition;
        vTextureCoord = aTextureCoord;
        vTransformedNormal = uNMatrix * aVertexNormal;
    }
</script>
<script type="text/javascript">

    var gl;

    function initGL(canvas) {
        try {
            gl = canvas.getContext("experimental-webgl");
            gl.viewportWidth = canvas.width;
            gl.viewportHeight = canvas.height;
        } catch (e) {
        }
        if (!gl) {
            alert("Could not initialize WebGL, sorry :-(");
        }
    }


    function getShader(gl, id) {
        var shaderScript = document.getElementById(id);
        if (!shaderScript) {
            return null;
        }

        var str = "";
        var k = shaderScript.firstChild;
        while (k) {
            if (k.nodeType == 3) {
                str += k.textContent;
            }
            k = k.nextSibling;
        }

        var shader;
        if (shaderScript.type == "x-shader/x-fragment") {
            shader = gl.createShader(gl.FRAGMENT_SHADER);
        } else if (shaderScript.type == "x-shader/x-vertex") {
            shader = gl.createShader(gl.VERTEX_SHADER);
        } else {
            return null;
        }

        gl.shaderSource(shader, str);
        gl.compileShader(shader);

        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            alert(gl.getShaderInfoLog(shader));
            return null;
        }

        return shader;
    }


    var shaderProgram;

    function initShaders() {
        var fragmentShader = getShader(gl, "shader-fs");
        var vertexShader = getShader(gl, "shader-vs");

        shaderProgram = gl.createProgram();
        gl.attachShader(shaderProgram, vertexShader);
        gl.attachShader(shaderProgram, fragmentShader);
        gl.linkProgram(shaderProgram);

        if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
            alert("Could not initialize shaders");
        }

        gl.useProgram(shaderProgram);

        shaderProgram.vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "aVertexPosition");
        gl.enableVertexAttribArray(shaderProgram.vertexPositionAttribute);

        shaderProgram.vertexNormalAttribute = gl.getAttribLocation(shaderProgram, "aVertexNormal");
        gl.enableVertexAttribArray(shaderProgram.vertexNormalAttribute);

        shaderProgram.textureCoordAttribute = gl.getAttribLocation(shaderProgram, "aTextureCoord");
        gl.enableVertexAttribArray(shaderProgram.textureCoordAttribute);

        shaderProgram.pMatrixUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
        shaderProgram.mvMatrixUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
        shaderProgram.nMatrixUniform = gl.getUniformLocation(shaderProgram, "uNMatrix");
        shaderProgram.samplerUniform = gl.getUniformLocation(shaderProgram, "uSampler");
        shaderProgram.useLightingUniform = gl.getUniformLocation(shaderProgram, "uUseLighting");
        shaderProgram.ambientColorUniform = gl.getUniformLocation(shaderProgram, "uAmbientColor");
        shaderProgram.lightingDirectionUniform = gl.getUniformLocation(shaderProgram, "uLightingDirection");
        shaderProgram.directionalColorUniform = gl.getUniformLocation(shaderProgram, "uDirectionalColor");

		shaderProgram.materialShininessUniform = gl.getUniformLocation(shaderProgram, "uMaterialShininess");
		shaderProgram.showSpecularHighlightsUniform = gl.getUniformLocation(shaderProgram, "uShowSpecularHighlights");
		shaderProgram.useTexturesUniform = gl.getUniformLocation(shaderProgram, "uUseTextures");
		shaderProgram.pointLightingLocationUniform = gl.getUniformLocation(shaderProgram, "uPointLightingLocation");
		shaderProgram.pointLightingSpecularColorUniform = gl.getUniformLocation(shaderProgram, "uPointLightingSpecularColor");
		shaderProgram.pointLightingDiffuseColorUniform = gl.getUniformLocation(shaderProgram, "uPointLightingDiffuseColor");

    }




    var vaseTexture;

    function initTexture() {




        vaseTexture = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, vaseTexture);
        var whitePixel = new Uint8Array([175, 175, 195, 175]);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, 1, 1, 0,gl.RGBA, gl.UNSIGNED_BYTE, whitePixel);

	   /*
        crateTexture = gl.createTexture();
        crateTexture.image = new Image();
        crateTexture.image.onload = function () {
            handleLoadedTexture(crateTexture)
        }

        crateTexture.image.src = "upload-temp/abstract-1.jpg";
        */
    }


    var mvMatrix = mat4.create();
    var mvMatrixStack = [];
    var pMatrix = mat4.create();

    function mvPushMatrix() {
        var copy = mat4.create();
        mat4.set(mvMatrix, copy);
        mvMatrixStack.push(copy);
    }

    function mvPopMatrix() {
        if (mvMatrixStack.length == 0) {
            throw "Invalid popMatrix!";
        }
        mvMatrix = mvMatrixStack.pop();
    }


    function setMatrixUniforms() {
        gl.uniformMatrix4fv(shaderProgram.pMatrixUniform, false, pMatrix);
        gl.uniformMatrix4fv(shaderProgram.mvMatrixUniform, false, mvMatrix);

        var normalMatrix = mat3.create();
        mat4.toInverseMat3(mvMatrix, normalMatrix);
        mat3.transpose(normalMatrix);
        gl.uniformMatrix3fv(shaderProgram.nMatrixUniform, false, normalMatrix);
    }


    function degToRad(degrees) {
        return degrees * Math.PI / 180;
    }



    var xRot = 15;
    var xSpeed = 0;

    var yRot = 0;
    var ySpeed = -25;
    
    var yLoc = -70;

    var z = -250;

    var currentlyPressedKeys = {};
    
    var leftVaseOuterVerts = [];
    var leftVaseInnerVerts = [];
	var leftVaseNormals = [];
	var leftVaseTriangles = [];
	var leftVaseLevels = 0;
	
    var rightVaseOuterVerts = [];
    var rightVaseInnerVerts = [];
	var rightVaseNormals = [];
	var rightVaseTriangles = [];
	var rightVaseLevels = 0;

    function handleKeyDown(event) {
        currentlyPressedKeys[event.keyCode] = true;
    }


    function handleKeyUp(event) {
        currentlyPressedKeys[event.keyCode] = false;
    }


    function handleKeys() {
        if (currentlyPressedKeys[109]) {
            // Minus key
            if (z > -300) {
                z -= 1;
            }
        }
        if (currentlyPressedKeys[107]) {
            // Plus key
            if (z < 300) {
                z += 1;
            }
        }
        if (currentlyPressedKeys[37]) {
            // Left Arrow
            if (ySpeed > -300) {
                ySpeed -= 1;
            }
        }
        if (currentlyPressedKeys[39]) {
            // Right arrow
            if (ySpeed < 300) {
                ySpeed += 1;
            }
        }
        
        if (currentlyPressedKeys[87]) {
            // W
            if (xSpeed > -300) {
                xSpeed -= 1;
            }
        }
        if (currentlyPressedKeys[83]) {
            // S
            if (xSpeed < 300) {
                xSpeed += 1;
            }
        }
        

        if (currentlyPressedKeys[38]) {
            // Up Arrow
            //if (viv.yloc > -500) {
                yLoc -= 1;
            //}
        }
        if (currentlyPressedKeys[40]) {
            // Down Arrow
            //if (viv.yLoc < 500) {
                yLoc += 1;
            //}
        }

    }

    var leftVasePositionBuffer;
    var leftVaseNormalBuffer;
    var leftVaseTextureCoordBuffer;
    var leftVaseIndexBuffer;
    
    var rightVasePositionBuffer;
    var rightVaseNormalBuffer;
    var rightVaseTextureCoordBuffer;
    var rightVaseIndexBuffer;
    
    var leftVaseCombinedVerts;
    var rightVaseCombinedVerts;

    function initBuffers() {
    	// left vase
        leftVasePositionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, leftVasePositionBuffer);
        leftVaseCombinedVerts = leftVaseOuterVerts.concat(leftVaseInnerVerts);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(leftVaseCombinedVerts), gl.STATIC_DRAW);
        leftVasePositionBuffer.itemSize = 3;
        leftVasePositionBuffer.numItems = leftVaseCombinedVerts.length;

        leftVaseNormalBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, leftVaseNormalBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(leftVaseNormals), gl.STATIC_DRAW);
        leftVaseNormalBuffer.itemSize = 3;
        leftVaseNormalBuffer.numItems = leftVaseNormals.length;

        leftVaseTextureCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, leftVaseTextureCoordBuffer);
        
        var leftVaseTextureCoords = [];
        var textureLength = (leftVaseCombinedVerts.length/3) * 2;
        for (var textLoopInd = 0; textLoopInd < textureLength; textLoopInd++) {
        	leftVaseTextureCoords[textLoopInd] = 1.0;
        }
        
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(leftVaseTextureCoords), gl.STATIC_DRAW);
        leftVaseTextureCoordBuffer.itemSize = 2;
        leftVaseTextureCoordBuffer.numItems = leftVaseTextureCoords.length;

        leftVaseIndexBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, leftVaseIndexBuffer);
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(leftVaseTriangles), gl.STATIC_DRAW);
        leftVaseIndexBuffer.itemSize = 1;
        leftVaseIndexBuffer.numItems = leftVaseTriangles.length;
        
        // right vase
        rightVasePositionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, rightVasePositionBuffer);
        rightVaseCombinedVerts = rightVaseOuterVerts.concat(rightVaseInnerVerts);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(rightVaseCombinedVerts), gl.STATIC_DRAW);
        rightVasePositionBuffer.itemSize = 3;
        rightVasePositionBuffer.numItems = rightVaseCombinedVerts.length;

        rightVaseNormalBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, rightVaseNormalBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(rightVaseNormals), gl.STATIC_DRAW);
        rightVaseNormalBuffer.itemSize = 3;
        rightVaseNormalBuffer.numItems = rightVaseNormals.length;

        rightVaseTextureCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, rightVaseTextureCoordBuffer);
        
        var rightVaseTextureCoords = [];
        var textureLength = (rightVaseCombinedVerts.length/3) * 2;
        for (var textLoopInd = 0; textLoopInd < textureLength; textLoopInd++) {
        	rightVaseTextureCoords[textLoopInd] = 1.0;
        }
        
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(rightVaseTextureCoords), gl.STATIC_DRAW);
        rightVaseTextureCoordBuffer.itemSize = 2;
        rightVaseTextureCoordBuffer.numItems = rightVaseTextureCoords.length;

        rightVaseIndexBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, rightVaseIndexBuffer);
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(rightVaseTriangles), gl.STATIC_DRAW);
        rightVaseIndexBuffer.itemSize = 1;
        rightVaseIndexBuffer.numItems = rightVaseTriangles.length;
    }
    
    function createVaseXyzPoints(vase, isLeftVase, isTweakSet) {
	var noOfPointsInBase = isLeftVase ? <?php echo $left_base_pts ?> : <?php echo $right_base_pts ?>;
    	var outerShift = 5;
        function interpolate(pointPair, level) {
        	var innerLeftX = pointPair.left[0] + outerShift;
        	var innerRightX = pointPair.right[0] - outerShift;
        	var radius = Math.sqrt(
        			Math.pow(pointPair.left[0]-pointPair.right[0],2)
        			+ Math.pow(pointPair.left[1]-pointPair.right[1],2))/2;
        	var innerRadius = Math.sqrt(
        			Math.pow(innerLeftX-innerRightX,2)
        			+ Math.pow(pointPair.left[1]-pointPair.right[1],2))/2;
        	var shiftCircleBy = pointPair.left[0] + pointPair.right[0];
			for (var i = 0; i < noOfPointsInBase; i++) {
				var theta = (i / noOfPointsInBase) * Math.PI * 2;
				var xVert = Math.cos(theta) * radius + shiftCircleBy;
				var zVert = Math.sin(theta) * radius;
				var innerXVert = Math.cos(theta) * innerRadius + shiftCircleBy;
				var innerZVert = Math.sin(theta) * innerRadius;
				var percentOfRight = 0;
				if (theta > Math.PI) {
					percentOfRight = 2 - (theta / Math.PI);
				} else {
					percentOfRight = (theta / Math.PI);
				}
				var heightDiff = pointPair.right[1] - pointPair.left[1];
				var yVert = pointPair.right[1] - (heightDiff * percentOfRight);
				var innerYVert = yVert;
				if (lvl == 0) {
					yVert = pointPair.left[1] - outerShift;
					innerYVert = 0;
					innerXVert *= 1;
					innerZVert *= 1;
				}
				if (isLeftVase) {
					leftVaseOuterVerts.push(
							xVert,
							yVert,
							zVert);
					leftVaseInnerVerts.push(
							innerXVert,
							innerYVert,
							innerZVert);
				} else {
					rightVaseOuterVerts.push(
							xVert,
							yVert,
							zVert);
					rightVaseInnerVerts.push(
							innerXVert,
							innerYVert,
							innerZVert);
				}
				
			}

        }
        // assign all xyz points and indices
        var vasePointList = vase;
        /*
        var vasePointList = [];
        var tempPoint = {};
        tempPoint['t'] = 0;
        tempPoint['left'] = [parseFloat(-10), parseFloat(0)];
        tempPoint['right'] = [parseFloat(10), parseFloat(0)];
        vasePointList.push(tempPoint);
        tempPoint = {};
        tempPoint['t'] = 0;
        tempPoint['left'] = [parseFloat(-20), parseFloat(5)];
        tempPoint['right'] = [parseFloat(20), parseFloat(10)];
        vasePointList.push(tempPoint);
        tempPoint = {};
        tempPoint['t'] = 0;
        tempPoint['left'] = [parseFloat(-10), parseFloat(15)];
        tempPoint['right'] = [parseFloat(10), parseFloat(25)];
        vasePointList.push(tempPoint);
        */
        
        if (isLeftVase) {
	        //outer vase
	        for (var lvl = 0; lvl < vasePointList.length; lvl++) {
	        	if (lvl == 0) {
	        		// make vase base
	        		leftVaseOuterVerts.push(0,(0-outerShift)/2,0);
	        		leftVaseInnerVerts.push(0,0,0);
	        	}
	        	interpolate(vasePointList[lvl], lvl);
	        }
	        leftVaseLevels = vasePointList.length;
        } else {
	        for (var lvl = 0; lvl < vasePointList.length; lvl++) {
	        	if (lvl == 0) {
	        		// make vase base
	        		rightVaseOuterVerts.push(0,(0-outerShift)/2,0);
	        		rightVaseInnerVerts.push(0,0,0);
	        	}
	        	interpolate(vasePointList[lvl], lvl);
	        }
	        rightVaseLevels = vasePointList.length;
        }
        
        console.error(leftVaseOuterVerts.length);
        console.error(leftVaseOuterVerts);
        console.error(leftVaseInnerVerts.length);
        console.error(leftVaseInnerVerts);
        console.error("FINISHED");
        
        calculateNormals(isLeftVase);
    }
    
    function calculateNormals(isLeftVase) {
	var noOfPointsInBase = isLeftVase ? <?php echo $left_base_pts ?> : <?php echo $right_base_pts ?>;
    	var tmpNormals = [];
    	var tmp1 = vec3.create(), tmp2 = vec3.create(), innerTmp1 = vec3.create(), innerTmp2 = vec3.create();
    	
    	function myRound(a) {
    		return Math.round(parseFloat(a)*100)/100;
    	}
    	
    	function contains(tmpArray, obj) {
    	    var tmpArrayIndex = tmpArray.length;
    	    while (tmpArrayIndex--) {
    	       if (myRound(tmpArray[tmpArrayIndex][0]) === myRound(obj[0])
    	    		   && myRound(tmpArray[tmpArrayIndex][1]) === myRound(obj[1])
    	    		   && myRound(tmpArray[tmpArrayIndex][2]) === myRound(obj[2]))
    	       {
    	           return true;
    	       }
    	    }
    	    return false;
    	}
    	
    	function pushNormal(index, normal) {
    		tmpNormals[index] = tmpNormals[index] || [];
    		
    		if (!contains(tmpNormals[index], normal)) {
    			//console.error("pushing normal for " + index[0] + "," + index[1] + "," + index[2] + ": " + normal[0] + "," + normal[1] + "," + normal[2]);
    			tmpNormals[index].push(normal);
    		}
    	}
    	
    	function pushTriangle(firstInd, secondInd, thirdInd) {
    		if (isLeftVase) {
                //console.error("pushing left triangle: " + firstInd + ", " + secondInd + ", " + thirdInd);
    			leftVaseTriangles.push(firstInd, secondInd, thirdInd);
    		} else {
                //console.error("pushing right triangle: " + firstInd + ", " + secondInd + ", " + thirdInd);
    			rightVaseTriangles.push(firstInd, secondInd, thirdInd);
    		}
    	}
    	
    	var outerVertList = isLeftVase ? leftVaseOuterVerts : rightVaseOuterVerts;
    	var innerVertList = isLeftVase ? leftVaseInnerVerts : rightVaseInnerVerts;
    	var vaseLvls = isLeftVase ? leftVaseLevels : rightVaseLevels;
    	var outerVertsLength = outerVertList.length;
    	var outerVertsIndexLength = (outerVertsLength/3);
    	
    	for (var normalsIndex = 3; normalsIndex < outerVertsLength; normalsIndex+=3) {
           //console.error("begin for loop with index: " + normalsIndex);
    		var tmpNorm = vec3.create();
    		var innerTmpNorm = vec3.create();
        	// vase base
    		if (normalsIndex < ((noOfPointsInBase + 1) * 3)) {
                //console.error("in base for index: " + normalsIndex);
    			var center = [outerVertList[0], outerVertList[1], outerVertList[2]];
    			var innerCenter = [innerVertList[0], innerVertList[1], innerVertList[2]];
    			var pt1 = [outerVertList[normalsIndex], outerVertList[normalsIndex+1], outerVertList[normalsIndex+2]];
    			var pt2 = [];
    			var innerPt1 = [innerVertList[normalsIndex], innerVertList[normalsIndex+1], innerVertList[normalsIndex+2]];
    			var innerPt2 = [];
    			if (normalsIndex != ((noOfPointsInBase) * 3)) {
    				pt2 = [outerVertList[normalsIndex+3], outerVertList[normalsIndex+4], outerVertList[normalsIndex+5]];
    				innerPt2 = [innerVertList[normalsIndex+3], innerVertList[normalsIndex+4], innerVertList[normalsIndex+5]];
    				pushTriangle((normalsIndex+3)/3,normalsIndex/3, 0);
    				pushTriangle(outerVertsIndexLength,(normalsIndex/3) + outerVertsIndexLength, ((normalsIndex+3)/3) + outerVertsIndexLength);
    				// console.error("1Pushing:" + 0 + "," + normalsIndex/3 + "," + (normalsIndex+3)/3);
    			}
    			else {
    				pt2 = [outerVertList[3], outerVertList[4], outerVertList[5]];
    				innerPt2 = [innerVertList[3], innerVertList[4], innerVertList[5]];
    				pushTriangle(1,normalsIndex/3, 0);
    				pushTriangle(outerVertsIndexLength,(normalsIndex/3) + outerVertsIndexLength, outerVertsIndexLength + 1);
    			}
    			
        		// calculate face normal
        		vec3.subtract(pt1, center, tmp1);
        		vec3.subtract(pt2, center, tmp2);
        		vec3.subtract(innerPt1, innerCenter, innerTmp1);
        		vec3.subtract(innerPt2, innerCenter, innerTmp2);
        		tmpNorm = vec3.normalize(vec3.cross(tmp1, tmp2, vec3.create()));
        		innerTmpNorm = vec3.normalize(vec3.cross(innerTmp2, innerTmp1, vec3.create()));

        		// accumulate face normal for each of a, b, c
        		pushNormal(center, vec3.create(tmpNorm));
        		pushNormal(pt1, vec3.create(tmpNorm));
        		pushNormal(pt2, vec3.create(tmpNorm));
        		
        		pushNormal(innerCenter, vec3.create(innerTmpNorm));
        		pushNormal(innerPt1, vec3.create(innerTmpNorm));
        		pushNormal(innerPt2, vec3.create(innerTmpNorm));
    		}
    		
    		// triangle above
    		var ptAboveIndex = ((normalsIndex/3) + noOfPointsInBase) * 3;
    		var ptBelowIndex = ((normalsIndex/3) - noOfPointsInBase) * 3;
    		var ptA = [outerVertList[normalsIndex], outerVertList[normalsIndex+1], outerVertList[normalsIndex+2]];
    		var ptB = [outerVertList[normalsIndex+3], outerVertList[normalsIndex+4], outerVertList[normalsIndex+5]];
    		var ptC = [outerVertList[ptAboveIndex], outerVertList[ptAboveIndex+1], outerVertList[ptAboveIndex+2]];
    		
    		var innerPtA = [innerVertList[normalsIndex], innerVertList[normalsIndex+1], innerVertList[normalsIndex+2]];
    		var innerPtB = [innerVertList[normalsIndex+3], innerVertList[normalsIndex+4], innerVertList[normalsIndex+5]];
    		var innerPtC = [innerVertList[ptAboveIndex], innerVertList[ptAboveIndex+1], innerVertList[ptAboveIndex+2]];
    		
    		var ptAIndex = normalsIndex;
    		var ptBIndex = normalsIndex + 3;
    		var ptCIndex = ptAboveIndex;
    		
    		// last point around, need to connect it to the first point on this level
    		if ((normalsIndex/3) % noOfPointsInBase == 0) {
                //console.error("last pt around on index: " + normalsIndex);
    			var tmpInd = normalsIndex - ((noOfPointsInBase - 1) * 3);
    			ptB = [outerVertList[tmpInd], outerVertList[tmpInd+1], outerVertList[tmpInd+2]];
    			innerPtB = [innerVertList[tmpInd], innerVertList[tmpInd+1], innerVertList[tmpInd+2]];
                //console.error("ptB on last around: <" + tmpInd + "," + (tmpInd+1) + "," + (tmpInd+2) + ">");
    			ptBIndex = tmpInd;
    		}
    		  		
    		// don't this on the top-most level, no triangle above
    		if (normalsIndex < ((((vaseLvls - 1) * noOfPointsInBase) + 1) * 3)) {
                //console.error("not on top level for index: " + normalsIndex);
	    		// calculate face normal
	    		vec3.subtract(ptC, ptA, tmp1);
	    		vec3.subtract(ptB, ptA, tmp2);
	    		tmpNorm = vec3.normalize(vec3.cross(tmp1, tmp2, vec3.create()));
	    		
	    		vec3.subtract(innerPtC, innerPtA, innerTmp1);
	    		vec3.subtract(innerPtB, innerPtA, innerTmp2);
	    		innerTmpNorm = vec3.normalize(vec3.cross(innerTmp2, innerTmp1, vec3.create()));
	
	    		// accumulate face normal for each of a, b, c
	    		pushNormal(ptA, vec3.create(tmpNorm));
	    		pushNormal(ptB, vec3.create(tmpNorm));
	    		pushNormal(ptC, vec3.create(tmpNorm));
	    		
	    		pushNormal(innerPtA, vec3.create(innerTmpNorm));
	    		pushNormal(innerPtB, vec3.create(innerTmpNorm));
	    		pushNormal(innerPtC, vec3.create(innerTmpNorm));
	    		
	    		pushTriangle(ptAIndex/3, ptBIndex/3, ptCIndex/3);
	    		pushTriangle((ptCIndex/3) + outerVertsIndexLength, (ptBIndex/3) + outerVertsIndexLength, (ptAIndex/3) + outerVertsIndexLength);
    		} else {
    			//console.error("inside the terrible else block for: " + normalsIndex);
    			// connect inner at outer verts
    			ptCIndex = ptAIndex + outerVertsLength;
    			innerPtC = [innerVertList[ptAIndex], innerVertList[ptAIndex+1], innerVertList[ptAIndex+2]];
    			vec3.subtract(innerPtC, ptA, tmp1);
	    		vec3.subtract(ptB, ptA, tmp2);
	    		tmpNorm = vec3.normalize(vec3.cross(tmp1, tmp2, vec3.create()));
	
	    		// accumulate face normal for each of a, b, c
	    		pushNormal(ptA, vec3.create(tmpNorm));
	    		pushNormal(ptB, vec3.create(tmpNorm));
	    		pushNormal(innerPtC, vec3.create(tmpNorm));
	    		
	    		pushTriangle(ptAIndex/3, ptBIndex/3, ptCIndex/3);
	    		
	    		//console.error("done with 1");
	    		
	    		// Next triangle
	    		ptDIndex = ptBIndex + outerVertsLength;
	    		var ptD = [innerVertList[ptBIndex],innerVertList[ptBIndex+1],innerVertList[ptBIndex+2]];
    			vec3.subtract(ptB, ptD, tmp1);
	    		vec3.subtract(innerPtC, ptD, tmp2);
	    		tmpNorm = vec3.normalize(vec3.cross(tmp1, tmp2, vec3.create()));
	
	    		// accumulate face normal for each of a, b, c
	    		pushNormal(ptD, vec3.create(tmpNorm));
	    		pushNormal(ptB, vec3.create(tmpNorm));
	    		pushNormal(innerPtC, vec3.create(tmpNorm));
	    		
	    		pushTriangle(ptDIndex/3, ptCIndex/3, ptBIndex/3);
	    		//console.error("done with both");
    		}
    		
    		// triangle below, don't do this on the bottom level
    		if (normalsIndex >= ((noOfPointsInBase + 1) * 3)) {
                //console.error("not on bottom level for index: " + normalsIndex);
    			// the one before it
    			ptB = [outerVertList[normalsIndex-3], outerVertList[normalsIndex-2], outerVertList[normalsIndex-1]];
    			innerPtB = [innerVertList[normalsIndex-3], innerVertList[normalsIndex-2], innerVertList[normalsIndex-1]];
    			ptBIndex = normalsIndex - 3;
    			// if the prior point is on a different level, use last point on current level
    			if (((normalsIndex/3) - 1) % (noOfPointsInBase) == 0) {
                    //console.error("prior point on diff level for index: " + normalsIndex);
    				var tmpInd = normalsIndex + ((noOfPointsInBase - 1) * 3);
    				ptB = [outerVertList[tmpInd], outerVertList[tmpInd+1], outerVertList[tmpInd+2]];
    				innerPtB = [innerVertList[tmpInd], innerVertList[tmpInd+1], innerVertList[tmpInd+2]];
    				ptBIndex = tmpInd;
                    //console.error("prior pt on diff level ptB set: <" + tmpInd + "," + (tmpInd+1) + "," + (tmpInd+2) + ">");
    			}
    			
    			// the one below it
    			ptC = [outerVertList[ptBelowIndex], outerVertList[ptBelowIndex+1], outerVertList[ptBelowIndex+2]];
    			innerPtC = [innerVertList[ptBelowIndex], innerVertList[ptBelowIndex+1], innerVertList[ptBelowIndex+2]];
                //console.error("not on bottom level ptC set: <" + ptBelowIndex + "," + (ptBelowIndex+1) + "," + (ptBelowIndex+1) + ">");
    			ptCIndex = ptBelowIndex;
    			
    			// calculate face normal
        		vec3.subtract(ptC, ptA, tmp1);
        		vec3.subtract(ptB, ptA, tmp2);
        		tmpNorm = vec3.normalize(vec3.cross(tmp1, tmp2, vec3.create()));
        		
        		vec3.subtract(innerPtC, innerPtA, innerTmp1);
        		vec3.subtract(innerPtB, innerPtA, innerTmp2);
        		innerTmpNorm = vec3.normalize(vec3.cross(innerTmp2, innerTmp1, vec3.create()));

        		// accumulate face normal for each of a, b, c
        		pushNormal(ptA, vec3.create(tmpNorm));
        		pushNormal(ptB, vec3.create(tmpNorm));
        		pushNormal(ptC, vec3.create(tmpNorm));
        		
        		pushNormal(innerPtA, vec3.create(innerTmpNorm));
        		pushNormal(innerPtB, vec3.create(innerTmpNorm));
        		pushNormal(innerPtC, vec3.create(innerTmpNorm));
        		
        		pushTriangle(ptAIndex/3, ptBIndex/3, ptCIndex/3);
        		pushTriangle((ptCIndex/3) + outerVertsIndexLength, (ptBIndex/3) + outerVertsIndexLength, (ptAIndex/3) + outerVertsIndexLength);
			}

    	}
    	
    	// now calculate normalized averages for each face normal, and store the result
    	for (postIndex = 0; postIndex < outerVertList.length; postIndex += 3) {
    		a = [outerVertList[postIndex], outerVertList[postIndex+1], outerVertList[postIndex+2]];
    		b = [innerVertList[postIndex], innerVertList[postIndex+1], innerVertList[postIndex+2]];
    		/*
    		if (tmpNormals[a]) {
    			for (var innerPostIndex = 0; innerPostIndex < tmpNormals[a].length; innerPostIndex++) {
   	    			leftVaseNormals.push(tmpNormals[a][innerPostIndex][0], 
   	    					tmpNormals[a][innerPostIndex][1], 
   	    					tmpNormals[a][innerPostIndex][2]);
   	    		}
    		}
    		*/
    		
   	  		if (tmpNormals[a]) {
   	    		var avg = vec3.create();
   	    		//console.error("normals for " + a[0] + "," + a[1] + "," + a[2] + ": ");
   	    		for (var innerPostIndex = 0; innerPostIndex < tmpNormals[a].length; innerPostIndex++) {
   	    			//console.error(tmpNormals[a][innerPostIndex][0] + "," + tmpNormals[a][innerPostIndex][1] + "," + tmpNormals[a][innerPostIndex][2]);
   	      			vec3.add(tmpNormals[a][innerPostIndex], avg, avg);
   	    		}
    	    	vec3.scale(avg, 1/tmpNormals[a].length);
    	    	vec3.normalize(avg);

    	    	if (isLeftVase) {
	    	    	leftVaseNormals[postIndex] = avg[0];
	    	    	leftVaseNormals[postIndex+1] = avg[1];
	    	    	leftVaseNormals[postIndex+2] = avg[2];
    	    	} else {
        	    	rightVaseNormals[postIndex] = avg[0];
        	    	rightVaseNormals[postIndex+1] = avg[1];
        	    	rightVaseNormals[postIndex+2] = avg[2];
    	    	}
    	  	}
   	  		if (tmpNormals[b]) {
   	    		var avg = vec3.create();
   	    		//console.error("normals for " + b[0] + "," + b[1] + "," + b[2] + ": ");
   	    		for (var innerPostIndex = 0; innerPostIndex < tmpNormals[b].length; innerPostIndex++) {
   	    			//console.error(tmpNormals[b][innerPostIndex][0] + "," + tmpNormals[b][innerPostIndex][1] + "," + tmpNormals[b][innerPostIndex][2]);
   	      			vec3.add(tmpNormals[b][innerPostIndex], avg, avg);
   	    		}
    	    	vec3.scale(avg, 1/tmpNormals[b].length);
    	    	vec3.normalize(avg);

    	    	if (isLeftVase) {
	    	    	leftVaseNormals[postIndex+outerVertsLength] = avg[0];
	    	    	leftVaseNormals[postIndex+1+outerVertsLength] = avg[1];
	    	    	leftVaseNormals[postIndex+2+outerVertsLength] = avg[2];
    	    	} else {
        	    	rightVaseNormals[postIndex+outerVertsLength] = avg[0];
        	    	rightVaseNormals[postIndex+1+outerVertsLength] = avg[1];
        	    	rightVaseNormals[postIndex+2+outerVertsLength] = avg[2];
    	    	}
    	  	}
    	}

    	// sanity check
    	//if (leftVaseNormals.length != leftVaseOuterVerts.length)
    	//	alert("ERROR "+leftVaseNormals.length+" != "+leftVaseOuterVerts.length);
    	console.error("Begin normals output")
    	console.error(leftVaseNormals);
    	console.error(leftVaseTriangles);
    	console.error(leftVaseTriangles.length);
    }


    function drawScene(isLeftVase) {

        mat4.perspective(45, gl.viewportWidth / gl.viewportHeight / 2, 0.1, 350.0, pMatrix);

        mat4.identity(mvMatrix);

        mat4.translate(mvMatrix, [0.0, yLoc, z]);

        mat4.rotate(mvMatrix, degToRad(xRot), [1, 0, 0]);
        mat4.rotate(mvMatrix, degToRad(yRot), [0, 1, 0]);

        if (isLeftVase) {
	        gl.bindBuffer(gl.ARRAY_BUFFER, leftVasePositionBuffer);
	        gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, leftVasePositionBuffer.itemSize, gl.FLOAT, false, 0, 0);
	
	        gl.bindBuffer(gl.ARRAY_BUFFER, leftVaseNormalBuffer);
	        gl.vertexAttribPointer(shaderProgram.vertexNormalAttribute, leftVaseNormalBuffer.itemSize, gl.FLOAT, false, 0, 0);
	
	        gl.bindBuffer(gl.ARRAY_BUFFER, leftVaseTextureCoordBuffer);
	        gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, leftVaseTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);
        } else {
        	gl.bindBuffer(gl.ARRAY_BUFFER, rightVasePositionBuffer);
	        gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, rightVasePositionBuffer.itemSize, gl.FLOAT, false, 0, 0);
	
	        gl.bindBuffer(gl.ARRAY_BUFFER, rightVaseNormalBuffer);
	        gl.vertexAttribPointer(shaderProgram.vertexNormalAttribute, rightVaseNormalBuffer.itemSize, gl.FLOAT, false, 0, 0);
	
	        gl.bindBuffer(gl.ARRAY_BUFFER, rightVaseTextureCoordBuffer);
	        gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, rightVaseTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);
        }

        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, vaseTexture);
        gl.uniform1i(shaderProgram.samplerUniform, 0);
        gl.uniform1i(shaderProgram.useLightingUniform, true);
        gl.uniform1i(shaderProgram.useTexturesUniform, true);
        gl.uniform1i(shaderProgram.showSpecularHighlightsUniform, true);
        gl.uniform3f(shaderProgram.ambientColorUniform, 0.2, 0.2, 0.2);
        var lightingDirection = [-.25, -.25, .25];
        var adjustedLD = vec3.create();
        vec3.normalize(lightingDirection, adjustedLD);
        vec3.scale(adjustedLD, -1);
        gl.uniform3fv(shaderProgram.lightingDirectionUniform, adjustedLD);
        gl.uniform3f(shaderProgram.directionalColorUniform, 0.8, 0.8, 0.8);
        gl.uniform3f(shaderProgram.pointLightingLocationUniform, -10, 4, 20);
        gl.uniform3f(shaderProgram.pointLightingSpecularColorUniform, 0.8, 0.8, 0.8);
        gl.uniform3f(shaderProgram.pointLightingDiffuseColorUniform, 0.8, 0.8, 0.8);
        
        gl.uniform1f(shaderProgram.materialShininessUniform, 32);

        if (isLeftVase) {
	        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, leftVaseIndexBuffer);
	        setMatrixUniforms();
	        gl.drawElements(gl.TRIANGLES, leftVaseIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
        } else {
	        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, rightVaseIndexBuffer);
	        setMatrixUniforms();
	        gl.drawElements(gl.TRIANGLES, rightVaseIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
        }
    }


    var lastTime = 0;

    function animate() {
        var timeNow = new Date().getTime();
        if (lastTime != 0) {
            var elapsed = timeNow - lastTime;

            xRot += (xSpeed * elapsed) / 1000.0;
            yRot += (ySpeed * elapsed) / 1000.0;
        }
        lastTime = timeNow;
    }


    function tick() {
    	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
    	
        gl.viewport(0, 0, gl.viewportWidth / 2, gl.viewportHeight);
        drawScene(true);
        gl.viewport( gl.viewportWidth / 2, 0,  gl.viewportWidth / 2, gl.viewportHeight);
        drawScene(false);
    	
        requestAnimFrame(tick);
        handleKeys();
        animate();
    }


    function webGLStart() {
        var canvas = document.getElementById("viv-canvas");
        initGL(canvas);
        var isTweakSet = false;
        if (<?php echo $selection ?> != -1) {
            isTweakSet = true;
        };
        var leftVase = createPreDefVase(1, isTweakSet);
        var rightVase = createPreDefVase(0, isTweakSet);
        createVaseXyzPoints(leftVase, true, isTweakSet);
        createVaseXyzPoints(rightVase, false, isTweakSet);
        initShaders();
        initBuffers();
        initTexture();

        gl.clearColor(0.0, 0.0, 0.0, 0.0);
        gl.enable(gl.DEPTH_TEST);

        document.onkeydown = handleKeyDown;
        document.onkeyup = handleKeyUp;
        
        tick();
    }
    
    function appendXmlValuesToJson(pointList, right_x, right_y, left_x, left_y, t) {

        // console.error(parseFloat(right_x));
        var tempPointObject = {};
        tempPointObject['t'] = parseInt(t);
        tempPointObject['left'] = [parseFloat(left_x), parseFloat(left_y)];
        tempPointObject['right'] = [parseFloat(right_x), parseFloat(right_y)];
        //console.error(tempPointObject);
        return tempPointObject;
	}

	function createVase() {
        console.error("creating vase");
        var pointList = [];
		//var new_url = "http://localhost:8080/cgi-bin/python/VivMain.py";
		var new_url = "http://localhost:8080/cgi-bin/python/VaseCreation.py";
	
		//console.error("new_url:" + new_url);
	    $.ajax({
	        type: "POST",
	        url: new_url,
	        async: false,
	        data: { upload_id: '0', image: getQueryVariable("image") }
	    }).done(function( response ) {
	        $(response).find('pointpair').each(function () {
	            var right_x = $(this).find('right').find('x').text();
	            // console.error("right x: " + right_x);
	            var right_y = $(this).find('right').find('y').text();
	            var left_x = $(this).find('left').find('x').text();
	            var left_y = $(this).find('left').find('y').text();
	            var t = $(this).find('t').text();
	            pointList.push(appendXmlValuesToJson(pointList, right_x, right_y, left_x, left_y, t));
	        });
	    });
	    //console.error(pointList);
	    return pointList;
}

function createPreDefVase(isLeft, isTweakSet) {
	console.error("creating pre-made vase");
	var pointList = [];
	if (isLeft == 1) {
		var vaseXml = "<?php echo $left_vase_xml; ?>";
	} else {
		var vaseXml = "<?php echo $right_vase_xml; ?>";
	}
	$(vaseXml).find('pointpair').each(function () {
    	var right_x = $(this).find('right').find('x').text();
        // console.error("right x: " + right_x);
        var right_y = $(this).find('right').find('y').text();
        var left_x = $(this).find('left').find('x').text();
        var left_y = $(this).find('left').find('y').text();
        var t = $(this).find('t').text();
        pointList.push(appendXmlValuesToJson(pointList, right_x, right_y, left_x, left_y, t));
    });
	return pointList;
}

webGLStart();

</script>

</body>
</html>
