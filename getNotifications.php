<?php
	session_start();
	if(isset($_SESSION['login'])){
		//require "requiredClasses.php";
		if(!isset($_SESSION['displayed']))
			$_SESSION['displayed'] = [];

		try{
			$db = new PDO('mysql:host=localhost;dbname=notif_db', 'root', '');
			// les erreurs lanceront des exceptions
			$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
			$requete = "SELECT * FROM notification AS notif WHERE notif.ID IN (SELECT relation.ID_NOTIFICATION FROM user_notification AS relation WHERE relation.ID_USER = (SELECT ID FROM user WHERE LOGIN = :login) AND relation.READED= 0 ) ";
			$statement = $db->prepare($requete);
			$statement->bindValue(':login', $_SESSION['login']);
			$statement->execute();	
			if($statement->rowCount() != 0){
				$notifications = [];
				while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)){
					$notThere=true;
					for ($i=0; $i < count($_SESSION['displayed']) ; $i++) { 
						if( $_SESSION['displayed'][$i] == $donnees['ID'])
							$notThere=false;
					}
					if($notThere){
			      		$notifications[] = $donnees;
			      		array_push($_SESSION['displayed'], $donnees['ID']);
					}
				}
				$arr = array ('notify'=> 'notify', 'notifications'=> $notifications);
				echo json_encode($arr);
			}
			else{
				$arr = array ('notify'=>'doNotNotify');
				echo json_encode($arr);
			}
		}
		catch(Exception $e){
			echo "Échec : " . $e->getMessage() . "<br/>";
		}
	}
	else{
		$arr = array ('notify'=>'doNotNotify');
		echo json_encode($arr);
	}

?>