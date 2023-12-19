<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <title>Tu Título</title>
    <style>
        /* Agregar CSS para hacer la barra de navegación fija en la parte superior */
        body {
            padding-top: 70px; /* Ajusta el espacio para la barra de navegación */
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000; /* Asegura que la barra de navegación esté en la parte superior */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('consultas.php')" href="consultas.php">Consultas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('recetas.php')" href="recetas.php">Recetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('medicamentos.php')" href="medicamentos.php">Medicamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('especialidades.php')" href="especialidades.php">Especialidades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('medico.php')" href="medico.php">Medico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('pacientes.php')" href="pacientes.php">Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('roles.php')" href="roles.php">Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="contenido" onclick="getContenido('usuarios.php')" href="usuarios.php">Usuarios</a>
                </li>
            </ul>
        </div>
    </nav>

    <section style="margin-top: 7px;"> <!-- Añadir margen superior para compensar la barra de navegación fija -->
        <iframe name="contenido" id="contenido" src="<?php echo $archivo; ?>" frameborder="0" width="100%" height="7000px"></iframe>
    </section>

    <script>
        function getContenido(cuadro) {
            document.getElementById("contenido").src = cuadro;
        }
    </script>
    <!-- Agregar aquí el script de Bootstrap y cualquier otro script necesario -->
</body>
</html>
