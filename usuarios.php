<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "database_usuarios";

$conn = new mysqli($host, $user, $pass, $database);

// Mensaje de retroalimentación
$message = '';

// Insertar ejecutivo
if (isset($_POST['insert_ejecutivo'])) {
    $nom_eje = $conn->real_escape_string($_POST['nom_eje']);
    $tel_eje = $conn->real_escape_string($_POST['tel_eje']);
    
    // 1. Insertar ejecutivo
    $sql = "INSERT INTO ejecutivo (nom_eje, tel_eje) VALUES ('$nom_eje', '$tel_eje')";
    if ($conn->query($sql)) {
        $id_eje = $conn->insert_id; // Obtenemos el ID del ejecutivo recién creado
        
        // 2. Crear cita automáticamente
        $nom_cit = "Cita inicial para $nom_eje";
        $sql_cita = "INSERT INTO cita (nom_cit, id_eje2) VALUES ('$nom_cit', $id_eje)";
        
        if ($conn->query($sql_cita)) {
            $message = "✅ Ejecutivo agregado y cita creada automáticamente.";
        } else {
            $message = "⚠️ Ejecutivo agregado, pero falló la creación de la cita: " . $conn->error;
        }
    } else {
        $message = "❌ Error al agregar ejecutivo: " . $conn->error;
    }
}

// Eliminar cita
if (isset($_POST['delete_cita'])) {
    $id_cit = (int)$_POST['id_cit'];
    
    $sql = "DELETE FROM cita WHERE id_cit = $id_cit";
    if ($conn->query($sql)) {
        $message = "🗑️ Cita eliminada correctamente.";
    } else {
        $message = "❌ Error al eliminar cita: " . $conn->error;
    }
}

// Obtener citas para mostrar
$query = "SELECT c.id_cit, c.nom_cit, e.nom_eje, e.tel_eje FROM cita c JOIN ejecutivo e ON c.id_eje2 = e.id_eje";
$resultado = $conn->query($query);

if (!$resultado) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas de ejecutivos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <h3 class="mb-4">➕ Agregar Ejecutivo </h3>
    <form method="POST" class="form-inline mb-4">
        <input type="text" name="nom_eje" class="form-control mr-2" placeholder="Nombre" required>
        <input type="text" name="tel_eje" class="form-control mr-2" placeholder="Teléfono" required>
        <button type="submit" name="insert_ejecutivo" class="btn btn-success">Guardar</button>
    </form>

    <h3 class="mb-3">❌ Eliminar Cita</h3>
    <form method="POST" class="form-inline mb-4">
        <input type="number" name="id_cit" class="form-control mr-2" placeholder="ID de cita" required>
        <button type="submit" name="delete_cita" class="btn btn-danger">Eliminar</button>
    </form>

    <h3 class="mb-3">📋 Citas Registradas</h3>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID Cita</th>
                <th>Nombre Cita</th>
                <th>Ejecutivo</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id_cit'] ?></td>
                    <td><?= $row['nom_cit'] ?></td>
                    <td><?= $row['nom_eje'] ?></td>
                    <td><?= $row['tel_eje'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>