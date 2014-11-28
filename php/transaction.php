<?php
	require_once("connection.php");	
	
	class Transaction{
		
		private $connection;
		private $error;
		private $message;
		
		
		public function __construct(){
			$this->connection=new Connection();
			$this->error=false;
			$this->message="";
		}
		
		public function insert($table,$values){
			try{
				foreach($values as $field=>$value){
					$fields[]=":".$field;
				}
				$fields=implode(",",$fields);
				$names=implode(",",array_keys($values));
				$sql="INSERT INTO $table($names) VALUES ($fields)";
				$insert=$this->connection->prepare($sql);
				foreach($values as $field=>$value){
					$insert->bindValue(":".$field,$value);
				}
				$insert->execute();
			
			}catch(PDOException $e){
				$this->error=true;
				$this->message="".$this->message."Error: ".$e->getCode()."".$e->getMessage()."\n";
			}
		}
		
				
		public function query($sql,$values=Array()){
			$array=Array();
			try{
				$sql_trans=$this->connection->prepare($sql);
				foreach($values as $field=>$value){
					$sql_trans->bindValue(":".$field,$value);
				}

				$sql_trans->execute();
				
				if($sql_trans->rowCount()>0){
					while($result=$sql_trans->fetch(PDO::FETCH_ASSOC)){
						array_push($array,$result);
					}
				}
				return $array;
			}catch(PDOException $e){
				$this->error=true;
				$this->message=$this->message."Error: ".$e->getMessage()."\n";
			}	
		}	
		
		public function queryTwo($sql,$values=array()){
			try{
				$sql_trans=$this->connection->prepare($sql);
				foreach($values as $field=>$value){
					$sql_trans->bindValue(":".$field,$value);
				}
				$sql_trans->execute();

			}catch(PDOException $e){
				$this->error=true;
				$this->message=$this->message."Error: ".$e->getMessage()."\n";
			}	
		}
		
		public function consolidate(){
			if($this->error==true){
				$this->connection->rollBack();
				return false;
			}
			$this->connection->commit();
			return true;
			
		}
		
		
		public function exists($sql,$values=Array()){			
			$quantity=0;
			try{
				$sql_trans=$this->connection->prepare($sql);
				foreach($values as $field=>$value){
					$sql_trans->bindValue(":".$field,$value);
				}
				$sql_trans->execute();

				if($sql_trans->rowCount()>0){
					$quantity=$sql_trans->fetchColumn(0);
				}
				return $quantity;
			}catch(PDOException $e){
				$this->error=true;
				$this->message=$this->message."Error: ".$e->getCode()."".$e->getMessage()."\n";
				
			}	
		}
		
		public function getMessage(){
			if($this->error==true){
				$message="".$this->message;
			}else{
				$message="Operation Complete";
			}
			return $message;
		}
		
		public function beginTransaction(){
			return $this->connection->beginTransaction();
		}

	}
	
	
	
?>