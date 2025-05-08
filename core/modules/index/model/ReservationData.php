<?php
class ReservationData {
	public static $tablename = "reservation";


	public function ReservationData(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function getPacient(){ return PacientData::getById($this->pacient_id); }
	public function getMedic(){ return MedicData::getById($this->medic_id); }
	public function getStatus(){ return StatusData::getById($this->status_id); }
	public function getPayment(){ return PaymentData::getById($this->payment_id); }

	public function add(){
		$sql = "insert into reservation (title,note,medic_id,date_at,time_at,pacient_id,user_id,price,status_id,payment_id,sick,symtoms,medicaments,created_at) ";
		$sql .= "values (";
		$sql .= "\"" . $this->title . "\",";
		$sql .= "\"" . $this->note . "\",";
		$sql .= $this->medic_id . ",";
		$sql .= "\"" . $this->date_at . "\",";
		$sql .= "\"" . $this->time_at . "\",";
		$sql .= $this->pacient_id . ",";
		$sql .= $this->user_id . ",";
		$sql .= $this->price . ",";
		$sql .= $this->status_id . ",";
		$sql .= $this->payment_id . ",";
		$sql .= "\"" . $this->sick . "\",";
		$sql .= "\"" . $this->symtoms . "\",";
		$sql .= "\"" . $this->medicaments . "\",";
		$sql .= "datetime('now'))";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto ReservationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set ";
		$sql .= "title=\"" . $this->title . "\",";
		$sql .= "pacient_id=" . $this->pacient_id . ",";
		$sql .= "medic_id=" . $this->medic_id . ",";
		$sql .= "date_at=\"" . $this->date_at . "\",";
		$sql .= "time_at=\"" . $this->time_at . "\",";
		$sql .= "note=\"" . $this->note . "\",";
		$sql .= "sick=\"" . $this->sick . "\",";
		$sql .= "symtoms=\"" . $this->symtoms . "\",";
		$sql .= "medicaments=\"" . $this->medicaments . "\",";
		$sql .= "status_id=" . $this->status_id . ",";
		$sql .= "payment_id=" . $this->payment_id . ",";
		$sql .= "price=" . $this->price;
		$sql .= " where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getRepeated($pacient_id,$medic_id,$date_at,$time_at){
		$sql = "select * from ".self::$tablename." where ";
		$sql .= "pacient_id=" . $pacient_id . " and ";
		$sql .= "medic_id=" . $medic_id . " and ";
		$sql .= "date_at=\"" . $date_at . "\" and ";
		$sql .= "time_at=\"" . $time_at . "\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}



	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." where mail=\"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getEvery(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename." where date(date_at)>=date('now') order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAllPendings(){
		$sql = "select * from ".self::$tablename." where date(date_at)>=date('now') and status_id=1 and payment_id=1 order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


	public static function getAllByPacientId($id){
		$sql = "select * from ".self::$tablename." where pacient_id=$id order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAllByMedicId($id){
		$sql = "select * from ".self::$tablename." where medic_id=$id order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getBySQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getOld(){
		$sql = "select * from ".self::$tablename." where date(date_at)<date('now') order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where title like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


}

?>