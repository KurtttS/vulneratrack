<?php
error_reporting(E_ALL);
require_once __DIR__ . '/../bootstrap.php';
ini_set('display_errors', 1);
session_start();

if(isset($_POST['firstname'])){
    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/updateuser.php";
    include __DIR__ . "/../processes/updateuserctrl.php";

    $area = !empty($_POST['area']) ? $_POST['area'] : $_SESSION['area_name'];
    $lat  = !empty($_POST['latitude']) ? $_POST['latitude'] : $_SESSION['latitude'];
    $lng  = !empty($_POST['longitude']) ? $_POST['longitude'] : $_SESSION['longitude'];

    $update = new updateuserctrl(
        $_SESSION['userid'],
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['dateofbirth'],
        $_POST['status'],
        $_POST['address'],
        $_POST['contactnum'],
        $lat,  
        $lng,  
        $area,
        $_POST['password'],
        $_POST['confirmpass']
    );

    $now = $update->updateUser();

    // Update standard session strings
    $_SESSION['firstName'] = $_POST['firstname'];
    $_SESSION['lastName'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['dateofbirth'] = $_POST['dateofbirth'];
    $_SESSION['contactnum'] = $_POST['contactnum'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['latitude'] = $lat;
    $_SESSION['longitude'] = $lng;
    $_SESSION['area_name'] = $area;
    $_SESSION['dateupdated'] = $now;

    $_SESSION['household_members'] = $update->getUpdatedHouseholdMembers($_SESSION['userid']);
    
    header("Location: ../user-module/profile.php?update=success");
    exit();
}
?>