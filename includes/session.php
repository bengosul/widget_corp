<?php
	session_start();

	function message(){
	 if(isset($_SESSION["message"])){
		 echo "<div class=\"message\">";
		 echo htmlentities($_SESSION["message"]);
		 echo "</div>";
	 	$_SESSION["message"]=null;
	 }
	}


	function errors(){
	 if(isset($_SESSION["errors"])){
		 $output = $_SESSION["errors"];
		 
		 $_SESSION["errors"]=null;

		 return $output;
	 }
	}

?>

