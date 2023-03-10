<!DOCTYPE html>
<html>
    <head>  
    <link rel="stylesheet" href="css/styleboleta.css">
    </head>
    <body>
        <?php
            include_once 'controlador/conexion.php';
            $obj=new conectar();
            $codigo = $_REQUEST['numFactura'];;
            $productos = $obj->listarFacturaxcodigo($codigo) ;
        ?>
        
        <div class="ticket">
            <img src="icon/logo.jpg" height="30px" >  
            <p class="centrado">Nro de boleta:&nbsp;&nbsp;<?=$codigo?>
                <br><?=$productos[0][1]?></p>
            <table>
                <thead>             
                    <tr>
                        <th class="cantidad">CANT</th>
                        <th class="producto">PRODUCTO</th>
                        <th class="precio">S/.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $a=>$datos){ ?> 
                    <tr>
                        <td class="cantidad"><?=$datos[3]?></td>
                        <td class="producto"><?=$datos[2]?></td>
                        <td class="precio"><?=$datos[4]?></td>
                    </tr>
                    <?php }?>
                    <tr>          
                        <td class="cantidad"></td>
                        <td class="producto">TOTAL</td>
                        <td class="precio"><?=$datos[5]?></td>
                    </tr>
                </tbody>
            </table>
            <p class="centrado">¡GRACIAS POR SU COMPRA!</p>
         
        </div>
    </body>
</html>