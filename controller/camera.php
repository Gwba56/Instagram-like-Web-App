<?php

require_once '../model/Montage.Class.php';

if(isset($_GET['takepic'])) {
    session_start();
    $montage = new Montage;
    $path = $montage->treat_picture($_POST['photo'], $_POST['sticker']);
    $montage->store_picture($path,$_SESSION['userid'],$_SESSION['username']);
    header('Location:../camera.php');
}

if(isset($_GET['uploadpic'])) {
    session_start();
    $maxsize = 500000;
    $format = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
    if ($_FILES['uploadPic']['size'] >= $maxsize) {
        $error_1 = 'Sorry, your file is too large. 500 KB max.';
    } elseif ($_FILES['uploadPic']['size'] == 0) {
        $error_2 = 'Invalid File';
    } elseif (!in_array($_FILES['uploadPic']['type'], $format)) {
        $error_3 = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
    }
    $montage = new Montage;
    $path = $montage->treat_picture($_POST['photo'], $_POST['sticker']);
    $montage->store_picture($path,$_SESSION['userid'],$_SESSION['username']);
    header('Location:../upload.php');
}

