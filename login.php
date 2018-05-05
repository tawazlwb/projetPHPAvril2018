<?php
	session_start();
	if(isset($_POST["log"]) && isset($_POST["pass"])) {

		require "requiredClasses.php";
		
		try{
			$db = new PDO('mysql:host=localhost;dbname=notif_db', 'root', '');
			// les erreurs lanceront des exceptions
			$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
			$requete = "SELECT * FROM user WHERE LOGIN= :login AND PASSWORD= :pass";
			$statement = $db->prepare($requete);
			$statement->bindValue(':login', $_POST["log"]);
			$statement->bindValue(':pass', $_POST["pass"]);
			$statement->execute();
			
			if($statement->rowCount() == 0){
				echo "failure";	
			}
			else{
				$donnees = $statement->fetch(PDO::FETCH_ASSOC);
				$user = new User($donnees);
				$_SESSION['login'] = $user->login();
				echo "success";
			}
		}
		catch(Exception $e){
			echo "Échec : " . $e->getMessage() . "<br/>";
		}
	}
?>