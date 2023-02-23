<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Online Examination</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/font.css">
  <script src="js/jquery.js" type="text/javascript"></script>


  <script src="js/bootstrap.min.js" type="text/javascript"></script>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
  <!--alert message-->
  <?php if (@$_GET['w']) {
    echo '<script>alert("' . @$_GET['w'] . '");</script>';
  }
  ?>
  <!--alert message end-->

</head>
<?php
include_once 'dbConnection.php';
?>

<body style="background-image:none; background-color: #eeeeee;">
  <div class="header">
    <div class="row">
      <div class="col-lg-6">
        <span class="logo">Learn here, lead anywhere...</span>
      </div>
      <div class="col-md-4 col-md-offset-2">
        <?php
        include_once 'dbConnection.php';
        session_start();
        if (!(isset($_SESSION['email']))) {
          header("location:index.php");
        } else {
          $name = $_SESSION['name'];
          $email = $_SESSION['email'];

          include_once 'dbConnection.php';
          echo '<span class="pull-right top title1" ><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Hi,</span><a href="account.php?q=1" class="log log1">' . $name . '</a>&nbsp;|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Signout</button></a></span>';
        } ?>
      </div>
    </div>
  </div>
  <div class="bg" >

    <!--navigation menu-->
    <nav class="navbar navbar-default title1">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><b>Dashboard</b></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li onclick="sendData2()" <?php if (@$_GET['q'] == 1) echo 'class="active"'; ?>><a href="account.php?q=1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span></a></li>
            <!--
        <li <?php if (@$_GET['q'] == 2) echo 'class="active"'; ?>><a href="account.php?q=2"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History</a></li>
    <li <?php if (@$_GET['q'] == 3) echo 'class="active"'; ?>><a href="account.php?q=3"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking</a></li>
    <li class="pull-right"> <a href="logout.php?q=account.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Signout</a></li>
    -->
          </ul>
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" id="hashtagsearch" class="form-control" placeholder="Enter tag/keyword ">
            </div>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search</button>

          </form>

        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <!--navigation menu closed-->

    <div class="container" >
      <!--container start-->
      <div class="row">
        <div class="col-md-12">

          <!--home start-->
          <?php if (@$_GET['q'] == 1) {

            $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            echo  "<div class='panel'><div class='table-responsive'><table class='table table-striped title1' id='quiztable'>
              <tr><th><b>S.N.</b></th><th><b>Topic</b></th><th><b>Total question</b></th><th><b>Marks</b></th><th><b>
              Time limit per Question</b></th><th></th><th style='display:none'>hashtags</th></tr>";
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $title = $row['title'];
              $total = $row['total'];
              $sahi = $row['sahi'];
              $time = $row['time'] . ":00";
              $eid = $row['eid'];
              $tag = $row['tag'];
              error_reporting(E_ERROR | E_PARSE);
              $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
              $rowcount = mysqli_num_rows($q12);
              if ($rowcount == 0) {
                echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $total . '</td><td>' . $sahi * $total . '</td><td>' . $time . '&nbsp;min</td>
  <td><b><a href="account.php?q=quiz&step=2&eid=' . $eid . '" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start
  </b></span></a></b></td><td style="display:none">' . $tag . '</td></tr>';
              } else {
                echo '<tr style="color:#99cc32"><td>' . $c++ . '</td><td>' . $title . '&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>' . $total . '</td><td>' . $sahi * $total . '</td><td>' . $time . '&nbsp;min</td>
  <td><b><a href="#"  class="pull-right btn sub1" style="margin:0px;background:red" disabled><span class="glyphicon glyphicon-repeat" aria-hidden="true" disabled></span>&nbsp;<span class="title1"><b>Restart</b></span></a></b></td><td style="display:none">' . $tag . '</td></tr>';
              }
            }
            $c = 0;
            echo '</table></div></div>';
          } ?>
          <!--home closed-->



          <!--quiz start-->
          <?php

          if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
            // ithe eid set krava lagel...
            $_SESSION['eid'] = $_GET['eid'];
            header("Location: quiz.php");
            // echo "Kahitri ye babas";
          }


          ?>

          <?php

          //result display
          if (@$_GET['q'] == 'result' && @$_GET['eid']) {
            $eid = @$_GET['eid'];
            $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
            $row = mysqli_fetch_array($q);
            if (mysqli_num_rows($q) > 0) {

            echo  '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

            
              
              $s = $row['score'];
              $w = $row['wrong'];
              $r = $row['sahi'];
              $qa = $row['level'];
              $unattempt = $_SESSION['total']-$qa;
              $grandtotal = $_SESSION['total'];
              echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>' . $grandtotal . '</td></tr><tr style="color:#66CCFF"><td>Attempted Questions</td><td>' . $qa . '</td></tr>
              <tr style="color:#66CCFF"><td>Unattempted Questions</td><td>' . $unattempt . '</td></tr>      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>' . $r . '</td></tr> 
    <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>' . $w . '</td></tr>
    <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';
            
            $q = mysqli_query($con, "SELECT * FROM rank WHERE  email='$email' ") or die('Error157');
            while ($row = mysqli_fetch_array($q)) {
              $s = $row['score'];
            }

            echo '</table></div>';
            echo "<button type='button' id='sendmailbut' onclick='sendEmail()' class='btn btn-danger mr-auto pull-right' style='margin-right:40px'>
  <span class='glyphicon glyphicon-envelope' aria-hidden='true'></span><span id='mailname'>&nbsp;Mail Me</span></button>";
          }
          else{
              echo  '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';


                $s = 0;
              $w = 0;
              $r = 0;
              $qa = 0;
              echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>' . $qa . '</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>' . $r . '</td></tr> 
    <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>' . $w . '</td></tr>
    <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';

            echo '</table></div>';
      
          }

        }
          ?>

          <!--quiz end-->

          <?php
          //history start
          if (@$_GET['q'] == 2) {
            $q = mysqli_query($con, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
            echo  '<div class="panel title">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>S.N.</b></td><td><b>Quiz</b></td><td><b>Question Solved</b></td><td><b>Right</b></td><td><b>Wrong<b></td><td><b>Score</b></td>';
            $c = 0;
            while ($row = mysqli_fetch_array($q)) {
              $eid = $row['eid'];
              $s = $row['score'];
              $w = $row['wrong'];
              $r = $row['sahi'];
              $qa = $row['level'];
              $q23 = mysqli_query($con, "SELECT title FROM quiz WHERE  eid='$eid' ") or die('Error208');
              while ($row = mysqli_fetch_array($q23)) {
                $title = $row['title'];
              }
              $c++;
              echo '<tr><td>' . $c . '</td><td>' . $title . '</td><td>' . $qa . '</td><td>' . $r . '</td><td>' . $w . '</td><td>' . $s . '</td></tr>';
            }
            echo '</table></div>';
          }

          //ranking start
          if (@$_GET['q'] == 3) {
            $q = mysqli_query($con, "SELECT * FROM rank  ORDER BY score DESC ") or die('Error223');
            echo  '<div class="panel title"><div class="table-responsive">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>Rank</b></td><td><b>Name</b></td><td><b>Gender</b></td><td><b>College</b></td><td><b>Score</b></td></tr>';
            $c = 0;
            while ($row = mysqli_fetch_array($q)) {
              $e = $row['email'];
              $s = $row['score'];
              $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e' ") or die('Error231');
              while ($row = mysqli_fetch_array($q12)) {
                $name = $row['name'];
                $gender = $row['gender'];
                $college = $row['college'];
              }
              $c++;
              echo '<tr><td style="color:#99cc32"><b>' . $c . '</b></td><td>' . $name . '</td><td>' . $gender . '</td><td>' . $college . '</td><td>' . $s . '</td><td>';
            }
            echo '</table></div></div>';
          }
          ?>



        </div>
      </div>
    </div>
  </div>
  <!--Footer start-->
  <div class="row footer" style=" position:absolute;
   bottom:0;
   width:100%;
   height:50px; 
   width:1725px;
   float: center;" >  
    <div class="col-md-4 box">
      <a href="#">About us</a>
    </div>
    <div class="col-md-4 box">
      <a href="#" data-toggle="modal" data-target="#login">Admin Login</a>
    </div>

    <div class="col-md-3 box">
      <a href="feedback.php" target="_blank">Feedback</a>
    </div>
  </div>
  <!-- Modal For Developers-->
  <div class="modal fade title1" id="developers">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" style="font-family:'typo' "><span style="color:orange">Developers</span></h4>
        </div>

        <div class="modal-body">
          <p>
          <div class="row">
            <div class="col-md-4">
              <img src="image/bg1.jpg" width=100 height=100 alt="Sunny Prakash Tiwari" class="img-rounded">
            </div>
            <div class="col-md-5">
              <a href="" style="color:#202020; font-family:'typo' ; font-size:18px" title="Find on Facebook"></a>
              <h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="title1"></h4>
              <h4 style="font-family:'typo' "></h4>
              <h4 style="font-family:'typo' "></h4>
            </div>
          </div>
          </p>
        </div>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!--Modal for admin login-->
  <div class="modal fade" id="login">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title"><span style="color:orange;font-family:'typo' ">LOGIN</span></h4>
        </div>
        <div class="modal-body title1">
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <form role="form" method="post" action="admin.php?q=index.php">
                <div class="form-group">
                  <input type="text" name="uname" maxlength="20" placeholder="Admin user id" class="form-control" />
                </div>
                <div class="form-group">
                  <input type="password" name="password" maxlength="15" placeholder="Password" class="form-control" />
                </div>
                <div class="form-group" align="center">
                  <input type="submit" name="login" value="Login" class="btn btn-primary" />
                </div>
              </form>
            </div>
            <div class="col-md-3"></div>
          </div>
        </div>
        <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--footer end-->

  <script>
    // <!-- filter -->
    $(document).ready(function() {

      $("#hashtagsearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#quiztable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });



    function sendEmail() {
      document.getElementById("sendmailbut").disabled = true;
      document.getElementById("mailname").textContent = "Please wait";
      $.ajax({
        type: "GET",
        url: "sendEmail.php",
      }).done(function(state) {
        if (state == 1) {
          alert("Result mail has been sent to you..");
          document.getElementById("mailname").textContent = "Mail sent!";

        } else {
         
          document.getElementById("sendmailbut").disabled = false;
          alert("Something went wrong! Please try again..");
          document.getElementById("mailname").textContent = "Mail me";

        }
      });
    }
  </script>

</body>

</html>