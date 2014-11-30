<html>
	
	<head>
		<meta charset="UTF-8">
		<title>Read Files</title>
		
		<script src="assets/js/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script src="assets/js/jquery.uniform.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="assets/css/uniform.default.min.css" type="text/css" media="screen"/>
		
		<link rel="stylesheet" href="assets/css/background.css" type="text/css"/>
		<link rel="stylesheet" href="assets/css/readFiles.css" type="text/css"/>
		<link rel="stylesheet" href="assets/css/wait.css" type="text/css"/>
		<link rel="stylesheet" href="assets/css/button.css" type="text/css"/>
	
		<script>
			$(function () {
				$("input[type=file]").uniform();
			});
		</script>	
	</head>

	
	<body>
		
		<form id="main" method="POST" action="php/read.php" enctype="multipart/form-data">
			
			<h4>Select Files</h4>
			<p>	
				<label for="category">Select CWTS Category File</label><br/>
				<input type="file" name="category"/>
			</p>
			<p>	
				<label for="scimago">Select Scimago Journal Rank File</label><br/>
				<input type="file" name="scimago"/>
			</p>
			<p>	
				<label for="category">Select CWTS Journal Indicators File</label><br/>
				<input type="file" name="cwts"/>
			</p>
			
			<p>
				<input type="submit" class="button" value="SUBMIT"/>
			</p>
		</form>
		<div id="wait" style="display:none;">
			Please Wait...<br/>
			<img src="assets/images/ajax-loader.gif"/>
		</div>
	</body>
	<!--<script>
		$(document).ready(function(){
			$("#main").submit(function(e){
				e.preventDefault();  
				var formData = new FormData($(this)[0]);
				$.ajax({
					url: 'php/loadData.php',
					type: 'POST',
					data: formData,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						/*if(data.error){
							alert(data.msg);
						}else{
							alert(data.msg);
						}*/
						alert(data);
					}
				});
				return false;
			});
		});
	</script>-->
	
</html>