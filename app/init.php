<?php
// Start sessions
session_start();

// The app directory
$app = __DIR__;

// Import config
$config = require "{$app}/config.php";

// Class autoloading
spl_autoload_register(function($class) use ($app) {
    require "{$app}/classes/{$class}.php";
});