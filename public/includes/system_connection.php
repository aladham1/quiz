<?php
$conn = mysqli_connect('localhost','root','XpeoTi8GXV','admin_default') or die('failed connect');
//mysqli_select_db($con,'asha');

$sSQL= 'SET CHARACTER SET utf8mb4'; 


mysqli_query($conn,"SET NAMES utf8mb4");
mysqli_query($conn,$sSQL) 
?>