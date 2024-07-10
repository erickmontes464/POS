<?php 
   session_start();
   include_once 'conexion.php';
   $obj = new Conectar();
   
   $producto = $obj->buscarProducto($_REQUEST['cod']);
   
   $accion = $_REQUEST['accion'];
   if ($accion === "agregar") {
       $foto=$_FILES['img']['name'];
       $ruta=$_FILES['img']['tmp_name'];
       $tipo = $_FILES['img']['type'];
   
       if ($tipo === 'image/jpeg') {
           $imagen = imagecreatefromjpeg($ruta);
       } else if ($tipo === 'image/png') {
           $imagen = imagecreatefrompng($ruta);
       }
      //Convertir la imagen a RGB
       $imagen = imagecreatefrompng($ruta);
       imagepalettetotruecolor($imagen);
      
       // crear una imagen en formato WebP
       $producto="images/".$foto.".webp";
       $productosql="../images/".$foto.".webp";
       imagewebp($imagen, $productosql, 80); // la calidad puede variar entre 0 y 100
   
       // liberar los recursos
       imagedestroy($imagen);
   
       if (!empty($producto)){
           $obj->agregarProducto($_REQUEST['cod'], $_REQUEST['desc'], $_REQUEST['stock'], $_REQUEST['precioaddpro'], $producto);   
       } else {
           echo "<script>alert('Ya existe el producto')</script>";           
       }
   } else {
       if ($accion === "agregarStock") {
           $obj->agregarProductorepeat($_REQUEST['cod'], $_REQUEST['stock']); 
       }
   }
   
   if (isset($_REQUEST['vaciar'])){
       session_destroy();
       header("location:../index.php");
   }
   
   echo "
       <script> 
           window.location.href = '../index.php';
       </script>";
   
?>
