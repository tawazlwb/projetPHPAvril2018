<?php
	session_start();
	//$_SESSION['login']="ok";
	unset($_SESSION['login']);
	        	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Ismail KHEIRY</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/w3.css" />
<link rel="stylesheet" type="text/css" href="css/custom/index.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

	<div class="container">
		<audio preload="auto" id="son">
		  <source src="audio/open-ended.mp3" type="audio/mp3">
		  <source src="audio/open-ended.ogg" type="audio/ogg">
		</audio>
	</div>

	<button id="jouer" class="btn btn-primary">Jouer le son!</button>

	<!-- jQuery 1.12.4 -->
    <script src="js/jquery/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/dataTables/jquery.dataTables.min.js"></script>
    <script src="js/dataTables/dataTables.bootstrap.min.js"></script>


    <script type="text/javascript">
    	$(document).ready(function(){
    		$("#jouer").on("click", function(){
    			$('#son')[0].play();
    			setTimeout(function() {
    				$('#son')[0].play();
			    }, 1000);
			    console.log("ok");
    		});
    	});
    		
    </script>
        
</body>
</html>