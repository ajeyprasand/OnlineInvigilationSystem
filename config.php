<?php 

$db_user="root";
$db_pass="viki@2002";
$db_name="project";

$db = new PDO('mysql:host=localhost;dbname=' .$db_name . ';charset=utf8', $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);