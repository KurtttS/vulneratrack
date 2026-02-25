<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(isset($_POST['firstname'])){

    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/updateuser.php";
    include __DIR__ . "/../processes/updateuserctrl.php";

    $update = new updateuserctrl(
        $_SESSION['userid'],
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['dateofbirth'],
        $_POST['status'],
        $_POST['password'],
        $_POST['confirmpass']
    );

    $now = $update->updateUser();

    // Update session
    $_SESSION['firstName'] = $_POST['firstname'];
    $_SESSION['lastName'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['dateofbirth'] = $_POST['dateofbirth'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['dateupdated'] = $now;

    header("Location: ../user-module/profile.php?update=success");
    exit();
}
