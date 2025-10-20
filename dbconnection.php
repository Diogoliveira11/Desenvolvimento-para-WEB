<?php
$db_name = 'espacolusitano';
$link = mysqli_connect('localhost','root','',$db_name);

if (!$link) {
    die('Could not connect: ' . mysqli_connect_error());
}
?>