<?php
     class Conectar{
        private $conn;
          
        function __construct() {
            $this->conectar();
        }
          
        function conectar() {
            if($this->conn==null){
                $this->conn= mysqli_connect ("localhost","root", "","bdpventa");
            }
            return $this->conn;
        }
        //Agregar productos 
        function agregarProducto($codpro,$descpro,$stockpro,$preciopro,$imgpro){
            $sql="insert into productos values('$codpro','$descpro','$preciopro','$stockpro','$imgpro')";   
            mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
        }
        function agregarProductorepeat($codpro,$stockpro) {
            $sql="update productos SET stock=stock+$stockpro  WHERE codigoQR = '$codpro'";
                mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
        }

        //método para listar registros
        function listarProducto(){
            $sql="select codigoQR, descripcion, precio, stock, image from productos";
            $res= mysqli_query($this->conn, $sql);
            $vec=array();
            while($f= mysqli_fetch_array($res))  
                    $vec[]=$f;
            return $vec;
        }
        //método para listar registros pero por medio del box iqz almacen
        function listarProductobusqueda($query){
            $sql="SELECT * FROM productos WHERE descripcion LIKE '%$query%' OR codigoQR LIKE '%$query%'";
            $res= mysqli_query($this->conn, $sql);
            $vec=array();
            while($f= mysqli_fetch_array($res))  
                    $vec[]=$f;
            return $vec;
        }
        function listarFactura() {
            $sql="select * from faccab ORDER BY fecha_de_factura DESC;";
            $res= mysqli_query($this->conn, $sql);
            $vec=array();
            while($f= mysqli_fetch_array($res))  
                    $vec[]=$f;
            return $vec;
        }
        function listarFacturaxcodigo($codigo) {
            $sql="select fc.numero_factura, fc.fecha_de_factura, fd.descripcion, fd.cantidad,fd.precio, fc.monto_total from faccab as fc 
            inner join facdet as fd on fc.numero_factura = fd.numero_factura WHERE fc.numero_factura='$codigo';";
            $res= mysqli_query($this->conn, $sql);
            $vec=array();
            while($f= mysqli_fetch_array($res))  
                    $vec[]=$f;
            return $vec;
        }
        function buscarProducto($codigo){
            $sql="SELECT descripcion, precio,stock FROM productos WHERE codigoQR ='$codigo'";
            $res= mysqli_query($this->conn, $sql);
            $vec=array();
            while($f= mysqli_fetch_array($res))  
                    $vec[]=$f;
            return $vec;
        }
        function numfactura(){
            $sql = "select * FROM faccab";
            $result = mysqli_query($this->conn, $sql);
            $num_rows = mysqli_num_rows($result);         
            return $num_rows;
        }
        function agregarFactura($carrito,$sumatotal){
            $num_factura = ($this->numfactura())+1;
            date_default_timezone_set('America/Lima');
            $fechaHora = date('Y-m-d H:i:s');
            $sql="insert into faccab values ('$num_factura','$fechaHora','$sumatotal')";
            
            foreach ($carrito as $indice =>['cantidad' => $cantidad, 'nombre' => $nombre, 'codigo' => $codigo,'subtotal' => $subtotal]) {    
                $sql2="insert into facdet values('$num_factura','$codigo','$nombre','$cantidad','$subtotal')";
                mysqli_query($this->conn, $sql2) or die(mysqli_error($this->conn));

                $sql3="update productos SET stock=stock-$cantidad  WHERE codigoQR = '$codigo'";
                mysqli_query($this->conn, $sql3) or die(mysqli_error($this->conn));

            }
            mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
           
            
        }
        
    }

?>
