<?php

session_start();
include "dbConnection.php";


$email = $_SESSION['email'];
if (!(isset($_SESSION['email'])))
	header("location:index.php");

$res = mysqli_query($con, "SELECT * FROM admin where email='$email'");
$rowcount = mysqli_num_rows($res);
if ($rowcount < 1) {
	echo "alert('You don't have access to this page!')";
	header("location:index.php");
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Admin-Show Captures</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<style type="text/css">
		.formdiv {
			position: fixed;
			min-width: 100%;
			/*height: 10%;*/
			padding-top: 3%;
			/*background-color: rgba(0,180,190,0.3);*/
			color: white;
		}

		input {
			text-align: center;
		}

		body {
			/* background-image: url('media/seadmin.jpg'); */
			background-color: #00172D;
			/* background-image: linear-gradient(315deg, #537895 0%, #09203f 74%); */
			background-size: cover;
			background-repeat: no-repeat;
		}

		.regitable {

			padding-top: 10%;
			/* background-color: rgba(210,0,200,0.5); */
			width: auto;
		}

		table {

			color: white;
			text-align: center;
			border: 1px solid black;
			height: 100%;

		}

		tr td {
			/*width: 80%;*/
			/*padding-left: 10px;*/
			border-radius: 5px 4px;
			background-color: rgba(0, 100, 120, 0.4);
			text-align: center;
		}

		th {
			background-color: rgba(0, 100, 120, 0.6);
			font-size: 18px;
			width: 500px;
			border-radius: 5px 4px;
			text-align: center;
		}

		#myInput {
			background-color: rgba(0, 100, 120, 0.3);
			color: white;
			border-radius: 4px 15px;
		}

		.playbut {
			background-color: rgba(0, 100, 120, 0.3);
			width: 80%;
		}
		#pozGallery img{
			float: top;
			padding: 5px;
			position: relative;
			height:200px;
			width:200px;
		}

	</style>

</head>

<body>


	<div class="regitable" align="center">

		<input type="text" class="form-control" id="myInput" placeholder="Search" style="width: 60%;"><br>

		<table id="myTable" style="width: 95%">
			<thead>
				<tr>
					<th scope="col">Sr no</th>
					<th scope="col">Email ID</th>
					<th scope="col">Name</th>
					<th scope="col">Test</th>
					<th scope="col">Score</th>
					<th scope="col">Date</th>
					<th scope="col">Video</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT history.email, user.name,quiz.title,history.score,history.date,captures.src,quiz.eid FROM user INNER JOIN history ON history.email=user.email INNER JOIN quiz ON quiz.eid=history.eid INNER JOIN captures ON history.email=captures.email and quiz.eid =captures.eid";
				$result = mysqli_query($con, $sql);
				$no=1;

				echo "<form method='GET' name='form2' action='showgallery.php'>";

				while ($row = mysqli_fetch_assoc($result)) { ?>
					<tr>
						<td scope="row"><?php echo $no ?></td>
						<td scope="row"><?php echo $row['email'] ?></td>
						<td scope="row"><?php echo $row['name'] ?></td>
						<td scope="row"><?php echo $row['title'] ?></td>
						<td scope="row"><?php echo $row['score'] ?></td>
						<td scope="row"><?php echo $row['date'] ?></td>
						<td scope='row'><button type='submit'  onclick="playVid('<?php echo $row['src'] ?>','<?php echo $row['name'] ?>')" 
						class='playbut'>SHOW</button> </td>
					<?php $no=$no+1; ?>

					</tr>
					
				<?php } ?>
						<input type="hidden" name="path" id="path">
						<input type="hidden" name="candiname" id="candiname">
						</form>

			</tbody>
		</table>



	</div> <!-- regitable-->


	


	<!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
	<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
	<script>
		$(document).ready(function() {
			$("#myInput").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#myTable tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});

		function playVid(vidsrc,name) {
			  var gallery = document.getElementById("pozGallery");

			  	$('#path').val(vidsrc);
			  	$('#candiname').val(name);
				var f = document.getElementById("form2");
				f.submit();


		}
		
	</script>

</body>

</html>