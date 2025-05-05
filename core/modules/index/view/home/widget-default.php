<?php
// Verificar si el usuario ha iniciado sesión
if(Session::getUID()==""){
    echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
    echo "<h3>Acceso denegado</h3>";
    echo "<p>Debes iniciar sesión para acceder a esta página.</p>";
    echo "<p>Redirigiendo a la página de inicio de sesión...</p>";
    echo "</div>";

    echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php?view=login';
        }, 3000);
    </script>";
    exit;
}

// Obtener datos del usuario
try {
    $user = UserData::getById(Session::getUID());

    if(!$user) {
        echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
        echo "<h3>Error</h3>";
        echo "<p>No se pudo obtener la información del usuario.</p>";
        echo "<p>ID de sesión: " . Session::getUID() . "</p>";
        echo "<p>Cerrando sesión y redirigiendo...</p>";
        echo "</div>";

        // Cerrar sesión
        session_destroy();

        echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php?view=login';
            }, 3000);
        </script>";
        exit;
    }
} catch(Exception $e) {
    echo "<div style='background-color: #f8d7da; padding: 15px; margin: 20px; border-radius: 5px;'>";
    echo "<h3>Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
    exit;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Bienvenido al Sistema de Citas Médicas</h1>
            <p>Hola, <?php echo $user->name . " " . $user->lastname; ?>!</p>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Menú Principal</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="index.php?view=reservations" class="list-group-item">
                            <i class="az-calendar3"></i> Gestionar Citas
                        </a>
                        <a href="index.php?view=pacients" class="list-group-item">
                            <i class="az-wc"></i> Gestionar Pacientes
                        </a>
                        <a href="index.php?view=medics" class="list-group-item">
                            <i class="az-user-tie"></i> Gestionar Médicos
                        </a>
                        <a href="index.php?view=categories" class="list-group-item">
                            <i class="az-books"></i> Gestionar Áreas Médicas
                        </a>
                        <a href="index.php?view=reports" class="list-group-item">
                            <i class="az-stats-dots"></i> Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

