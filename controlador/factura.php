<?php
    session_start();
    include_once 'conexion.php';
    $obj = new Conectar();
    if(isset($_REQUEST['comprar'])){
        $sumafinal =$_REQUEST['sumatotal'];
        $query_string = $_SERVER['QUERY_STRING'];
        parse_str($query_string, $params);
        $carrito = $params['carrito'];
        $obj->agregarFactura($carrito,$sumafinal);
        session_destroy();   
        $numfactura = $obj->numfactura();
        echo "
        <script> 
        var meWindow = window.open('../boleta.php?numFactura=$numfactura', '_blank');
        meWindow.print();
        
            window.location.href = '../index.php';
        </script>";  
    }
    
?>