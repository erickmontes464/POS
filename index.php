<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
    
    <?php
        session_start();
        include_once 'controlador/conexion.php';
        $obj=new conectar();
        define('CARRITO_CLAVE', 'carrito');

        function agregar_producto($cantidad, $codigo) {
            $obj=new conectar();
            $produc = $obj->buscarProducto($codigo)[0];
            if (!empty($codigo)) {
                if (isset($_SESSION[CARRITO_CLAVE])) {
                    foreach ($_SESSION[CARRITO_CLAVE] as &$producto) {
                        if ($producto['codigo'] == $codigo) {
                            $totalCantidad = $producto['cantidad'] + $cantidad;
                            if ($totalCantidad > $produc["stock"]) {
                                echo "<script>alert('La cantidad solicitada excede el stock disponible para este producto');</script>";
                                return;
                            }
                            $producto['cantidad'] = $totalCantidad;
                            $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
                            return;
                        }
                    }
                }
                $precio = $produc["precio"];
                if ($cantidad > $produc["stock"]) {
                    echo "<script>alert('La cantidad solicitada excede el stock disponible para este producto');</script>";
                    return;
                }
                $_SESSION[CARRITO_CLAVE][] = [
                    'codigo' => $codigo,
                    'nombre' => $produc["descripcion"],
                    'cantidad' => $cantidad,   
                    'precio' => $precio,  
                    'subtotal' => number_format($cantidad * $precio, 2)            
                ];
            }
        }     

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!empty($_POST['codigo'])){
                $producto = $obj->buscarProducto($_POST['codigo']);
                if (!empty($producto)) {
                    $stock = $producto[0][2];
                    if ($stock > 0) {
                        $cancarrito = 0;
                        if (isset($_SESSION[CARRITO_CLAVE])) {
                            foreach ($_SESSION[CARRITO_CLAVE] as $producto) {
                                if ($producto['codigo'] == $_POST['codigo']) {
                                    $cancarrito += $producto['cantidad'];
                                }
                            }
                        }
                        $totalCantidad = $cancarrito + $_POST['cantidad'];
                        if ($totalCantidad <= $stock) {
                            agregar_producto($_POST['cantidad'], $_POST['codigo']);
                        } else {
                            echo "<script>alert('La cantidad solicitada excede el stock disponible para este producto');</script>";
                        }
                    } else {
                        echo "<script>alert('El producto con el código no tiene stock');</script>";
                    }                
                } else {
                    echo "<script>alert('El producto con el código no existe');</script>";
                }
            }
        }
        if(isset($_REQUEST['qr'])){
        	$producto=$_REQUEST['qr'];
        	unset($_SESSION[CARRITO_CLAVE][$producto]);
            header("Location: index.php");
        }
        if(isset($_REQUEST['vaciar'])){
        	session_destroy();
        	header("Location: index.php");
        }
    ?>
    <header>
        <img src="icon/logo.jpg" height="30px" class="mx-4">  
        <nav>         
            <!--  ------------- Modal productos Inicio ------------------>
            <button type="button" data-bs-toggle="modal" data-bs-target="#productos">Productos</button>       
            <button type="button" data-bs-toggle="modal" data-bs-target="#ventas">Ventas</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#ajustes">Ajustes</button>
            <!--Modales-->
            <div class="modal fade" id="productos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!--body-->
                            <form action="controlador/proceso_producto.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>                          
                                <input type="hidden" name="accion" value="agregar">                                                       
                                <label>Nombre del producto</label>
                                <input type="text" class="form-control mb-3" name="desc" required>                                                                                             
                                <label>Stock</label>
                                <input type="number" class="form-control mb-3" name="stock" required>      
                                <label>Precio S/.</label>
                                <input type="number" class="form-control mb-3" name="precioaddpro" required step="0.1">                                                                            
                                <input type="file" class="form-control mb-3" name="img" required>    
                                <label>Codigo de barra</label>
                                <input type="number" class="form-control mb-3" name="cod" required>  
                                <button class="btn btn-dark" type="submit">Agregar Producto</button>
                            </form>
                        </div>
                    
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ventas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ventas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-ventas">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#Nro boleta</th>
                            <th scope="col">Fecha</th>  
                            <th scope="col">Monto total</th>   
                            <th scope="col"><img src="icon/print.png" ></th>                       
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $boletas = $obj->listarFactura();
                                foreach($boletas as $a=>$datos){ 
                                    echo '<tr>';
                                    echo '<td scope="row">' . $datos[0] . '</td>';
                                    echo '<td>' . $datos[1] . '</td>';    
                                    echo '<td>' . $datos[2] . '</td>';       
                                    echo '<td><a href="boleta.php?numFactura='.$datos[0].'" target="_blank"><img src="icon/print.png"></a></td>';                                     
                                    echo '</tr>';
                                }
                            ?>    
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                </div>
            </div>

            <div class="modal fade" id="ajustes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Stock</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!--body-->
                            <form action="controlador/proceso_producto.php" method="post"class="needs-validation" novalidate>                          
                                <input type="hidden" name="accion" value="agregarStock">                                                                                                                                                   
                                <label>Stock</label>
                                <input type="number" class="form-control mb-3" name="stock" required>                                                                                                              
                                <label>Codigo de barra</label>
                                <input type="number" class="form-control mb-3" name="cod" required>  
                                <button class="btn btn-dark" type="submit">Agregar Stock</button>
                            </form>
                        </div>
                    
                    </div>
                </div>
            </div>
            <!--  ------------- Modal productos Final ------------------>
                
            <button src="" onclick="toggleDarkMode()">Modo oscuro</button>
            <button href="" class="btn-admin">Administrador</button>
            <a href=""><img src="icon/poweroff.png" ></a>
        </nav>
    </header>
    
    <div class="container-fluid">
        <section class="box-compra">
        
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="d-flex mb-3">         
                <input type="number" class="form-control w-25" id="cantidad" name="cantidad" min="1" placeholder="Cantidad" value="1">
                <input type="number" class="form-control" id="codigo" name="codigo" placeholder="Ingresa el codigo de barra">
                <button class="btn btn-secondary" type="submit">Agregar</button>
            </div>
        </form>  
