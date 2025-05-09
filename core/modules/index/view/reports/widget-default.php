
<h1>Reportes</h1>
<br>
<div class="cont-total">
<form class="col s12" role="form">
<input type="hidden" name="view" value="reports">
        <?php
$pacients = PacientData::getAll();
$medics = MedicData::getAll();
$statuses = StatusData::getAll();
$payments = PaymentData::getAll();
        ?>



    <div class="input-field col s12 l6 m6"> 
		   
				<select name="pacient_id"  >
				<option value="">PACIENTE</option>
				  <?php foreach($pacients as $p):?>
				    <option value="<?php echo $p->id; ?>" <?php if(isset($_GET["pacient_id"]) && $_GET["pacient_id"]==$p->id){ echo "selected"; } ?>><?php echo $p->id." - ".$p->name." ".$p->lastname; ?></option>
				  <?php endforeach; ?>
				</select> 
    </div>
    <div class="input-field col s12 l6 m6">  
			<select name="medic_id" >
				<option value="">MEDICO</option>
				  <?php foreach($medics as $p):?>
				    <option value="<?php echo $p->id; ?>" <?php if(isset($_GET["medic_id"]) && $_GET["medic_id"]==$p->id){ echo "selected"; } ?>><?php echo $p->id." - ".$p->name." ".$p->lastname; ?></option>
				  <?php endforeach; ?>
			</select> 
    </div>
    <div class="input-field col s12 l6 m6"> 
		  <span >INICIO</span>
		  <input type="date" name="start_at" value="<?php if(isset($_GET["start_at"]) && $_GET["start_at"]!=""){ echo $_GET["start_at"]; } ?>" class="form-control" placeholder="Palabra clave"> 
    </div>
    <div class="input-field col s12 l6 m6"> 
		  <span >FIN</span>
		  <input type="date" name="finish_at" value="<?php if(isset($_GET["finish_at"]) && $_GET["finish_at"]!=""){ echo $_GET["finish_at"]; } ?>" class="form-control" placeholder="Palabra clave">
    </div> 
  	<div class="input-field col s12 l6 m6">  
		  <span >ESTADO</span>
			<select name="status_id" class="form-control">
			  <?php foreach($statuses as $p):?>
			    <option value="<?php echo $p->id; ?>" <?php if(isset($_GET["status_id"]) && $_GET["status_id"]==$p->id){ echo "selected"; } ?>><?php echo $p->name; ?></option>
			  <?php endforeach; ?>
			</select> 
    </div>
    <div class="input-field col s12 l6 m6">
    		<span >PAGO</span> 
		  		<select name="payment_id" class="form-control">
				  <?php foreach($payments as $p):?>
				    <option value="<?php echo $p->id; ?>" <?php if(isset($_GET["payment_id"]) && $_GET["payment_id"]==$p->id){ echo "selected"; } ?>><?php echo $p->name; ?></option>
				  <?php endforeach; ?>
				</select> 
    </div>
    <div class="input-field col s12 l6 m6">
    	<button class="btn btn-primary btn-block">Procesar</button>
    </div>
    <br>
</form>

<div class="cont-total">
		<?php
$users= array();
if((isset($_GET["status_id"]) && isset($_GET["payment_id"]) && isset($_GET["pacient_id"]) && isset($_GET["medic_id"]) && isset($_GET["start_at"]) && isset($_GET["finish_at"]) ) && ($_GET["status_id"]!="" ||$_GET["payment_id"]!="" || $_GET["pacient_id"]!="" || $_GET["medic_id"]!="" || ($_GET["start_at"]!="" && $_GET["finish_at"]!="") ) ) {
$sql = "select * from reservation where ";
if($_GET["status_id"]!=""){
	$sql .= " status_id = ".$_GET["status_id"];
}

if($_GET["payment_id"]!=""){
if($_GET["status_id"]!=""){
	$sql .= " and ";
}
	$sql .= " payment_id = ".$_GET["payment_id"];
}


if($_GET["pacient_id"]!=""){
if($_GET["status_id"]!=""||$_GET["payment_id"]!=""){
	$sql .= " and ";
}
	$sql .= " pacient_id = ".$_GET["pacient_id"];
}

if($_GET["medic_id"]!=""){
if($_GET["status_id"]!=""||$_GET["pacient_id"]!=""||$_GET["payment_id"]!=""){
	$sql .= " and ";
}

	$sql .= " medic_id = ".$_GET["medic_id"];
}



if($_GET["start_at"]!="" && $_GET["finish_at"]){
if($_GET["status_id"]!=""||$_GET["pacient_id"]!="" ||$_GET["medic_id"]!="" ||$_GET["payment_id"]!="" ){
	$sql .= " and ";
}

	$sql .= " ( date_at >= \"".$_GET["start_at"]."\" and date_at <= \"".$_GET["finish_at"]."\" ) ";
}

//echo $sql;
		$users = ReservationData::getBySQL($sql);

}else{
		$users = ReservationData::getAllPendings();

}
		if(count($users)>0){
			// si hay usuarios
			$_SESSION["report_data"] = $users;
			?>
			<div class="panel panel-default">
			<div class="panel-heading">
			<a href="./report/report-word.php" class="btn btn-default waves-effect waves-light"><i class="az-download"></i> Descargar Reporte</a>
			</div>
			<table class="responsive-table table table-bordered table-hover">
			<thead>
			<th>Asunto</th>
			<th>Paciente</th>
			<th>Medico</th>
			<th>Fecha</th>
			<th>Estado</th>
			<th>Pago</th>
			<th>Costo</th>
			</thead>
			<?php
			$total = 0;
			foreach($users as $user){
				$pacient  = $user->getPacient();
				$medic = $user->getMedic();
				?>
				<tr>
				<td><?php echo $user->title; ?></td>
				<td><?php echo $pacient->name." ".$pacient->lastname; ?></td>
				<td><?php echo $medic->name." ".$medic->lastname; ?></td>
				<td><?php echo $user->date_at." ".$user->time_at; ?></td>
				<td><?php echo $user->getStatus()->name; ?></td>
				<td><?php echo $user->getPayment()->name; ?></td>
				<td>$ <?php echo number_format($user->price,2,".",",");?></td>
				</tr>
				<?php
				$total += $user->price;

			}
			echo "</table>";
			?>
			<div class="panel-body">
			<h1>Total: $ <?php echo number_format($total,2,".",",");?></h1>
			</div>
			<?php



		}else{
			echo "<p class='alert alert-danger'>No hay pacientes</p>";
		}


		?>

</div>
</div>