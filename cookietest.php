<?php
ini_set("display_errors",1);
error_reporting(E_ALL);


echo 2;
// Set a cookie named "user" with the value "JohnDoe" that expires in 1 hour, available on the entire domain, and accessible via HTTP only.
setcookie("user", "JohnDoe", time() + 3600, "/", "", true, true);

// Retrieve the value of the "user" cookie
if (isset($_COOKIE["user"])) {
    $username = $_COOKIE["user"];
    echo "Welcome back, $username!";
} else {
    echo "Cookie not set.";
}
