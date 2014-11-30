<?php
	require_once("facade.php");
	$facade=new Facade();
	$sjr=$facade->retrieveJournalData(Array("title"=>trim($_POST['name'])));
	if($sjr!=null){
		foreach($sjr as $s){
			echo $s['sjr'].",".$s['total_docs'].",".$s['h_index'];
		}
	}

?>