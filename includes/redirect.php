<?php

include_once 'includes/config.php';
$action = filter_input(INPUT_GET, 'action');
//если есть логин в $_SESSION['username'] , определить польз или админ
$username = $_SESSION['username'];
if (isset($_SESSION['username'])) {
    if (is_admin($db, $username)) {
        //если админ , переход на страницу show_admin.php
        //$thisway = 'show_admin.php';
        //return $thisway;
        include_once 'show_admin.php';
    } else {
        //если польз , переход на страницу show.php
        //$thisway = 'show.php';
        //return $thisway;
        include_once 'show_mess.php';
    }
} else {
    if ($action == 'signup') {
        include_once 'signup.php';
    } elseif ($action == 'reg') {
       include_once 'registration.php';;
    } else {
        include_once 'content/content_reg_signup.php';
    }
}

