<?php
require '../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth){
    header('Location: /');
}



//Importar la conexion
require '../includes/config/database.php';
$db=conectarDB();

//escribir el query
$query="Select * from propiedades";

//consultar la base de datos
$resultadoConsulta = mysqli_query($db,$query);

if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $id = $_POST["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){

        //eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id=${id}";

        $resultado = mysqli_query($db,$query);
        $propiedad = mysqli_fetch_assoc($resultado);

        unlink('../imagenes/' .$propiedad['imagen']);
 
        

        //eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id=${id}";
        $resultado = mysqli_query($db,$query);

        if($resultado){
            header("Location: /admin?resultado=3");
        }
    }
}

//muestra mensaje condicional
    $resultado=$_GET['resultado'] ??  null;
//incluye template
    
    incluirTemplate('header');
?>
    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if($resultado==1){?>
            <p class="alerta exito">Registrado correctamente</p>
        <?php } elseif($resultado==2){?>
            <p class="alerta exito">Actualizado correctamente</p>
        <?php } elseif($resultado==3){?>
            <p class="alerta exito">Propiedad eliminada correctamente</p>
        <?php }?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody><!-- mostrar los resultados -->
            <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)):?>
                <tr>
                    <td><?php echo $propiedad['id'];?></td>
                    <td><?php echo $propiedad['titulo'];?></td>
                    <td><img src="/imagenes/<?php echo $propiedad['imagen'];?>" class="imagen-tabla"></td>
                    <td><?php echo $propiedad['precio'];?></td>
                    <td>
                        <form method="post" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                            <input type="submit"  class="boton-rojo-block" value="Eliminar">
                        </form>
                        
                        <a href="./propiedades/actualizar.php?id=<?php echo $propiedad['id'];?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile;?>
            </tbody>
        </table>
    
    
    </main>

<?php
    mysqli_close($db);
    incluirTemplate('footer');
?>