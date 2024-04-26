<?php
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto por el servidor de tu base de datos
$username = "root"; // Cambia esto por tu nombre de usuario de la base de datos
$password = ""; // Cambia esto por tu contraseña de la base de datos
$dbname = "almacen1"; // Cambia esto por el nombre de tu base de datos

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}

// Recibe los datos del formulario
$Nombre_del_producto = $_POST['Nombre_del_producto'];
$Precio_Unitario = $_POST['Precio_Unitario'];
$Precio_Pack = $_POST['Precio_Pack'];
$Tipo = $_POST['Tipo'];
$Fecha_compra = $_POST['Fecha_compra'];
$Olor = $_POST['Olor'];
$Color = $_POST['Color'];
$Descripcion = $_POST['Descripcion'];
$id  = $_POST['id']; // ID del producto
$Fotos = $_FILES['Fotos']['name']; // Nombre del archivo de la foto

// Variable para verificar si el producto se guarda correctamente
$product_saved = true;

// Validación de datos
if (empty($Nombre_del_producto) || empty($Precio_Unitario) || empty($Precio_Pack) || empty($Tipo) || empty($Fecha_compra) || empty($Olor) || empty($Color) || empty($Descripcion) || empty($id) || empty($Fotos)) {
    // Si algún valor está vacío, redirige a error.html
    header("Location: error.html");
    exit; // Termina el script después de la redirección
}

// Verifica si se ha subido una foto
if (isset($_FILES["Fotos"]) && $_FILES["Fotos"]["error"] === UPLOAD_ERR_OK) {
    // Ruta donde se guardará la foto
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["Fotos"]["name"]);
    // Verifica si el directorio "uploads" existe, si no, lo crea
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Crea el directorio con permisos de lectura, escritura y ejecución
    }

    // Mueve el archivo de la foto al directorio de destino
    if (move_uploaded_file($_FILES["Fotos"]["tmp_name"], $target_file)) {
        echo "La foto ". basename( $_FILES["Fotos"]["name"]). " ha sido subida correctamente.";
    } else {
        echo "Error al subir la foto.";
        $product_saved = false; // Indica que hubo un error al guardar la foto
    }
} else {
    echo "No se ha subido ninguna foto.";
    $product_saved = false; // Indica que no se subió ninguna foto
}

// Prepara la consulta SQL para insertar los datos en la base de datos
$sql = "INSERT INTO productos1 (Nombre_del_producto, Precio_Unitario, Precio_Pack, Tipo, Fecha_compra, Olor, Color, Descripcion, Fotos, id)
        VALUES ('$Nombre_del_producto', '$Precio_Unitario', '$Precio_Pack', '$Tipo', '$Fecha_compra', '$Olor', '$Color', '$Descripcion', '$Fotos', '$id')";

// Ejecuta la consulta SQL y verifica si fue exitosa
if ($conn->query($sql) === TRUE && $product_saved) {
    // Redirige al usuario a una URL de éxito si el producto se guarda correctamente
    header("Location: https://lilpruben.github.io/Hosting/Guardarproductos/exito.html");
    exit; // Termina el script después de la redirección
} else {
    // Si hay un error al guardar el producto, redirige a una URL de error
    header("Location: https://lilpruben.github.io/Hosting/Guardarproductos/error.html");
    exit; // Termina el script después de la redirección
}


// Cierra la conexión a la base de datos
$conn->close();
?>
