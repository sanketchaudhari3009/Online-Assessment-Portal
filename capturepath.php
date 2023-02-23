<?php 	

	session_start();
	include "dbConnection.php";

		$_SESSION['src']="";
		//$userid = $_SESSION['uid'];
		//$phone = $_SESSION['phone'];
		$minTime = 0;
		$secTime = 0;
		$result = NULL;
		if($_SERVER["REQUEST_METHOD"] == "GET"){
			$folder = "captures/".substr($_GET['src'],0,-4)."/";
			
			$dirname = $folder;
			$images = glob($dirname."*.png");
			

			// $data=substr($_GET['src'],6,-4);
		}

	echo json_encode($images);

?>