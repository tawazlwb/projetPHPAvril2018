<?php
	session_start();
	if(!isset($_SESSION['displayed']))
			$_SESSION['displayed'] = [];

	function isThere($value, $tab){
		for($i=0; $i<count($tab); $i++){
			if($value == $tab[$i])
				return $i;
		}
		return -1;
	}
	if(isset($_POST['id']) && isset($_SESSION['login'])){
		try{
			$db = new PDO('mysql:host=localhost;dbname=notif_db', 'root', '');
			// les erreurs lanceront des exceptions
			$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
			$requete = "UPDATE user_notification SET READED=1 WHERE ID_USER = (SELECT ID FROM user WHERE LOGIN = :login ) AND ID_NOTIFICATION = :id";
			$statement = $db->prepare($requete);
			$statement->bindValue(':id', $_POST['id']);
			$statement->bindValue(':login', $_SESSION['login']);
			$statement->execute();	
			
			if($statement->rowCount() == 1){
				echo "Le status de la notification est mis à jour!";
			}
			
			if(isset($_SESSION['displayed'])){
				$index = isThere($_POST['id'], $_SESSION['displayed']);
				if($index != -1){
					$tab = [];
					for($i=0; $i<count($_SESSION['displayed']); $i++){
						if($_SESSION['displayed'][$index] != $_SESSION['displayed'][$i])
							$tab[] = $_SESSION['displayed'][$i];
					}
					$_SESSION['displayed'] = $tab; 
				}
			}
		}
		catch(Exception $e){
			echo "Échec : " . $e->getMessage() . "<br/>";
		}
	}
	else{
		echo "erreur : données manquantes!";
	}
?>