<?php
$resultado = "";

function contieneNumeros($cadena) {
    return preg_match('/\d/', $cadena);
}

function contieneLetras($cadena) {
    return preg_match('/[a-zA-Z]/', $cadena);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $usuario = "root";
    $contraseña = "";
    $basedatos = "hola_mundo";

    $conexion = new mysqli($host, $usuario, $contraseña, $basedatos);

    if ($conexion->connect_error) {
        $resultado = "<p style='color:red;'>Conexión fallida: " . $conexion->connect_error . "</p>";
    } else {
        if (isset($_POST['test_conexion'])) {
            $resultado = "<p style='color:green;'>Conexión exitosa a la base de datos '$basedatos'</p>";
        } elseif (isset($_POST['enviar_datos'])) {
            $nombre = $conexion->real_escape_string($_POST['nombre']);
            $correo = $conexion->real_escape_string($_POST['correo']);
            $telefono = $conexion->real_escape_string($_POST['telefono']);

            if (contieneNumeros($nombre)) {
                $resultado = "<p style='color:red;'>El nombre no debe contener números.</p>";
            } elseif (contieneLetras($telefono)) {
                $resultado = "<p style='color:red;'>El teléfono no debe contener letras.</p>";
            } else {
                $verificar = "SELECT * FROM usuarios WHERE correo = '$correo' OR telefono = '$telefono'";
                $existe = $conexion->query($verificar);

                if ($existe->num_rows > 0) {
                    $resultado = "<p style='color:red;'>Ya existe un registro con ese correo o teléfono.</p>";
                } else {
                    $sql = "INSERT INTO usuarios (nombre, correo, telefono) VALUES ('$nombre', '$correo', '$telefono')";
                    if ($conexion->query($sql) === TRUE) {
                        $resultado = "<p style='color:green;'>Datos guardados exitosamente</p>";
                    } else {
                        $resultado = "<p style='color:red;'>Error al guardar datos: " . $conexion->error . "</p>";
                    }
                }
            }
        } elseif (isset($_POST['ver_registros'])) {
            $sql = "SELECT * FROM usuarios";
            $consulta = $conexion->query($sql);

            if ($consulta->num_rows > 0) {
                $resultado = "<h3>Registros guardados:</h3><table class='tabla-usuarios'>
                              <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Fecha</th></tr>";
                while ($fila = $consulta->fetch_assoc()) {
                    $resultado .= "<tr><td>" . $fila["id"] . "</td><td>" . $fila["nombre"] . "</td><td>" .
                                  $fila["correo"] . "</td><td>" . $fila["telefono"] . "</td><td>" .
                                  $fila["fecha_registro"] . "</td></tr>";
                }
                $resultado .= "</table>";
            } else {
                $resultado = "<p>No hay registros disponibles.</p>";
            }
        }

        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form method="POST" action="" id="registroForm">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Correo:</label>
            <input type="email" name="correo" required>
        </div>
        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" required>
        </div>
        <input type="submit" name="enviar_datos" value="Enviar Datos">
        <input type="reset" value="Restablecer">
        <button type="submit" name="test_conexion">Testear Conexión</button>
        <button type="submit" name="ver_registros" id="verRegistrosBtn">Ver Registros</button>
    </form>

    <div class="resultado">
        <?php echo $resultado; ?>
    </div>

    <script>
        document.getElementById("verRegistrosBtn").addEventListener("click", function() {
            const form = document.getElementById("registroForm");
            const inputs = form.querySelectorAll("input[required]");
            inputs.forEach(input => input.removeAttribute("required"));
        });
    </script>
</body>
</html>