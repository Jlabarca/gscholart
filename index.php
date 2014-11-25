<?php
	require_once("php/facade.php");
	$facade=new Facade();
	$countryList=$facade->retrieveCountries();
	$categoryList=$facade->retrieveCategories();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Google Scholar Search</title>
		
		<script src="assets/js/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script src="assets/js/jquery.uniform.min.js" type="text/javascript"></script>		
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/highcharts-more.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>
		<!-- Additional files for the Highslide popup effect -->
		<script type="text/javascript" src="http://www.highcharts.com/media/com_demo/highslide-full.min.js"></script>
		<script type="text/javascript" src="http://www.highcharts.com/media/com_demo/highslide.config.js" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="http://www.highcharts.com/media/com_demo/highslide.css" />
		<link rel="stylesheet" href="assets/css/uniform.default.min.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
		
		<script>
			$(function () {
				$("select").uniform();
			});
		</script>
	<body>
		<div id="main">
			<div id="filters">
				<label for="country">Country</label>
				<select id="country">
					<option value="">select</option>
					<?php
						if($countryList!=null){
							foreach($countryList as $cl){
								echo "<option value='".$cl['id']."'>".$cl['name']."</option>";
							}
						}
					?>
				</select>
				
				<label for="category">Category</label>
				<select id="category">
					<option value="">select</option>
					<?php
						if($categoryList!=null){
							foreach($categoryList as $cal){
								echo "<option value='".$cal['id']."'>".$cal['name']."</option>";
							}
						}
					?>
				</select>
				
				<label hidden="true" for="Journal">Journal</label>
				<span hidden="true" id="block">
					<select disabled='disabled' id="journal">
						<option value="">select</option>
					</select>
				</span>
				<input type="button" value="toggle legend" id="toggleBtn" />
				<label for="Paper">Papers</label>
				<span id="block2">
					<select id="paper">
						<option value="">select</option>
					</select>
				</span>
				<div id="grafico"></div>

				

			</div>
			<div id="espere" style="display:none;">
				Please Wait...<br/>
				<img src="assets/images/ajax-loader.gif"/>
			</div>
		</div>
			
	</body>
	<script>

		var journal=[];	
		var papers=[];
		var chart1;

		$(document).ready(function(){
			$('#country,#category').change(function(){
				country=$('#country').val();
				category=$('#category').val();
				
				if(country!="" && category!=""){
					$('#filters').fadeOut(function() {
						$('#espere').fadeIn();
						$.ajax({
							type:"POST",
							url:"php/retrieveJournals.php",
							data:"country="+country+"&category="+category,
							error:function(){
								alert("Error");
							},
							success:function(data){
								$("#paper").empty();
								$('#espere').hide(0);
								$('#filters').show(500);
								$("#block").empty();
								$("#block").append(data);
								if($('#journal').val()=="select"){
									alert("There were no results found.");
								}else{
									highchart();
									$("#journal option").each(function()
									{
										addSerie($(this).text(),Math.floor(Math.random() * 60) + 1,Math.floor(Math.random() * 60) + 1,Math.floor(Math.random() * 60) + 1);	
									});
									chart1.redraw();
								}
							}
						});
					});
				}
			});
		});



	

     function highchart(){

			chartOptions = {
		      chart: {
		        renderTo: 'grafico',
		        type: 'bubble',
	            zoomType: 'xy'
		        },
		        title: {
		            text: ''
		        },
		        subtitle: {
		            text: 'Source: Google Scholar'
		        },
		        xAxis: {
		            title: {
		                enabled: true,
		                text: 'Height (cm)'
		            },
		            startOnTick: true,
		            endOnTick: true,
		            showLastLabel: true
		        },
		        legend: {
		        	enabled: true,
		         	layout: 'vertical',
		            align: 'right',
		            verticalAlign: 'top',
		            floating: false,
		            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
		        },

		        yAxis: {
		            title: {
		                text: 'Weight (kg)'
		            }
		        },

		        plotOptions: {

		         	 series: {
			                cursor: 'pointer',
			                point: {
			                    events: {
			                        click: function(e) {
			                        		var papers = searchPapers(this.series.name);
			                        		//setTimeout(continueExecution, 3);

			                        		for(var key in papers){
												alert('asdasd');
											}
								                        	
			                                hs.htmlExpand(null, {
		                                    pageOrigin: {
		                                       // x: e.pageX || e.clientX,
		                                       // y: e.pageY || e.clientY
		                                       x: -300,
		                                       y: 300
		                                    },
		                                    headingText: this.series.name,
		                                    maincontentText: 'Aqui irán los papers y sus links algún dia',
		                                    width: 400,
		                                    height: 700
		                                });
			                        }
			                    }
			                }
			            }
	
		            
		        },
		        tooltip: {
		                    headerFormat: '<b>{series.name}</b><br>',
		                    pointFormat: '{point.x} cm, {point.y} kg'
		        }
		    
		        
		};
	chart1 = new Highcharts.Chart(chartOptions);		

}	

	function addSerie(nombre,x,y,z){

			chart1.addSeries({                        
			     name: nombre,
		         //color: 'rgba(223, 83, 83, .5)',
		         color: 'rgba(51, 102, 153, .5)',
		         data: [[x,y,z]]  
			}, false);
			chart1.redraw();

	}
	function searchPapers(journal){
				//$('#gif').show(500);
				//$("#search").hide(0);
				//var consulta = $("#consulta").val();
                $.ajax({
                    type:"POST",
                    url: "test.py",
                    data:"journal="+journal,
					//dataType: "json",
                    success:function(data){
						var x=String(data);
						var obj=$.parseJSON(data);
						$("#paper").empty();
						$.each(obj, function(k, v) {
						    $('<option>').val(v.journal_url).text(v.title).appendTo('#paper');
						});
										//alert(obj[1]['journal_url']);
						return obj;
                    },
					error: function (xhr, ajaxOptions, thrownError) {
					   alert(xhr.status);
					   alert(xhr.responseText);
					   alert(thrownError);
					}
                });

            
      }    

	//super indentación
	(function(b,a){if(!b){return}var c=b.Chart.prototype,d=b.Legend.prototype;b.extend(c,{legendSetVisibility:function(h){var i=this,k=i.legend,e,g,j,m=i.options.legend,f,l;if(m.enabled==h){return}m.enabled=h;if(!h){d.destroy.call(k);e=k.allItems;if(e){for(g=0,j=e.length;g<j;++g){e[g].legendItem=a}}k.group={}}c.render.call(i);if(!m.floating){f=i.scroller;if(f&&f.render){l=i.xAxis[0].getExtremes();f.render(l.min,l.max)}}},legendHide:function(){this.legendSetVisibility(false)},legendShow:function(){this.legendSetVisibility(true)},legendToggle:function(e){if(typeof e!="boolean"){e=(this.options.legend.enabled^true)}this.legendSetVisibility(e)}})}(Highcharts));
	$('#toggleBtn').click(function () { chart1.legendToggle();});
          
	</script>

</html>