<?php
session_start();

$_SESSION = [];
session_destroy();

header("Location: crud-user/views/login.php");
exit();
