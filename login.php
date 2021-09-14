<?php

require 'includes/config/database.php';
$db=conectarDB();

$errores = [];

//autenticar el usuario 

if($_SERVER['REQUEST_METHOD'] == 'POST'){



    $email =mysqli_real_escape_string($db,filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) ) ;
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(!$email){
        $errores[]="El email no es valido";
    }
    if(!$password){
        $errores[]="El password es obligatorio";
    }

    if(empty($errores)){

        //revisar si el usuario existe 
        $query = "SELECT * FROM usuarios where email = '${email}' ";
        $resultado = mysqli_query($db,$query);

        
        if( $resultado->num_rows){
            //revisar si el password es correcto
            $usuario = mysqli_fetch_assoc($resultado);

            
            //verificar si el password es correcto
            $auth = password_verify($password,$usuario['password']);
            if($auth){
                session_start();
                //llenar el arreglo de la sesion   
                $_SESSION['usuario']=$usuario['email'];
                $_SESSION['login']="true";
                header('Location: /admin');

            }else{
                $errores[]="El password es incorrecto";
            }
        }else {
            $errores[]=" El usuario no existe ";
        }
    }

}


//incluye el header
    require 'includes/funciones.php';
    incluirTemplate('header');

?>

    <main class="contenedor seccion contenido-centrado">
      <h1>Iniciar Sesion</h1>

      <?php foreach($errores as $error):?>
        <div class ="alerta error">
            <?php echo $error;?>

        </div>
      <?php endforeach;?>          
      <form class="formulario" method="POST" action="">
            <fieldset>
                <legend>Email y password</legend>

                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Email" id="email" required>

                <label for="email">password</label>
                <input type="password" name="password" placeholder="Password" id="password" required>

            </fieldset>



            <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
        </form>
    </main>

    <?php
  incluirTemplate('footer');
?>

