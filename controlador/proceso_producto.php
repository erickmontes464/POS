<?php 
    session_start();
    include_once 'conexion.php';
    $obj = new Conectar();

    $producto = $obj-> buscarProducto($_REQUEST['cod']);

    $accion = $_REQUEST['accion'];
    if ($accion === "agregar") {
        $foto=$_FILES['img']['name'];
        $ruta=$_FILES['img']['tmp_name'];
        $producto="images/".$foto;
        $productosql="../images/".$foto;
        copy($ruta, $productosql);

        if (empty($producto)){
                $obj->agregarProducto($_REQUEST['cod'], $_REQUEST['desc'], $_REQUEST['stock'], $_REQUEST['precioaddpro'],$producto);   
        }else{
            echo "<script>alert('Ya existe el producto')</script>";           
        }
    }else{
        if ($accion === "agregarStock") {
            $obj->agregarProductorepeat($_REQUEST['cod'], $_REQUEST['stock']); 
        }
    }
    if(isset($_REQUEST['vaciar'])){
        session_destroy();
        header("location:../index.php");
    }
    echo "
        <script> 
            window.location.href = '../index.php';
        </script>";  
        
?>
