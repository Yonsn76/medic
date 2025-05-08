<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si la sesión está iniciada
if(!isset($_SESSION)) {
    session_start();
}

// Verificar si hay datos para el reporte
if(!isset($_SESSION["report_data"]) || empty($_SESSION["report_data"])) {
    die("Error: No hay datos para generar el reporte");
}

// Definir la ruta base
$base_dir = dirname(dirname(__FILE__));

// Incluir los archivos necesarios
require_once $base_dir.'/core/autoload.php';
require_once $base_dir.'/core/modules/index/model/ReservationData.php';
require_once $base_dir.'/core/modules/index/model/PacientData.php';
require_once $base_dir.'/core/modules/index/model/MedicData.php';
require_once $base_dir.'/core/modules/index/model/StatusData.php';
require_once $base_dir.'/core/modules/index/model/PaymentData.php';

require_once $base_dir.'/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

try {
    Autoloader::register();

    $word = new PhpOffice\PhpWord\PhpWord();
    $alumns = $_SESSION["report_data"];

    $section1 = $word->AddSection();
    $section1->addText("REPORTE DE CITAS",array("size"=>22,"bold"=>true,"align"=>"right"));

    $styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
    $styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

    $table1 = $section1->addTable("table1");
    $table1->addRow();
    $table1->addCell(3000)->addText("Asunto");
    $table1->addCell(3000)->addText("Paciente");
    $table1->addCell(3000)->addText("Medico");
    $table1->addCell(3000)->addText("Fecha");
    $table1->addCell(3000)->addText("Estado");
    $table1->addCell(3000)->addText("Pago");
    $table1->addCell(3000)->addText("Costo");

    $total = 0;
    foreach($alumns as $al){
        $medic = $al->getMedic();
        $pacient = $al->getPacient();
        $table1->addRow();
        $table1->addCell(3000)->addText($al->title);
        $table1->addCell(3000)->addText($pacient->name." ".$pacient->lastname);
        $table1->addCell(3000)->addText($medic->name." ".$medic->lastname);
        $table1->addCell(3000)->addText($al->date_at." ".$al->time_at);
        $table1->addCell(3000)->addText($al->getStatus()->name);
        $table1->addCell(3000)->addText($al->getPayment()->name);
        $table1->addCell(3000)->addText("$ ".number_format($al->price,2,".",","));
        $total += $al->price;
    }

    $section1->addText("TOTAL: $ ".number_format($total,2,".",","),array("size"=>18));

    $word->addTableStyle('table1', $styleTable,$styleFirstRow);
    
    $section1->addText("");
    $section1->addText("");
    $section1->addText("");
    $section1->addText("Generado por Sistema de Citas Médicas v2.0");
    
    $filename = "report-".time().".docx";
    $word->save($filename,"Word2007");

    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');
    
    readfile($filename);
    unlink($filename);  // remove temp file

} catch (Exception $e) {
    die("Error al generar el reporte: " . $e->getMessage());
}
?>