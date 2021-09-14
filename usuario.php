<?php

require 'includes/config/database.php';
$db=conectarDB();

$email = "correo@correo.com";
$password = '12345';

$passwordhash = password_hash($password,PASSWORD_BCRYPT);


$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordhash}')";
exit;

mysqli_query($db , $query);

