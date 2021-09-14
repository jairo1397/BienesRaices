<?php

function conectarDB():mysqli {

    
    $db = mysqli_connect('localhost', 'root','','bienes_raices');
    if(!$db)
    {
        echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus datos.</h3><hr><br>";
        exit;
    }
  return $db;
}