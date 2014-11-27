<?php
	
	require_once("facade.php");
	$facade=new Facade();
	
	echo "<script>
				$(function () {
					$('select').uniform();
				});
		  </script>";
	
	$country=(empty($_POST['country']))?"":trim($_POST['country']);
	
	$category=(empty($_POST['category']))?"":trim($_POST['category']);
	
	$data=Array("id_country"=>$country,
				"id_category"=>$category);
	sleep(1);
	$listJournal=$facade->retrieveJournalByCountryAndCategory($data);
	
	if($listJournal!=null){
		echo "<select id='journal'>";
		foreach($listJournal as $lj){
			echo "<option>".$lj['title']."</option>";
		}
		echo "</select>";
	}else{
		echo "<select id='journal' disabled='disabled'>";
		echo "<option>select</option>";
		echo "</select>";
	}
	
?>