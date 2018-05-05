<?php
	session_start();
	if(! isset($_SESSION['login'])){
		header('Location: http://localhost:8080/dbNotif/');
	}

	require "requiredClasses.php";
		
	try{
		$db = new PDO('mysql:host=localhost;dbname=notif_db', 'root', '');
		// les erreurs lanceront des exceptions
		$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
		$requete = "SELECT * FROM notification AS notif WHERE notif.ID IN (SELECT relation.ID_NOTIFICATION FROM user_notification AS relation WHERE relation.ID_USER = (SELECT ID FROM user WHERE LOGIN = :login) AND relation.READED= 0 ) ";
		$statement = $db->prepare($requete);
		$statement->bindValue(':login',$_SESSION['login']);
		$statement->execute();	
		$nbre_notifs = $statement->rowCount();

		if(!isset($_SESSION['displayed'])){
			$_SESSION['displayed'] = [];
			while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)){
				$notify = new Notification($donnees);
				array_push($_SESSION['displayed'], $notify->id());
			}
		}
	}
	catch(Exception $e){
		echo "Échec : " . $e->getMessage() . "<br/>";
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
	        	if(isset($_SESSION['login'])){
					if($nbre_notifs != 0 ){
						echo '	<li style=" display: inline-block;"><a href="notifications.php">
		        					<span class="glyphicon glyphicon-bell"></span>
		        					<span class="label label-danger alert-number" style="position: absolute; top: 24px; border-radius: 0px 10px 10px 10px;">' . $nbre_notifs . '</span>
		        				</a></li>';	
					}
					else{
						echo '	<li style=" display: inline-block;"><a href="notifications.php">
		        					<span class="glyphicon glyphicon-bell"></span>
		        					<span class="label label-danger alert-number" style="position: absolute; top: 24px; border-radius: 0px 10px 10px 10px; display: none;">' . $nbre_notifs . '</span>
		        				</a></li>';
					}
					echo '<li style=" display: inline-block;"><a href="logout.php"><span class="glyphicon glyphicon-log-out" style="margin-right: 5px;"></span> Logout</a></li>';
				}
			?>
	    </ul>
	    </div>
	    <ul class="nav navbar-nav navbar-right hidden-xs" style="float: right; margin-right: 10px; list-style: none;">
	        <?php
				if(isset($_SESSION['login'])){
					if($nbre_notifs != 0 ){
						echo '	<li style=" display: inline-block;"><a href="notifications.php">
		        					<span class="glyphicon glyphicon-bell"></span>
		        					<span class="label label-danger alert-number" style="position: absolute; top: 24px; border-radius: 0px 10px 10px 10px;">' . $nbre_notifs . '</span>
		        				</a></li>';	
					}
					else{
						echo '	<li style=" display: inline-block;"><a href="notifications.php">
		        					<span class="glyphicon glyphicon-bell"></span>
		        					<span class="label label-danger alert-number" style="position: absolute; top: 24px; border-radius: 0px 10px 10px 10px; display: none;">' . $nbre_notifs . '</span>
		        				</a></li>';
					}
					echo '<li style=" display: inline-block;"><a href="logout.php"><span class="glyphicon glyphicon-log-out" style="margin-right: 5px;"></span> Logout</a></li>';
				}
			?>
	    </ul>
	    <audio preload="auto" id="son">
		  <source src="audio/open-ended.mp3" type="audio/mp3">
		  <source src="audio/open-ended.ogg" type="audio/ogg">
		</audio>
	  </div>
	</nav>
	
	<div id="alert-container" style="position: fixed; z-index: 1000; opacity: 0.85; margin-top: 15px; padding: 0; width: 100%">
		<?php 
			// ici arrivent les notifications
		?>
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
	      <a href="notifications.php" class="btn btn--opacity">Voir les notifications!</a>
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
    		
			var notification_id = 1;
			var nbre_global = <?php echo $nbre_notifs;?>;
			// Recherche notifications
		    time=setInterval(function(){
				$.ajax({
			    	url : 'getNotifications.php',
			       	type : 'GET',
			       	success : function(response, statut){
			       		if(statut == "success"){
			       			//console.log(response);
			       			var resultat = jQuery.parseJSON(response);
				       		if(resultat.notify == "notify"){
					        	var nbre = resultat.notifications.length;
					        	nbre_global += nbre;
					        	var message = "";
					        	if(nbre != 0 ){
						        	if(nbre == 1){
						        		message = "1 notification.";
						        	}
						        	else{
						        		message = nbre + " notifications.";
						        	}
						        	var alert_id = notification_id;
						        	notification_id++;
									var alert = '\
							        	<div id="' + alert_id + '"class="col-xs-12 col-sm-7 col-sm-offset-5 col-md-5 col-md-offset-7 col-lg-3 col-lg-offset-9" style="display: none;">\
											<div class="alert alert-success alert-dismissible" >\
											  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
											  <strong>News!</strong> Vous avez reçu ' + message +
											'</div>\
										</div>';
									$('#son')[0].play();
									setTimeout(function() {
					    				$("#alert-container").prepend(alert);
										if(nbre_global == 0){
											$(".alert-number").text(nbre_global);
											$(".alert-number").hide();	
										}
										else if(nbre_global < 100){
											$(".alert-number").text(nbre_global);
											$(".alert-number").show();	
										}
										else{
											$(".alert-number").text("99+");
											$(".alert-number").show();
										}
										$("#"+alert_id).slideDown("slow");
										setTimeout(function() {
											$("#"+alert_id).animate({
									    	left: "600px",
									    	opacity: '0'}, "slow").hide("slow");
									    }, 7000);
								    }, 1000);
									
								}
							}
						}
			      	},
			       	
			       	error : function(resultat, statut, erreur){
			       		console.log(erreur);
			    	},
			       	complete : function(resultat, statut){
			       		//console.log(resultat + " : " + statut);
			       	}
			    });
			},3000);		    
		});
    </script>

</body>
</html>