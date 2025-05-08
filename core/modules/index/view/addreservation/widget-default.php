<?php
if(!isset($_SESSION["user_id"])) {
    Core::redir("./index.php?view=login");
    exit;
}

if(count($_POST)>0){
    // Validar que todos los campos requeridos estén presentes
    $required_fields = ["title", "pacient_id", "medic_id", "date_at", "time_at", "status_id", "payment_id", "price"];
    foreach($required_fields as $field) {
        if(!isset($_POST[$field]) || empty($_POST[$field])) {
            Core::alert("Error: Todos los campos marcados con * son obligatorios");
            Core::redir("./index.php?view=newreservation");
            exit;
        }
    }

    // Verificar si ya existe una cita para el mismo médico en la misma fecha y hora
    $rx = ReservationData::getRepeated($_POST["pacient_id"],$_POST["medic_id"],$_POST["date_at"],$_POST["time_at"]);
    if($rx != null){
        Core::alert("Error: Ya existe una cita para este médico en la fecha y hora seleccionada");
        Core::redir("./index.php?view=newreservation");
        exit;
    }

    try {
        $r = new ReservationData();
        $r->title = $_POST["title"];
        $r->note = isset($_POST["note"]) ? $_POST["note"] : "";
        $r->pacient_id = $_POST["pacient_id"];
        $r->medic_id = $_POST["medic_id"];
        $r->date_at = $_POST["date_at"];
        $r->time_at = $_POST["time_at"];
        $r->user_id = $_SESSION["user_id"];
        $r->status_id = $_POST["status_id"];
        $r->payment_id = $_POST["payment_id"];
        $r->price = $_POST["price"];
        $r->sick = isset($_POST["sick"]) ? $_POST["sick"] : "";
        $r->symtoms = isset($_POST["symtoms"]) ? $_POST["symtoms"] : "";
        $r->medicaments = isset($_POST["medicaments"]) ? $_POST["medicaments"] : "";

        $r->add();
        Core::alert("Cita agregada exitosamente!");
        Core::redir("./index.php?view=reservations");
    } catch(Exception $e) {
        Core::alert("Error al agregar la cita: " . $e->getMessage());
        Core::redir("./index.php?view=newreservation");
    }
} else {
    Core::redir("./index.php?view=newreservation");
}
?>