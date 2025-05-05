<!DOCTYPE html>
<html>
<head>
    <title>Verificar Base de Datos</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verificación de Base de Datos</h1>
        
        <?php
        // Verificar si la base de datos existe
        $db_path = 'db/bookmedik.sqlite';
        
        if (!file_exists('db')) {
            echo "<p class='error'>El directorio 'db' no existe.</p>";
            echo "<a href='init_db_web.php' class='button'>Inicializar Base de Datos</a>";
        } elseif (!file_exists($db_path)) {
            echo "<p class='error'>El archivo de base de datos no existe.</p>";
            echo "<a href='init_db_web.php' class='button'>Inicializar Base de Datos</a>";
        } else {
            echo "<p class='success'>El archivo de base de datos existe.</p>";
            
            try {
                // Conectar a la base de datos
                $pdo = new PDO('sqlite:' . $db_path);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                echo "<p class='success'>Conexión a la base de datos exitosa.</p>";
                
                // Verificar las tablas
                $tables = array('user', 'pacient', 'medic', 'category', 'status', 'payment', 'reservation');
                $missingTables = array();
                
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$result) {
                        $missingTables[] = $table;
                    }
                }
                
                if (empty($missingTables)) {
                    echo "<p class='success'>Todas las tablas necesarias existen.</p>";
                    
                    // Verificar si hay usuarios
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM user");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result['count'] > 0) {
                        echo "<p class='success'>Hay " . $result['count'] . " usuario(s) en la base de datos.</p>";
                        
                        // Mostrar usuarios
                        $stmt = $pdo->query("SELECT id, username, name, lastname, email, is_admin, is_active FROM user");
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        echo "<h2>Usuarios</h2>";
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Admin</th><th>Activo</th></tr>";
                        
                        foreach ($users as $user) {
                            echo "<tr>";
                            echo "<td>" . $user['id'] . "</td>";
                            echo "<td>" . $user['username'] . "</td>";
                            echo "<td>" . $user['name'] . "</td>";
                            echo "<td>" . $user['lastname'] . "</td>";
                            echo "<td>" . $user['email'] . "</td>";
                            echo "<td>" . ($user['is_admin'] ? 'Sí' : 'No') . "</td>";
                            echo "<td>" . ($user['is_active'] ? 'Sí' : 'No') . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p class='error'>No hay usuarios en la base de datos.</p>";
                        echo "<a href='init_db_web.php' class='button'>Inicializar Base de Datos</a>";
                    }
                } else {
                    echo "<p class='error'>Faltan las siguientes tablas: " . implode(', ', $missingTables) . "</p>";
                    echo "<a href='init_db_web.php' class='button'>Inicializar Base de Datos</a>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>Error al conectar a la base de datos: " . $e->getMessage() . "</p>";
                echo "<a href='init_db_web.php' class='button'>Inicializar Base de Datos</a>";
            }
        }
        ?>
        
        <div class="info">
            <h2>Información</h2>
            <p>Esta página verifica el estado de la base de datos SQLite para la aplicación de Citas Médicas.</p>
            <p>Si hay algún problema con la base de datos, puedes inicializarla haciendo clic en el botón "Inicializar Base de Datos".</p>
            <p><strong>Nota:</strong> La inicialización de la base de datos eliminará todos los datos existentes.</p>
        </div>
        
        <a href="index.php" class='button' style="background-color: #007bff;">Volver a la aplicación</a>
    </div>
</body>
</html>
