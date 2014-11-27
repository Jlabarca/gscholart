<?php	
	require_once("facade.php");
	$facade=new Facade();
	
	echo "<script>
				$(function () {
					$('select').uniform();
				});
		  </script>";
		
	$country=(empty($_POST['country']))?"":trim($_POST['country']);
	
	$data=Array("id"=>$country);
	$listCategory=$facade->retrieveCategoriesByCountry($data);
	
	if($listCategory!=null){
		//echo "<select id='category'>";
		echo "<option value=''>select</option>";
		foreach($listCategory as $lc){
			echo "<option value='".$lc['id']."'>".$lc['name']."</option>";
		}
		//echo "</select>";
	}else{
		//echo "<select id='category' disabled='disabled'>";
		echo "<option value=''>select</option>";
		//echo "</select>";
	}
	
?>
