<?php
	session_start();
	if(isset($_SESSION['login'])){
		header('Location: home.php');
	}	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Ismail KHEIRY</title>
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="w3/w3.css" />
<link rel="stylesheet" type="text/css" href="custom/css/index.css" />
</head>
<body>

	<nav id="myNavbar" class="navbar navbar-inverse navbar-static-top" style="margin-bottom: 0;">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="http://localhost:8080/dbNotif">Ismail KHEIRY</a>
	      <ul class="nav navbar-nav visible-xs" style="float: right; margin-right: 10px; list-style: none;">
	        <?php
	        	if(!isset($_SESSION['login'])){
					echo '<li style=" display: inline-block;"><a href="#" data-toggle="modal" data-target="#myLoginModal"><span class="glyphicon glyphicon-log-in login" style="margin-right: 5px;"></span> Login</a></li>';	
				}
			?>
	    </ul>
	    </div>
	    <ul class="nav navbar-nav navbar-right hidden-xs" style="float: right; margin-right: 10px; list-style: none;">
	        <?php
				if(!isset($_SESSION['login'])){
					echo '<li style=" display: inline-block;"><a href="#" data-toggle="modal" data-target="#myLoginModal"><span class="glyphicon glyphicon-log-in login" style="margin-right: 5px;"></span> Login</a></li>';	
				}
			?>
	    </ul>
	  </div>
	</nav>
	
	<div id="alert-container" style="position: absolute; z-index: 10; opacity: 0.85; margin-top: 15px; padding: 0; width: 100%">
	</div>

	<div class="banner" style="position: absolute;">
	  <div class="banner__overlay">
	    <div class="banner__container">
	      <h1 class="banner__title">ISMAIL KHEIRY</h1>
	      <h5 class="banner__title" style="font-size: 36px;">Projet PHP</h5>
	      <div class=" col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
	      	<p class="banner__text" >Ce projet consiste à réaliser un système d'actualisation de pages web, plus précisément lorsque des données changent dans la base de données, les pages web doivent s'actualiser automatiquement</p>
	      </div>
	      <div class="clearfix"></div>
	      <p class="banner__text">Pour la réalisation, j'ai utilisé ajax avec jquery.</p>
	      <a href="#" class="btn btn--opacity login" data-toggle="modal" data-target="#myLoginModal">Tester le système</a>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div id="myLoginModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Modal Header</h4>
	      </div>
	      <div class="modal-body">
	        <form action="/action_page.php">
			  <div class="form-group">
			    <label for="email">Login:</label>
			    <input type="text" class="form-control" id="log">
			  </div>
			  <div class="form-group">
			    <label for="pwd">Mot de passe:</label>
			    <input type="password" class="form-control" id="pass">
			  </div>
			  <div id="erreur" class="form-group" style="display: none;">
			    <label class="alert alert-danger" style="margin: 0; width: 100%">Login ou mot de passe invalide(s)!</label>
			  </div>
			</form> 
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
	        <button id="connexion" type="button" class="btn btn-default" disabled="disabled">Connexion</button>
	      </div>
	    </div>

	  </div>
	</div>

	<!-- jQuery 1.12.4 -->
    <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    	$(document).ready(function(){
    		$(".banner").height($(window).height() - $("#myNavbar").height() - 1);
    		$(window).on( "resize", function(){
    			var newHeight = $(window).height() - $("#myNavbar").height() - 1;
    			$(".banner").height(newHeight);
    		});

    		$("#login").on("click", function(e){
		        e.preventDefault();
		    });
		    $("#log").on("blur", function(){
		    	if($(this).val() != "" && $("#pass").val() != "")
		    		$("#connexion").prop("disabled",false);
		    	else
		    		$("#connexion").prop("disabled",true);
		    });
		    $("#log").on("change", function(){
		    	$("#erreur").hide();
		    });
		    $("#pass").on("blur", function(){
		    	if($(this).val() != "" && $("#log").val() != "")
		    		$("#connexion").prop("disabled",false);
		    	else
		    		$("#connexion").prop("disabled",true);
		    });
		    $("#pass").on("change", function(){
		    	$("#erreur").hide();
		    });
		    $("#myLoginModal").on("hidden.bs.modal", function(){
		    	$("#log").val("");
		    	$("#pass").val("");
		    	$("#erreur").hide();
		    });
		    $("#myLoginModal").on("shown.bs.modal", function(){
		    	$("#log").focus();
		    });

		    $("#connexion").on("click", function(){
		    	$.ajax({
			    	url : 'login.php',
			       	type : 'POST',
			       	data: { log: $("#log").val(), pass: $("#pass").val() },
			       	success : function(response, statut){
			       		console.log(response);
			       		if(response == "success")
			       			$(location).attr('href', ' http://localhost:8080/dbNotif/home.php');
			       		else
			       			$("#erreur").show();
			      	},
			       	
			       	error : function(resultat, statut, erreur){
			       		console.log(erreur);	
			    	},
			       	complete : function(resultat, statut){
			       		//console.log(resultat + " : " + statut);
			       	}
			    });
		    });
    	});
    </script>

</body>
</html>