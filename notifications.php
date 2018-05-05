<?php
	session_start();
	if(! isset($_SESSION['login'])){
		header('Location: http://localhost:8080/dbNotif/');
	}
	$_SESSION['displayed'] = [];

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
<link rel="stylesheet" type="text/css" href="dataTables/css/dataTables.bootstrap.min.css" />
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
		    <div>
		      <h1 class="banner__title">ISMAIL KHEIRY</h1>
		      <h5 class="banner__title" style="font-size: 36px;">Projet PHP</h5>
		      <div class="container" style="margin-top: 20px;">
				<div class="panel-group">
					<div class="panel panel-primary">
				      <div class="panel-heading">
				      	<b>Notification From Server Side</b>
				      	<a data-toggle="collapse" data-target="#content" style="float: right; color: #fff;">
							<span class="glyphicon glyphicon-collapse-up upDown"></span>
						</a>
				      </div>
				      <div id="content" class="panel-body panel-collapse collapse">
				      	<div class="table-responsive">
						  			<table id="notifTable" class="table table-striped table-bordered table-hover" width="100%">
						  				<col width="5%"/>
						  				<col width="30%"/>
						  				<col width="30%"/>
						  				<col width="30%"/>
						  				<col width="30%"/>
						  				<thead>
									        <tr>
									        	<th width="20">Type</th>
									            <th>Titre</th>
									            <th>Date</th>
									            <th>Details</th>
									            <th>Action</th> 
									        </tr>
									    </thead>
										<tbody id="notifications">
											<?php // Les notifications arrivent ici 
												while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)){
													$notify = new Notification($donnees);
													echo '<tr class="' . $notify->type() . '">
															<td><img src="img/'. $notify->type() . '.png" width="18px" /></td>
															<td>' . $notify->titre() . '</td>
															<td><span style="display: none;"> 20180424165310</span>' . $notify->date() .'</td>
															<td>' . $notify->details() . '</td>
															<td><span id="' . $notify->id() . '" class="glyphicon glyphicon-remove delete"></span></td>
														</tr>';
													array_push($_SESSION['displayed'], $notify->id());
												}
											?>
										</tbody>
										<tfoot>
									        <tr>
									        	<th width="20">Type</th>
									            <th>Titre</th>
									            <th>Date</th>
									            <th>Details</th>
									            <th>Action</th> 
									        </tr>
									    </tfoot>
									</table>
						  		</div>
						  	</div>
				    </div>
				</div>
			</div>
	    </div>
	  </div>
	</div>
	<!-- Fin Contenu -->
	
	<!-- jQuery 1.12.4 -->
    <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script src="dataTables/js/jquery.dataTables.min.js"></script>
    <script src="dataTables/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
    	function parseDate(a) {
    		var b = a.split(' ');
    		var c = b[1].split(':');
		    var ukDatea = b[0].split('-');
		    return (ukDatea[2] + ukDatea[1] + ukDatea[0] + c[2] + c[1] + c[0]) * 1;
		}
    	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
			"date-uk-pre": function ( a ) {
			    return parseDate(a);
			},

			"date-uk-asc": function ( a, b ) {
			    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
			},

			"date-uk-desc": function ( a, b ) {
			    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
			}
		});

    	$(document).ready(function(){
    		$(".banner").height($(window).height() - $("#myNavbar").height() - 1);
    		$(window).on( "resize", function(){
    			var newHeight = $(window).height() - $("#myNavbar").height() - 1;
    			$(".banner").height(newHeight);
    		});    		

    		var notification_id = 1;
			var nbre_global = <?php echo $nbre_notifs;?>;
			var myTable = $("#notifTable").DataTable({
				"aoColumns": [
		            null,
		            null,
		            { "sType": "date-uk" },
		            null,
		            null
		        ],
		        "order": [[ 2, "desc" ]],
		        createdRow: function( row, data, dataIndex ) {
			        // do something
			    }
		    });
			
			// Animation affichage
		    $("#content").collapse();
		    $(".upDown").on( "click", function() {
		    	if($(this).hasClass('glyphicon-collapse-down')){
		    		$(this).addClass('glyphicon-collapse-up');
		    		$( this ).removeClass( 'glyphicon-collapse-down' );
		    	}
		    	else{
		    		$(this).addClass('glyphicon-collapse-down');
		    		$( this ).removeClass( 'glyphicon-collapse-up' );
		    	}
			});
			
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
					        		if(nbre == 1 ){
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
										for (var i = 0; i < resultat.notifications.length; ++i) {
											var row = [
									            '<img src="img/'+ resultat.notifications[i].TYPE + '.png" width="18px" />',
									            resultat.notifications[i].TITRE,
									            '<span style="display: none;">' + parseDate(resultat.notifications[i].DATE) +'</span>' + resultat.notifications[i].DATE,
									            resultat.notifications[i].DETAILS,
									            '<span id="' + resultat.notifications[i].ID + '" class="glyphicon glyphicon-remove delete"></span>'
									        ];
									        var rowNode = myTable.row.add(row).node();
									        $(rowNode).addClass(resultat.notifications[i].TYPE);
									        $(rowNode).find('td').eq(0).addClass("w3-center");
									        $(rowNode).find('td').eq(4).addClass("w3-center");
									        myTable.draw();
									        
											$("#"+alert_id).slideDown("slow");
											setTimeout(function() {
												$("#"+alert_id).animate({
										    	left: "600px",
										    	opacity: '0'}, "slow").hide("slow");
										    }, 15000);	
									    }	
								    }, 1000);
					        	}
					        }
			       		}
			      	},
			       	
			       	error : function(resultat, statut, erreur){
			       		//console.log("erreur");
			    	},
			       	complete : function(resultat, statut){
			       		//console.log(resultat + " : " + statut);
			       	}
			    });
			},3000);

		    // bind click event to dynamically created HTML elements in jQuery
			$("#notifTable tbody").on( "click", "tr td span.delete",  function() {
			    $(this).parent().parent().addClass("selected");
			    myTable.row(".selected").remove().draw(false);
				nbre_global--;
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
				// update database
				$.ajax({
			    	url : 'updateDB.php',
			       	type : 'POST',
			       	data: { id: $(this).attr("id") },
			       	success : function(response, statut){
			       		console.log(response);
			       	},
			       	error : function(resultat, statut, erreur){
			       		//console.log("erreur");
			    	},
			       	complete : function(resultat, statut){
			       		//console.log(resultat + " : " + statut);
			       	}
			    });
			});

			setTimeout(function() {
				$(".myAlerte").animate({
		    	left: "600px",
		    	opacity: '0'}, "slow").hide("slow");
		    }, 5000);
		});
    </script>
</body>
</html>