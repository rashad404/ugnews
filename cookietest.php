<?php


setcookie('lang', '22044f935519516a8910ce0b8c872b0ce4f10e2c~az', '1733669631', '', '', '', '');

// Set a cookie named "user" with the value "JohnDoe" that expires in 1 hour, available on the entire domain, and accessible via HTTP only.
setcookie("user", "JohnDoe", time() + 3600, "/", "", true, true);

// Retrieve the value of the "user" cookie
if (isset($_COOKIE["lang"])) {
    $username = $_COOKIE["lang"];
    echo "!!!Welcome back, $username!";
} else {
    echo "Cookie not set.";
}
