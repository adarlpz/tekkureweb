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

// Directorio donde se guardarán los videos
$upload_dir = "almacenamiento/private/uploads/";

// Verificar si se subió un archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $file_name = basename($_FILES["video"]["name"]); // Nombre del archivo
    $target_file = $upload_dir . $file_name; // Ruta completa

    // Verificar el tipo de archivo
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("mp4", "avi", "mov", "mkv");

    if (!in_array($file_type, $allowed_types)) {
        die("Error: Solo se permiten archivos de video (MP4, AVI, MOV, MKV).");
    }

    // Mover el archivo al servidor
    if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
        // Guardar la ruta en la base de datos
        $stmt = $conn->prepare("INSERT INTO videos (nombre, ruta) VALUES (?, ?)");
        $stmt->bind_param("ss", $file_name, $target_file);

        if ($stmt->execute()) {
            header("Location: ../frontend/warningscreens/perdon.html");
        } else {
            echo "Error al guardar en la base de datos.";
            header("Location: ../frontend/warningscreens/perdon.html");
        }

        $stmt->close();
    } else {
        echo "Error al subir el archivo.";
        header("Location: ../frontend/warningscreens/perdon.html");
    }
} else {
    echo "No se ha subido ningún archivo.";
    header("Location: ../frontend/warningscreens/perdon.html");
}

// Cerrar conexión
$conn->close();
?>