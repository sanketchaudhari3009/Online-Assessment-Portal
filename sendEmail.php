<?php
require_once 'vendor/autoload.php';
include "dbConnection.php";

session_start();

$eid = $_SESSION['eid'];
$email = $_SESSION['email'];
$title = "";
try {

    $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
    while ($row = mysqli_fetch_array($q)){

       $s = $row['score'];
              $w = $row['wrong'];
              $r = $row['sahi'];
              $qa = $row['level'];
              $unattempt = $_SESSION['total']-$qa;
              $grandtotal = $_SESSION['total'];

    }

    $result = mysqli_query($con, "SELECT * FROM quiz where `eid`='$eid'") or die('Error');
    while ($row = mysqli_fetch_array($result)){
        $title = $row['title'];
    }

   

    $msg = "Hello " . $_SESSION['name'] . ",\n";

    $msg .= "Here is your result of the test : $title 
        Total Questions : $grandtotal
	Attempted Questions : $qa
	Unattempted Questions : $unattempt
        Right Answers : $r
        Wrong Answers : $w
        Final Score : $s\n\n
    Thanks & Regards,
    domain name goes here..
    www.domain.com
";


    $transport = (new Swift_SmtpTransport('smtp.gmail.com',465, 'ssl'))
        ->setUsername('onlineexamportalin@gmail.com')
        ->setPassword('kfcadlkmcgcqjhjm');

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message();

    $headers = $message->getHeaders();

    $message->setSubject('Test result from Online Exam Portal');
    $message->setFrom(['onlineexamportalin@gmail.com' => 'Online Exam']);

    // // Set the "To address" [Use setTo method for multiple recipients, argument should be array]
    //for now .. this shoul be commented and 
    $message->addTo($email, $_SESSION['name']);

    //This should be uncommented 
    // $message->addTo($email, $_SESSION['name']);

    $message->setBody($msg);
    
    $result = $mailer->send($message);
    echo "1";
} catch (Exception $e) {
    echo $e;
}
