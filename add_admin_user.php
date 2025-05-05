<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario Administrador</title>
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
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Usuario Administrador</h1>
        
        <?php
        // Verificar si se ha enviado el formulario
        if (isset($_POST['submit'])) {
            // Obtener los datos del formulario
            $username = $_POST['username'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            
            // Validar los datos
            $errors = array();
            
            if (empty($username)) {
                $errors[] = "El nombre de usuario es obligatorio.";
            }
            
            if (empty($password)) {
                $errors[] = "La contraseña es obligatoria.";
            }
            
            if (empty($name)) {
                $errors[] = "El nombre es obligatorio.";
            }
            
            if (empty($lastname)) {
                $errors[] = "El apellido es obligatorio.";
            }
            
            // Si no hay errores, insertar el usuario
            if (empty($errors)) {
                try {
                    // Verificar si la base de datos existe
                    $db_path = 'db/bookmedik.sqlite';
                    
                    if (!file_exists('db')) {
                        mkdir('db', 0777, true);
                    }
                    
                    // Conectar a la base de datos
                    $pdo = new PDO('sqlite:' . $db_path);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Verificar si la tabla user existe
                    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='user'");
                    $tableExists = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$tableExists) {
                        // Crear la tabla user
                        $pdo->exec("CREATE TABLE user (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            username TEXT,
                            name TEXT,
                            lastname TEXT,
                            email TEXT,
                            password TEXT,
                            is_active INTEGER NOT NULL DEFAULT 1,
                            is_admin INTEGER NOT NULL DEFAULT 0,
                            created_at DATETIME
                        )");
                        
                        echo "<p class='success'>Tabla 'user' creada correctamente.</p>";
                    }
                    
                    // Verificar si el usuario ya existe
                    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($user) {
                        echo "<p class='error'>El nombre de usuario ya existe.</p>";
                    } else {
                        // Hashear la contraseña
                        $hashedPassword = sha1(md5($password));
                        
                        // Insertar el usuario
                        $stmt = $pdo->prepare("INSERT INTO user (username, name, lastname, email, password, is_active, is_admin, created_at) VALUES (:username, :name, :lastname, :email, :password, 1, 1, datetime('now'))");
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':lastname', $lastname);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $hashedPassword);
                        $stmt->execute();
                        
                        echo "<p class='success'>Usuario administrador creado correctamente.</p>";
                        echo "<p>Ahora puedes iniciar sesión con:</p>";
                        echo "<p><strong>Usuario:</strong> " . htmlspecialchars($username) . "</p>";
                        echo "<p><strong>Contraseña:</strong> " . htmlspecialchars($password) . "</p>";
                        echo "<a href='index.php' class='button'>Ir a la aplicación</a>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
                }
            } else {
                // Mostrar errores
                echo "<div class='error'>";
                echo "<p>Se encontraron los siguientes errores:</p>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>" . $error . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        ?>
        
        <form method="post" action="">
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'admin'; ?>">
            
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : 'admin'; ?>">
            
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'Administrador'; ?>">
            
            <label for="lastname">Apellido:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : 'Sistema'; ?>">
            
            <label for="email">Correo electrónico:</label>
            <input type="text" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'admin@example.com'; ?>">
            
            <input type="submit" name="submit" value="Agregar Usuario">
        </form>
        
        <div class="info">
            <h2>Información</h2>
            <p>Esta página te permite agregar un usuario administrador a la base de datos.</p>
            <p>El usuario administrador tendrá acceso completo a todas las funciones de la aplicación.</p>
            <p>Por defecto, se sugiere usar "admin" como nombre de usuario y contraseña, pero puedes cambiarlo si lo deseas.</p>
        </div>
        
        <a href="check_db.php" class='button' style="background-color: #007bff;">Verificar Base de Datos</a>
        <a href="index.php" class='button' style="background-color: #6c757d;">Volver a la aplicación</a>
    </div>
</body>
</html>
