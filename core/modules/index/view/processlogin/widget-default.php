<?php
// Archivo: processlogin/widget-default.php
// Este archivo procesa el inicio de sesión

// Verificar si ya hay una sesión iniciada
if(Session::getUID()=="") {
    // No hay sesión, procesar el inicio de sesión

    // Verificar que se enviaron los datos del formulario
    if(isset($_POST['mail']) && isset($_POST['password'])) {
        $user = $_POST['mail'];
        $pass = sha1(md5($_POST['password']));

        try {
            // Conectar a la base de datos
            $con = Database::getCon();

            // Depuración: Verificar si la tabla user existe
            try {
                $checkTable = $con->query("SELECT name FROM sqlite_master WHERE type='table' AND name='user'");
                $tableExists = $checkTable->fetch(PDO::FETCH_ASSOC);

                if(!$tableExists) {
                    die("Error: La tabla 'user' no existe en la base de datos. Por favor, inicializa la base de datos usando init_db_web.php");
                }
            } catch(PDOException $e) {
                die("Error al verificar la tabla: " . $e->getMessage());
            }

            // Mostrar información de depuración
            echo "<div style='background-color: #f8f9fa; padding: 15px; margin: 20px; border-radius: 5px;'>";
            echo "<h3>Información de depuración</h3>";
            echo "<p><strong>Usuario:</strong> " . htmlspecialchars($user) . "</p>";
            echo "<p><strong>Contraseña hasheada:</strong> " . $pass . "</p>";

            // Verificar si hay usuarios en la base de datos
            $checkUsers = $con->query("SELECT COUNT(*) as count FROM user");
            $userCount = $checkUsers->fetch(PDO::FETCH_ASSOC);
            echo "<p><strong>Número de usuarios en la base de datos:</strong> " . $userCount['count'] . "</p>";

            if ($userCount['count'] > 0) {
                // Mostrar todos los usuarios
                $allUsers = $con->query("SELECT id, username, email, password, is_active, is_admin FROM user");
                echo "<p><strong>Usuarios en la base de datos:</strong></p>";
                echo "<ul>";
                while ($row = $allUsers->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>";
                    echo "ID: " . $row['id'] . ", ";
                    echo "Usuario: " . $row['username'] . ", ";
                    echo "Email: " . $row['email'] . ", ";
                    echo "Contraseña: " . $row['password'] . ", ";
                    echo "Activo: " . ($row['is_active'] ? 'Sí' : 'No') . ", ";
                    echo "Admin: " . ($row['is_admin'] ? 'Sí' : 'No');
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='error'>No hay usuarios en la base de datos.</p>";
                echo "<p>Por favor, <a href='add_admin_user.php'>agrega un usuario administrador</a> antes de intentar iniciar sesión.</p>";
            }
            echo "</div>";

            // Preparar la consulta con parámetros para evitar inyección SQL
            $sql = "SELECT * FROM user WHERE (email = :user OR username = :user) AND password = :pass AND is_active = 1";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':pass', $pass);
            $stmt->execute();

            // Usar fetch con PDO
            $r = $stmt->fetch(PDO::FETCH_ASSOC);

            if($r) {
                // Usuario encontrado, iniciar sesión
                $_SESSION['user_id'] = $r['id'];

                // Depuración
                echo "<div style='background-color: #f8f9fa; padding: 15px; margin: 20px; border-radius: 5px;'>";
                echo "<h3>Inicio de sesión exitoso</h3>";
                echo "<p>Usuario: " . htmlspecialchars($user) . "</p>";
                echo "<p>ID de usuario: " . $r['id'] . "</p>";
                echo "<p>Sesión iniciada: " . (isset($_SESSION['user_id']) ? 'Sí' : 'No') . "</p>";
                echo "<p>Redirigiendo a la página de inicio...</p>";
                echo "</div>";

                // Redirigir después de 3 segundos
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?view=home';
                    }, 3000);
                </script>";
            } else {
                // Usuario no encontrado o contraseña incorrecta
                echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
                echo "<h3>Error de inicio de sesión</h3>";
                echo "<p>Usuario o contraseña incorrectos.</p>";
                echo "<p>Redirigiendo a la página de inicio de sesión...</p>";
                echo "</div>";

                // Redirigir después de 3 segundos
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?view=login';
                    }, 3000);
                </script>";
            }
        } catch(PDOException $e) {
            // Error en la base de datos
            echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
            echo "<h3>Error de base de datos</h3>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
    } else {
        // No se enviaron los datos del formulario
        echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
        echo "<h3>Error</h3>";
        echo "<p>No se recibieron los datos del formulario.</p>";
        echo "<p>Redirigiendo a la página de inicio de sesión...</p>";
        echo "</div>";

        // Redirigir después de 3 segundos
        echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php?view=login';
            }, 3000);
        </script>";
    }
} else {
    // Ya hay una sesión iniciada, redirigir a la página de inicio
    echo "<div style='background-color: #d4edda; padding: 15px; margin: 20px; border-radius: 5px;'>";
    echo "<h3>Ya has iniciado sesión</h3>";
    echo "<p>Redirigiendo a la página de inicio...</p>";
    echo "</div>";

    // Redirigir después de 3 segundos
    echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php?view=home';
        }, 3000);
    </script>";
}
?>