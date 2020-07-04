<?php

require_once '../model/Publication.Class.php';

if(isset($_GET['new_comment'])) {
    session_start();
    $picture_id = $_POST['pid'];
    $comment = trim($_POST['comment']);
    $author_id = $_SESSION['userid'];
    $author_username = $_SESSION['username'];
    $email = $_POST['email'];
    $notifications = $_POST['notif'];
    $username = $_POST['author'];
    $pub = new Publication;
    $pub->comment_publication($picture_id,$author_id,$author_username,$comment);
    $pub->send_notification($picture_id,$author_username,$email,$notifications,$username);
    header('Location:../publication.php?pid='.$picture_id);
}

if(isset($_GET['like'])) {
    session_start();
    $picture_id = $_GET['pid'];
    $author_id = $_SESSION['userid'];
    $pub = new Publication;
    $pub->like($author_id, $picture_id);
    header('Location:../publication.php?pid='.$picture_id);
}

if(isset($_GET['unlike'])) {
    session_start();
    $picture_id = $_GET['pid'];
    $author_id = $_SESSION['userid'];
    $pub = new Publication;
    $pub->unlike($author_id, $picture_id);
    header('Location:../publication.php?pid='.$picture_id);
}

if(isset($_GET['del_pub'])) {
    session_start();
    $picture_id = $_GET['pid'];
    $author_id = $_SESSION['userid'];
    $pub = new Publication;
    $pub->delete_publication($author_id, $picture_id);
    if($_GET['del_pub'] == "camera") {
        header('Location:../camera.php');
    } else if ($_GET['del_pub'] == "upload") {
        header('Location:../upload.php');
    } else {
        header('Location:../home.php');
    }
}

if(isset($_GET['del_com'])) {
    session_start();
    $comment_id = $_POST['comment_id'];
    $picture_id = $_POST['pid'];
    $author_id = $_SESSION['userid'];
    $pub = new Publication;
    $pub->delete_selected_comment($comment_id);
    header('Location:../publication.php?pid='.$picture_id);
}