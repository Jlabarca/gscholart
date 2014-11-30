<?php
	
	
	class LoadData{
		
		private $errors;
		
		public function __construct(){
			$this->errors=Array();
		}
		
		
		public function validateFile($file,$filename){
			$name="";
			if($file['error']==1){
				$this->errors[]="File exceeds max upload file size.";
			}else{
				if($file['size']==0){
					$this->errors[]="File is empty";
				}else{
					$allowedExt=Array("csv");
					$temp=explode(".",$file['name']);
					$extension=end($temp);
					
					if($file['type']!='application/vnd.ms-excel' AND !in_array($extension,$allowedExt)){
						$this->errors[]="Select a CSV excel file.";			
					}else{
						$name=$file['tmp_name'];
					}
				}
			}
			return $name;
		}
				
		function retrieveCSVArrayByYear($file){
			$array=Array();
			$currentYear=date("Y")-1;
			$file=fopen($file,"r");
			fgetcsv($file);
			if ($file!== FALSE) {
				while (($data = fgetcsv($file)) !== FALSE) {
					if((!empty($data[3]) AND $data[3]!='-') AND $data[5]==$currentYear){
						$array[]=$data;
						
					}
				}
				fclose($file);
			}
			//var_dump($array[12680]);
			return $array;
			
		}
		
		function retrieveCSVArray($file){
			$array=Array();
			$file=fopen($file,"r");
			fgetcsv($file);
			if ($file!== FALSE) {
				while (($data = fgetcsv($file)) !== FALSE) {
					$array[]=$data;
				}
				fclose($file);
			}
			return $array;
		}
		
		public function getErrors(){
			return $this->errors;
		}
	
	}


?>