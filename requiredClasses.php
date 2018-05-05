<?php
	function chargerMesClasse($classe){
  		require "entities/" . $classe . ".php";
	}
	spl_autoload_register("chargerMesClasse");
?>