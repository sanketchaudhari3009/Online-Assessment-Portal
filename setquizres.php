<?php

include "dbConnection.php";
session_start();        
//quiz start
$eid=$_SESSION['eid'];        
$sn=$_SESSION['serialNo'];
$sahi = $_SESSION['sahi'];
$wrong = $_SESSION['wrong'];
$total=$_SESSION['total'];    
$email =$_SESSION['email']; 

$ans=$_POST['ans'];
$qid=@$_POST['qid'];
 
$q=mysqli_query($con,"SELECT * FROM answer WHERE qid='$qid' " );

while($row=mysqli_fetch_array($q)){
    $ansid=$row['ansid'];
}

if($ans == $ansid){
   

    if($sn == 1){
        $q=mysqli_query($con,"INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW())")or die('Error');
    }

    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' ")or die('Error115');

    while($row=mysqli_fetch_array($q)){
        $s=$row['score'];
        $r=$row['sahi'];
    }
    $r++;
    $s=$s+$sahi;
    $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date= NOW()  WHERE  email = '$email' AND eid = '$eid'")or die('Error124');

} 
else{

    
    if($sn == 1){
        $q=mysqli_query($con,"INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW() )")or die('Error137');
    }

    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error139');
    while($row=mysqli_fetch_array($q) ){
        $s=$row['score'];
        $w=$row['wrong'];
    }
    $w++;
    $s=$s-$wrong;
    $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'")or die('Error147');
}


if($_SESSION["serialNo"] >= $total){
    echo "-1";
    }
else{
   $_SESSION ["serialNo"]++;
    echo "1";
}


?>