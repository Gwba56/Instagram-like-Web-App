<?php
 
require_once 'config/database.php';
require_once 'model/Publication.Class.php';
require_once 'model/Page.Class.php';
try  {
    $db = new PDO ($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    session_start();
    session_unset();
    session_destroy();
    require_once 'config/setup.php';
    require_once 'register.php';
}
if(!isset($_SESSION)) {
    session_start();
}
require 'home.php';