<?php

?>
        <diV class="tabla">
            <table class="table" id="tabla">
                <thead>
                    <tr>                       
                        <th scope="col">Item</th>
                        <th scope="col">Cant.</th>
                        <th scope="col">S/</th>
                        <th scope="col">ST.</th>
                        <td><img src="icon/x.png"></td>
                    </tr>
                </thead>
                <tbody>
    <?php
        $sumatotal = 0;
        if (isset($_SESSION[CARRITO_CLAVE])){
            foreach ($_SESSION[CARRITO_CLAVE] as $indice =>['cantidad' => $cantidad, 'nombre' => $nombre, 'precio' => $precio,'subtotal' => $subtotal]) {
                $sumatotal=number_format($sumatotal+$subtotal,2);
                echo '<tr>';
                echo '<td>' . $nombre . '</td>';
                echo '<td>' . $cantidad . '</td>';
                echo '<td>' . $precio . '</td>';
                echo '<td>' . $subtotal . '</td>';
                echo '<td><a href="index.php?qr='.$indice.'"><img src="icon/x.png"></a></td>';
                echo '</tr>';}}
    ?>              
                </tbody>
            </table>
        </diV>
            <div class="d-flex justify-content-between under-box">      
                <p>Total Item(s):</p>
                <p id="cont"></p>
                <p>Precio Total:</p>
                <p><?=$sumatotal?></p>
            </div>
            <div class="d-flex justify-content-between">
                <?php
                if(!empty($_SESSION[CARRITO_CLAVE])){   
                    $carrito =$_SESSION[CARRITO_CLAVE];
                    $query_string = http_build_query(array('carrito' => $carrito));
                }  ?>
                
                <a href='controlador/proceso_producto.php?vaciar=true' class="btn btn-outline-danger col-5">Cancelar</a>
                <a href='controlador/factura.php?comprar=true&sumatotal=<?=$sumatotal?>&<?=$query_string?>' class="btn btn-outline-success col-5">Pagar</a>
            <div>          

        </section>
        <!--caja de la derecha de productos de almacen-->
        <section class="box-products">
            <form method="POST" class="input-group">
                <input type="text" class="form-control" placeholder="Buscar Producto" name="search" id="search">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        
            <div class="container-product mt-3" id="product-container">
                <?php
                    if(isset($_POST['search'])){
                        $query = $_POST['search'];
                        $productos = $obj->listarProductobusqueda($query);
                    } else {
                        $productos = $obj->listarProducto();
                    }
                    foreach($productos as $a=>$datos){ 
                ?>
                    <div class="product">
                        <img src="<?=$datos[4]?>" alt="no image">
                        <p><?=$datos[1]?></p>
                        <p><?=$datos[0]?></p>
                        <p>Stock: <?=$datos[3]?></p>
                        <h5 class="text-success"><strong>S/<?=$datos[2]?></strong></h5>
                    </div>

                <?php } ?>   
            </div>
        </section>
    <script src="js/app.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/validation.js"></script>
    <script>
        function toggleDarkMode() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>
    
</body>
</html>