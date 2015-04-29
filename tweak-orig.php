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

$_viv_sess_db = new mysqli('localhost', 'root', 'Muellne1!', 'viv');
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
        <a href="inspire.php" class="button" id="previousbutton">&lt;&lt;</a>
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
        <div class="control">
            <p>Weight: x</p>
        </div>
        <div class="control">
            <p>Temperature: x</p>
        </div>
        <div class="control">
            <p>Activity: x</p>
        </div>
        <div class="control">
            <p>Hardness: x</p>
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
        var new_url = "./cgi-bin/python/WebTweakVase.py";

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
		var new_url = "./cgi-bin/python/VaseCreation.py";
	
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
