    <?php

    session_start();
    include "dbConnection.php";

    $email = $_SESSION['email'];
    $eid = $_SESSION['eid'];

    $tim = time();
    $filename = $email.$eid.date("Y_m_d",$tim).".mp3";

    if(($_FILES['file']) and !$_FILES['file']['error']){

        move_uploaded_file($_FILES['file']['tmp_name'], "captures/audio/" . $filename);
    }


    $del="DELETE FROM captures where   email='$email' and eid='$eid' ";
    mysqli_query($con,$del) or die("Error!");
        
    
    $sql = "INSERT INTO `captures`(`eid`, `email`, `src`) VALUES('$eid','$email','$filename')";
    $res = mysqli_query($con,$sql) or die("Error!");


    echo "Done from my side..";
?>