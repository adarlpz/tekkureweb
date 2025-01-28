<?php
// Configuración de la base de datos
$host = "localhost";
$user = "u519586698_admi";
$password = "Admi12345#";
$database = "u519586698_reconocimiento";

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// URL del servidor y credenciales
$upload_url = "https://tekkure.com/almacenamiento/private/uploads/";
$username = "tekkure"; // Cambia este usuario
$password = "!Prueba123*"; // Cambia esta contraseña

// Verificar si se subió un archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $file_name = basename($_FILES["video"]["name"]); // Nombre del archivo
    $file_temp = $_FILES["video"]["tmp_name"]; // Ruta temporal del archivo

    // Verificar el tipo de archivo
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_types = array("mp4", "avi", "mov", "mkv");

    if (!in_array($file_type, $allowed_types)) {
        die("Error: Solo se permiten archivos de video (MP4, AVI, MOV, MKV).");
    }

    // Subir el archivo usando cURL
    $ch = curl_init();

    // Configurar cURL
    curl_setopt($ch, CURLOPT_URL, $upload_url . $file_name); // URL del archivo destino
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Usuario y contraseña
    curl_setopt($ch, CURLOPT_UPLOAD, true); // Habilitar la subida de archivos
    curl_setopt($ch, CURLOPT_INFILE, fopen($file_temp, "r")); // Archivo local
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_temp)); // Tamaño del archivo

    // Ejecutar la solicitud
    $result = curl_exec($ch);

    if ($result === false) {
        die("Error al subir el archivo: " . curl_error($ch));
    }

    curl_close($ch);

    // Guardar la ruta del archivo en la base de datos
    $stmt = $conn->prepare("INSERT INTO videos (nombre, ruta) VALUES (?, ?)");
    $stmt->bind_param("ss", $file_name, $upload_url . $file_name);

    if ($stmt->execute()) {
        header("Location: ../frontend/warningscreens/perdon.html");
    } else {
        echo "Error al guardar en la base de datos.";
    }

    $stmt->close();
} else {
    echo "No se ha subido ningún archivo.";
}

// Cerrar conexión
$conn->close();
?>
