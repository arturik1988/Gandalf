<?php
require_once 'app/init.php';

$auth = new Auth;
$auth->signout();

header('Location: index.php');