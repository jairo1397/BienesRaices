<?php
    require 'includes/funciones.php';
     //Importar la conexion
     require 'includes/config/database.php';
     $db=conectarDB();
    
    incluirTemplate('header');
    $id=$_GET["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    //escribir el query
    $query="SELECT * FROM propiedades WHERE id=${id}";
    //consultar la base de datos
    $resultado = mysqli_query($db,$query);
    $propiedad = mysqli_fetch_assoc($resultado);
 
?>

    <main class="contenedor seccion contenido-centrado">
      <h1>Casa en Venta frente al bosque</h1>

      <picture>
        
        <img
          loading="lazy"
          src="/imagenes/<?php echo $propiedad['imagen'];?>"
          alt="imagen de la propiedad"
        />
      </picture>

      <div class="resumen-propiedad">
        <p class="precio"><?php echo $propiedad['precio'];?></p>
        <ul class="iconos-caracteristicas">
          <li>
            <img
              class="icono"
              loading="lazy"
              src="build/img/icono_wc.svg"
              alt="icono wc"
            />
            <p><?php echo $propiedad['wc'];?></p>
          </li>
          <li>
            <img
              class="icono"
              loading="lazy"
              src="build/img/icono_estacionamiento.svg"
              alt="icono estacionamiento"
            />
            <p><?php echo $propiedad['estacionamiento'];?></p>
          </li>
          <li>
            <img
              class="icono"
              loading="lazy"
              src="build/img/icono_dormitorio.svg"
              alt="icono habitaciones"
            />
            <p><?php echo $propiedad['habitaciones'];?></p>
          </li>
        </ul>

        <p>
        <?php echo $propiedad['descripcion'];?>
        </p>


      </div>
    </main>

    <?php
  incluirTemplate('footer');
?>

