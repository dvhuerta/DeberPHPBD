<!-- Autor: Diego Huerta -->
<?php
//CONEXIÓN
$conex = mysqli_connect("127.0.0.1", "root", "admin123", "test1");
if (!$conex) {
    echo "<p> Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    echo "</p>";
    exit;
}
//INICIALIZACIÓN
$nombre = "";
$cantidad = "";
$precio = "";
$caducidad = "";
$codProducto = "";
$accion = "Agregar";
$eliminarProd = "Eliminar";
//CRUD
//AGREGAR
if (isset($_POST["accion"]) && ($_POST["accion"] == "Agregar")) {
    $stmt = $conex->prepare("INSERT INTO producto (nombre, cantidad, precio, caducidad) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $nombre, $cantidad, $precio, $caducidad);
    $nombre = $_POST["nombre"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $caducidad = $_POST["caducidad"];
    $stmt->execute();
    $stmt->close();
    $nombre = "";
    $cantidad = "";
    $precio = "";
    $caducidad = "";
    $codProducto = "";
} 
//MODIFICAR
else if (isset($_POST["accion"]) && ($_POST["accion"] == "Modificar")) {
    $stmt = $conex->prepare("UPDATE producto SET nombre = ?, cantidad = ?, precio = ?, caducidad = ? WHERE cod_producto = ?");
    $stmt->bind_param("ssdsi", $nombre, $cantidad, $precio, $caducidad, $codProducto);
    $nombre = $_POST["nombre"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $caducidad = $_POST["caducidad"];
    $codProducto = $_POST["codProducto"];
    $stmt->execute();
    $stmt->close();
    $nombre = "";
    $cantidad = "";
    $precio = "";
    $caducidad = "";
    $codProducto = "";
} 
//SELECCIONAR ID A MODIFICAR
else if (isset($_GET["update"])) {
    $result = $conex->query("SELECT * FROM producto WHERE cod_producto=" . $_GET["update"]);
    if ($result->num_rows > 0) {
        $row1 = $result->fetch_assoc();
        $codProducto = $row1["cod_producto"];
        $nombre = $row1["nombre"];
        $cantidad = $row1["cantidad"];
        $precio = $row1["precio"];
        $caducidad = $row1["caducidad"];
        $accion = "Modificar";
    }
} 
//ELIMINAR
else if (isset($_POST["eliminarProd"])) {
    $stmt = $conex->prepare("DELETE FROM producto WHERE cod_producto = ?");
    $stmt->bind_param("i", $codProducto);
    $codProducto = $_POST["eliminarProd"];
    $stmt->execute();
    $stmt->close();
    $codProducto = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Base de Datos con PHP">
    <meta name="author" content="Diego Huerta">
    <title>Acceso a BD con PHP</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/ayc.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3"><img src="./img/7DH7.png" style="width: 50%;" alt=""></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="card-body">
                <h1 class="text-center"> <img src="./img/DH7.png" style="width: 7%;" alt=""> Supermercados Don Diego</h1>
                <div class="card-header py-3">
                    <h2 class="m-0 font-weight-bold text-info" class="text-center">Tabla de productos</h2>
                </div>
                <div class="table-responsive">
                    <form name="forma" id="forma" method="post" action="index.php">
                        <!-- BOTÓN ELIMINAR -->
                        <table border=0>
                            <tr>
                                <td colspan="3" style="width: 1080px;">&nbsp;</td>
                                <td>
                                <button type="button" class="btn btn-danger btn-icon-split shadow" 
                                name="eliminar" onclick="eliminacionProducto()" >
                                <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                                </span><span class="text"><?php echo $eliminarProd?></span></button>
                                </td>
                            </tr>
                        </table>
                        <!-- TABLA CLIENTE -->
                        <table border="1" id="t01" class="table table-bordered shadow" id="dataTable" width="100%" cellspacing="0">
                            <!-- TÍTULOS -->
                            <tr>
                                <th class="text-center">CÓDIGO</td>
                                <th class="text-center">NOMBRE</td>
                                <th class="text-center">CANTIDAD</td>
                                <th class="text-center">PRECIO</td>
                                <th class="text-center">CADUCIDAD</td>
                                <th class="text-center">ELIMINAR</td>
                            </tr>
                            <?php
                            /* GUARDAR EN RESULT LOS DATOS DE LA TABLA */
                            $result = $conex->query("SELECT * FROM PRODUCTO");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                            <!-- IMPRESIÓN DE LA TABLA CON LOS DATOS DESDE LA BASE -->
                            <tr>
                                <td  class="text-center"><a class="btn btn-info btn-circle btn-sm" 
                                        href="index.php?update=<?php echo $row["cod_producto"]; ?>"><?php echo $row["cod_producto"]; ?></a>
                                </td>
                                <td  class="text-center"><?php echo $row["nombre"]; ?></td>
                                <td  class="text-center"><?php echo $row["cantidad"]; ?></td>
                                <td  class="text-center"><?php echo $row["precio"]; ?></td>
                                <td  class="text-center"><?php echo $row["caducidad"]; ?></td>
                                <td  class="text-center"><input type="radio" name="eliminarProd" value="<?php echo $row["cod_producto"]; ?>">
                                </td>
                            </tr>
                            <?php
                                }
                            } 
                            /* EN CASO DE NO EXISTIR DATOS EN LA TABLA */
                            else { ?>
                            <tr>
                                <td colspan="5">NO HAY DATOS</td>;
                            </tr>
                            <?php } ?>
                        </table>
                        <!-- hidden ES PARA QUE LOS USUARIOS NO PUEDAN VER NI MODIFICAR DATOS CUANDO SE ENVÍA EN UN FORMULARIO, ESPECIALMENTE ID -->
                        <input type="hidden" name="codProducto" value="<?php echo $codProducto ?>">
                        <!-- CAMPOS PARA NUEVO PRODUCTO -->
                        <div class="col-xl-5 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-50 py-2">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800 ">
                                <table>
                                    <tr>
                                        <td colspan=2><strong>PRODUCTO</strong></td>
                                    </tr>
                                    <tr>
                                        <td><label id="lblNombre" for="nombre">Nombre: </label></td>
                                        <td><input type="text"  class="form-control form-control-user"
                                        name="nombre" id="nombre" value="<?php echo $nombre ?>"
                                                maxlength="100" size="25" required></td>
                                    </tr>
                                    <tr>
                                        <td><label id="lblCantidad" for="cantidad">Cantidad: </label></td>
                                        <td><input type="number"  class="form-control form-control-user"
                                         name="cantidad" id="cantidad" value="<?php echo $cantidad ?>"
                                                min="1" max="200" required></td>
                                    </tr>
                                    <tr>
                                        <td><label id="lblPrecio" for="precio">Precio: </label>
                                        </td>
                                        <td><input type="number"  class="form-control form-control-user"
                                         name="precio" id="precio" step="0.01" min="0.1" max="100"
                                                value="<?php echo $precio ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><label id="lblCaducidad" for="caducidad">Caducidad: </label></td>
                                        <td><input type="date"  class="form-control form-control-user"
                                         name="caducidad" id="caducidad" value="<?php echo $caducidad ?>"
                                                min="2020-07-10" required></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2><input type="submit" class="btn btn-success btn-block" name="accion" value="<?php echo $accion ?>"></td>
                                    </tr>
                                </table>
                                </div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-archive fa-4x text-gray-300"></i>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
<!-- CODIGO JAVA SCRIPT PARA HACER UN TYPE BUTTON EN SUBMIT -->
<script>
function eliminacionProducto() {
    document.getElementById("forma").submit();
}
</script>

</html>
<!-- Autor: Diego Huerta -->