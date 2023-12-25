<?php
$pass = $_GET['pass'];
$key = 'Ureb91MAjs731n';
$md5_pass = md5(md5($pass).$key);
echo $pass.'<br/>';
echo $md5_pass;
?>