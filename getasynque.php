<?php

include "dbConnection.php";
session_start();

$eid = $_SESSION['eid'];
$sn = $_SESSION["serialNo"];

$tim = time();

 
$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
$photofold = "captures/" . $_SESSION['email'] . $eid . date("Y_m_d", $tim);

if (is_dir($photofold) === false) {
    mkdir($photofold);
}
$fileName = $photofold."/".$sn . ".png";

$success = file_put_contents($fileName, $fileData);



$q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' ") or die('Error');

$row = mysqli_fetch_array($q);
$qns = $row['qns'];
$qid = $row['qid'];

$q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ") or die('Error');

$options = array();
$optionids = array();
while ($row = mysqli_fetch_array($q)) {
    array_push($options, $row['option']);
    array_push($optionids, $row['optionid']);
}

$data = array(
    'question' => $qns, 'questionid' => $qid,
    'option1' => $options[0], 'option1id' => $optionids[0],
    'option2' => $options[1], 'option2id' => $optionids[1],
    'option3' => $options[2], 'option3id' => $optionids[2],
    'option4' => $options[3], 'option4id' => $optionids[3]
);

echo json_encode($data);
