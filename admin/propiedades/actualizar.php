<?php

require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }
    // Conectar a la base de datos 
    require '../../includes/config/database.php';
    $db=conectarDB();

    $id=$_GET["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header("Location: /admin");
    }

    //consulta para poner en los campos
    $consulta_a="SELECT * FROM propiedades WHERE id = ${id}";
    $resultado_a=mysqli_query($db,$consulta_a);
    $propiedad= mysqli_fetch_assoc($resultado_a);

    //consulta para obtener los vendedores
    $consulta="SELECT * FROM vendedores";
    $resultado_v=mysqli_query($db,$consulta);

    // Arreglo con mensajes de errores
    $errores = [];

    $titulo = $propiedad["titulo"];
    $precio = $propiedad["precio"];
    $descripcion = $propiedad["descripcion"];
    $habitaciones = $propiedad["habitaciones"];
    $wc = $propiedad["wc"];
    $estacionamiento =$propiedad["estacionamiento"];
    $vendedores_id = $propiedad["vendedores_id"];
    $imagenPropiedad = $propiedad['imagen'];

    // Ejecutar el codigo despues de que el usuario envia el formulario
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      // echo "<pre>";
      // var_dump($_POST);
      // echo "</pre>";

      // echo "<pre>";
      // var_dump($_FILES);
      // echo "</pre>";




      //sanitizar(evitar inyeccion sql) con mysqli_real_escape_string

      $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
      $precio = mysqli_real_escape_string($db, $_POST['precio']);
      $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
      $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
      $wc = mysqli_real_escape_string($db, $_POST['wc']);
      $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
      $vendedores_id = mysqli_real_escape_string($db, $_POST['vendedores_id']);
      $creado =date('Y-m-d');
     
      //
 
      $imagen = $_FILES['imagen'];
      

      if(!$titulo){
         $errores[]="Debes añadir un titulo"; 
      }
      if(!$precio){
        $errores[]="EL precio es obligatorio";
      }
      if(strlen($descripcion) < 50){
        $errores[]="La descripcion es obligatoria, debe tener al menos 50 caracteres.";
      }
      if(!$habitaciones){
        $errores[]="El numero de habitaciones es obligatorio";
      }
      if(!$wc){
        $errores[]="El numero de baños es obligatorio";
      }
      if(!$estacionamiento) {
        $errores[]="El numero de estacionamientos es obligatorio";
      }
      if($vendedores_id == null){
        $errores[]="Debe seleccionar un vendedor";
      }

      // if(!$imagen['name']){
      //   $errores[]="La imagen es obligatoria";
      // }

        //validar por tamaño maximo la imagne
        $medida = 1080*1000;
      
      if($imagen['size'] > $medida){
        $errores[]="La imagen es muy pesada";
      }

      //revisar que el arreglo de errores este vacio
      if(empty($errores)){

        //Subida de archivos

        //crear carpetas
        $carpetaImagenes = '../../imagenes/';
        if(!is_dir($carpetaImagenes)){
          mkdir($carpetaImagenes);
        }

        $nombreImagen='';

        if($imagen['name']){
          //eliminar la imagen previa     
          unlink($carpetaImagenes.$propiedad['imagen']);

          //Generar un nombre unico 
          $nombreImagen=md5(uniqid(rand(),true)).".jpg";


          //subir la imagen
          move_uploaded_file($imagen['tmp_name'],$carpetaImagenes.$nombreImagen);
        }else{
          $nombreImagen=$propiedad['imagen'];
        }

        
        

        //insertar en la base de datos
      $query = 
      "UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = '${wc}', estacionamiento = ${estacionamiento}, vendedores_Id = ${vendedores_id} WHERE id = ${id}";

      // echo $query;
      // exit;

    $resultado = mysqli_query($db,$query);

      if($resultado){
        //redireccionando usuario
        header('Location: /admin?resultado=2');
      }


      }

      
    }

  
    incluirTemplate('header');

?>

    <main class="contenedor seccion">
      <h1>Actualizar Datos</h1>

      <a href="/admin" class="boton boton-verde">Volver</a>

      <?php foreach($errores as $error):?>
      <div class="alerta error">
        <?php echo $error;?>
      </div>
      <?php endforeach; ?>
      <form class="formulario" method="post"  enctype="multipart/form-data">
        <fieldset>
        <legend>Informacion General</legend>
        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo;?>">
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio;?>">

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
        
        <img class="imagen-actualizar" src="../../imagenes/<?php echo $imagenPropiedad;?>">

        <label for="descripcion">Descripcion:</label>
        <textarea id="descripcion" name="descripcion" ><?php echo $descripcion;?></textarea>
        </fieldset>
        <fieldset>
          <legend>Informacion Propiedad</legend>

          <label for="habitaciones">Habitaciones:</label>
          <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones;?>">

          <label for="wc">Baños:</label>
          <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc;?>">

          <label for="estacionamiento">Estacionamientos</label>
          <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento;?>">
        </fieldset>
        <fieldset>
          <legend>Vendedor</legend>
          
          <select name="vendedores_id" >
            <option value="">-- Seleccione --</option>
            <?php while ($row = mysqli_fetch_assoc($resultado_v) ):?>
              <option <?php echo $vendedores_id === $row['id'] ? 'selected' : ' ';?> value="<?php echo $row['id']?>"><?php echo $row['nombre']." ".$row['apellido']?></option>
            <?php endwhile;?>
            </select>
        </fieldset>
        
        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
      </form>
    </main>

    <?php
  incluirTemplate('footer');
?>
  </body>
</html>
