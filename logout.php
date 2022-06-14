<?php
require "./function.php";

session_start();
checkLoginUser();

$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy();
redirect("./login.php");
exit();
