<!DOCTYPE html>
<html>
<head>
    <title>Inicializar Base de Datos SQLite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inicialización de Base de Datos SQLite</h1>

        <?php
        // Verificar si se ha enviado el formulario
        if (isset($_POST['initialize'])) {
            // Asegurarse de que el directorio db existe
            if (!file_exists('db')) {
                mkdir('db', 0777, true);
                echo "<p>Directorio 'db' creado.</p>";
            }

            $db_path = 'db/bookmedik.sqlite';

            // Si el archivo de base de datos ya existe, lo eliminamos para recrearlo
            if (file_exists($db_path)) {
                unlink($db_path);
                echo "<p>Base de datos anterior eliminada.</p>";
            }

            try {
                // Crear una nueva conexión a la base de datos
                $pdo = new PDO('sqlite:' . $db_path);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Habilitar claves foráneas
                $pdo->exec('PRAGMA foreign_keys = ON;');

                // Leer y ejecutar el script SQL
                $sql = file_get_contents('schema.sqlite.sql');
                $pdo->exec($sql);

                echo "<p class='success'>Base de datos SQLite inicializada correctamente.</p>";

                // Verificar si se creó el usuario admin
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM user WHERE username = 'admin'");
                $adminExists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($adminExists['count'] > 0) {
                    echo "<div class='info'>";
                    echo "<p>Se ha creado un usuario administrador por defecto:</p>";
                    echo "<p><strong>Usuario:</strong> admin</p>";
                    echo "<p><strong>Contraseña:</strong> admin</p>";
                    echo "</div>";
                    echo "<a href='index.php' class='button'>Ir a la aplicación</a>";
                } else {
                    echo "<div class='info'>";
                    echo "<p class='error'>No se pudo crear el usuario administrador por defecto.</p>";
                    echo "<p>Por favor, crea un usuario administrador manualmente:</p>";
                    echo "</div>";
                    echo "<a href='add_admin_user.php' class='button'>Agregar Usuario Administrador</a>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>Error al inicializar la base de datos: " . $e->getMessage() . "</p>";
            }
        } else {
            // Mostrar formulario
            ?>
            <p>Este script inicializará la base de datos SQLite para la aplicación de Citas Médicas.</p>
            <p><strong>Advertencia:</strong> Si ya existe una base de datos, será eliminada y reemplazada.</p>

            <form method="post" action="">
                <input type="submit" name="initialize" value="Inicializar Base de Datos" class="button" onclick="return confirm('¿Estás seguro de que deseas inicializar la base de datos? Todos los datos existentes se perderán.');">
            </form>
            <?php
        }
        ?>
    </div>
</body>
</html>
