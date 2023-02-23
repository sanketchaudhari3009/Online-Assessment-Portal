<?php
session_start();
if (!(isset($_SESSION['email'])))
    header("location:index.php");

include "dbConnection.php";

$_SESSION['serialNo'] = 1;
$eid = $_SESSION['eid'];
$q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' ");

if(isset($_SESSION['test_seen'.$eid])){
    header("location:account.php?q=1");
}
else{
    $_SESSION['test_seen'.$eid] = true;
}
while ($row = mysqli_fetch_array($q)) {

    $_SESSION['sahi'] = $row['sahi'];
    $_SESSION['wrong'] = $row['wrong'];
    $_SESSION['total'] = $row['total'];
    $time = $row['time'];
}

        //time samplyavar question skip krayacha rahilay

?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Online Examination</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/vidCapt.js" type="text/javascript"></script>


    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <style>
        video.proctored {
            position: fixed;
            top: 125px;
            right: 0;
            width: 300px;
            height: 165px;
            border: 2px solid red;
        }
    </style>

</head>


<body onload="letsStart()">
    <div class="header">
        <div class="row">
            <div class="col-lg-6">
                <span class="logo">Learn here, lead anywhere...</span>
            </div>
            <div class="col-md-4 col-md-offset-2">

            </div>
        </div>
    </div>
    <div class="bg">

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


            </div><!-- /.container-fluid -->
        </nav>
        <!--navigation menu closed-->

        <div class="container">
            <!--container start-->
            <div class="row">
                <div class="col-md-12">

                    <!--home start-->



                    <!--home closed-->



                    <!--quiz start-->
                    <div style="margin-left:58px" "padding:10px">
                        <button type="input" class="btn btn-danger" style="margin-top: 12px;margin-bottom:12px">
                            <div id="showtime">

                            </div>
                        </button>
                    </div>

                    <div style="margin-left:58px;">
                        <button type="input" class="btn btn-info">PLEASE DO NOT REFRESH PAGE(THIS WILL SIGNOUT YOU)<span class="" aria-hidden="true"></span></button>
                    </div>


                    <div class="panel" style="margin:5%">
                        <div id="questno"><b></b></div>
                        <div id="question"></div>
                        <div id="options">

                        </div>
                        <br />
                        <button type="button" onclick="sumbitAns()" class="btn btn-primary">
                            <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit
                        </button>
                        <button type="button" onclick="submitTestAlert()" class="btn btn-danger mr-auto pull-right">
                            <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit Test
                        </button>
                    </div>





                    <!--quiz end-->



                </div>
            </div>
        </div>
    </div>

    <video autoplay class="proctored" muted></video>
    <p style="display: none;">
        <button id="btnStart">START RECORDING</button><br />
        <button id="btnStop">STOP RECORDING</button>
    </p>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalCenterTitle">Please wait while we process your data</h3>
                    <h5>Or your actions will not be saved.. </h5>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-warning">
                            <h2><span class="sr">Loading...</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <canvas id="myCanvas" width="400" height="350" style="display: none;"></canvas>

    <!--Footer start-->
    <div class="row footer">
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




    <!--footer end-->

    <!-- scripts.. -->
    <!-- scripts.. -->
    <script>
        var questno = 0;
        var qid;
        var srno = 1;
        var skipalert = false;


        function letsStart() {
            // getQuestion();
            startVideo();
            startAudio();
            snapShotinit();
            snapShot();
        }

        function getQuestion() {
            $('#myModal').modal('hide');
            snapShot();
            var dataURL = canvas.toDataURL();
            $.ajax({
                type: "POST",
                data: {
                    imgBase64: dataURL,
                },
                url: "getasynque.php",
            }).done(function(ques) {
                // alert(ques);
                $('#myModal').modal('hide');
                que = JSON.parse(ques);
                ans = que.answer;
                qid = que.questionid;
                document.getElementById("questno").innerHTML = "<b>Question &nbsp;&nbsp;::" + srno + "</b>";
                document.getElementById("question").innerHTML = "<b>&nbsp;" + que.question + "</b><br><br>";
                dum = `<input type="radio" name="ans"  required value= "` + que.option1id + `">&nbsp;` + que.option1 + `<br /><br />
                      <input type="radio" name="ans" value= "` + que.option2id + `">&nbsp;` + que.option2 + `<br /><br />
                      <input type="radio" name="ans" value= "` + que.option3id + `">&nbsp;` + que.option3 + `<br /><br />
                      <input type="radio" name="ans" value= "` + que.option4id + `">&nbsp;` + que.option4 + `<br /><br />`;
                srno++;
                document.getElementById("options").innerHTML = dum;

                setTime();

            });
        }


        var hrs = 0;
        var mins = <?php echo $time ?>;
        var secs = 00;
        var cancelled = false;

        var timer;

        function setTime() {

            var locHrs = hrs;
            var locMins = mins;
            var locSecs = secs;
            refresh2();

            function refresh2() {
                timer = setTimeout(function() {

                    if (locSecs <= 0) {
                        locMins--;
                        locSecs = locSecs + 60;
                    }
                    locSecs--;
                    if(locSecs==30)
                        snapShot();

                    if (locMins < 0) {
                        locHrs--;
                        locMins = locMins + 59;
                    }
                    if (locHrs < 0) {
                        clearTimeout(timer);
                        clearTimeout(timer);
                        $('#myModal').modal('show');
                        // sumbitAns();
                        sumbitAns();
                        return;
                    } else {
                        var mytime = locHrs + " hours " + locMins + " minutes " + locSecs + "  seconds"
                        document.getElementById("showtime").innerHTML = mytime;
                        refresh2();
                    } 
                }, 1000);
            }
        }
    </script>

    <script>
        var qno=1;
        function sumbitAns() {
            qno+=1;
            clearTimeout(timer);
            var ele = document.getElementsByName("ans");
            var useans;
            var i = 0;
            for (i = 0; i < ele.length; i++) {
                if (ele[i].checked) {
                    useans = ele[i].value;
                    break;
                }
            }
            if (useans == null)
                useans = "undefined";
            $.ajax({
                type: "POST",
                data: {
                    ans: useans,
                    qid: qid,
                },
                url: "setquizres.php",
            }).done(function(state) {
                if (state == "1")
                    getQuestion();
                else {
                    submitTest();
                }

            }); 
        }

        function submitTestAlert() {
            var res = confirm("Do you want to end this test?");
            if (res) {
                submitTest();
            }
        }

        function submitTest() {
            if(qno < <?php echo $_SESSION['total'];?>)
            {	
                sumbitAns();
            }
            skipalert = true;
            $("#btnStop").click();
        }
    </script>


    <script>
        var globblob;
        var canvas, ctx;

        function snapShotinit() {
            canvas = document.getElementById("myCanvas");
            ctx = canvas.getContext('2d');
        }

        function snapShot() {
            let video = document.querySelector('video');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        }




        function uploadVid() {

            $('#myModal').modal('show');

            var filename = "temp";
            var data = new FormData();
            data.append('file', globblob);

            $.ajax({
                type: "POST",
                data: data,
                contentType: false,
                processData: false,
                url: "capture.php",
            }).done(function(state) {
                $('#myModal').modal('hide');
                var resultUrl = "<?php echo "account.php?q=result&eid=" . $_SESSION['eid']; ?>";
                window.location.href = resultUrl;
            });


        };
        $(window).on('beforeunload', function() {
            if (skipalert);
            else
                return false;
        });

        var vis = (function() {
            var stateKey, eventKey, keys = {
                hidden: "visibilitychange",
                webkitHidden: "webkitvisibilitychange",
                mozHidden: "mozvisibilitychange",
                msHidden: "msvisibilitychange"
            };
            for (stateKey in keys) {
                if (stateKey in document) {
                    eventKey = keys[stateKey];
                    break;
                }
            }
            return function(c) {
                if (c) document.addEventListener(eventKey, c);
                return !document[stateKey];
            }
        })();


        var warningRaise = 0;

        vis(function() {
            if (!vis()) {
                warningRaise++;

                if (warningRaise > 0) {
                    alert("You reached max limit of tabs/window switch !!");

                    submitTest();
                } else {
                    alert("Please stay on the page..");
                }
            }
        });
    </script>

</body>

</html>