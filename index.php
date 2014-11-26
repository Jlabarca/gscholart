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
									var i = 0;
									var data = new Array($("#input1 option").length);
									var cuartx=0;
									var cuarty=0;
									$("#journal option").each(function()
									{
										var aux = loadJournalData($(this).text());
										var split=aux.split(',');
										var sjr=parseFloat(split[0]);
										var docs=parseFloat(split[1]);
										var hindex=parseFloat(split[2]);
										data[i] = new Array(4);
										data[i][0] =$(this).text();
										data[i][1] =sjr;
										data[i][2] =docs;
										data[i++][3] =hindex;
										//alert(data[i][0] +"-"+data[i][1]);
									});
									for (x in data) {
										cuartx += data[x][2]
									    cuarty += data[x][1];
									}
									cuartx = cuartx/4;
									cuarty = cuarty/4;
									highchart(cuartx,cuarty);
									for(x in data)
										addSerie(data[x][0],data[x][1],data[x][2],data[x][3]);
									chart1.redraw();	
									//chart1.legendToggle();	
								}
							}
						});
					});
				}
			});
		});

	function loadJournalData(name){
			var aux =$.ajax({
				type:"POST",
				url:"php/retrieveJournalData.php",
				data:"name="+name,
				error:function(){
					alert("Error");
				},
				success:function(data){					
				},
				async: false
			});
			return aux.responseText;
	}
     function highchart(cuartx,cuarty){
     		alert(cuartx);
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
		            text: ''
		        },
		        xAxis: {
		            title: {
		                enabled: true,
		                text: 'SJR'
		            },
		            startOnTick: true,
		            endOnTick: true,
		            showLastLabel: true,
		            plotLines: [{
		                value: [[cuarty]],
		                color: 'black',
		                width: 1,
		                label: {
		                    text: 'Q1',
		                     rotation:0,
   							align: 'bottom',
		                    style: {
		                        color: 'gray'
		                    }
			                }
			            },
			            {
		                value: cuarty*2,
		                color: 'black',
		                width: 1,
		                label: {
		                    text: ' Q2',
		                    rotation:0,
   							align: 'bottom',
		                    style: {
		                        color: 'gray'
		                    }

			                }
			            },
			               {
		                value: cuarty*3,
		                color: 'black',
		                width: 1,
		                label: {
		                    text: 'Q3',
		                     rotation:0,
   							align: 'bottom',	
		                    style: {
		                        color: 'gray'
		                    }
			                }
			            }
			            ]
        			
		        },
		        legend: {
		        	enabled: false,
		         	layout: 'vertical',
		            align: 'right',
		            verticalAlign: 'top',
		            floating: false,
		            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
		        },

		        yAxis: {
		            title: {
		                text: 'Documents'
		            },
                    labels: {
                     	
                 	},
                 	plotLines: [{
		                value: 0.5,
		                color: 'black',
		                width: 1,
		                label: {
		                    text: 'Q1',
		                    align: 'left',
		                    style: {
		                        color: 'gray'
		                    }
			                }
			            },
			            {
		                value: 1.25,
		                color: 'black',
		                width: 1,
		                height: 1,
		                label: {
		                    text: 'Q2',
		                    align: 'left',
		                    style: {
		                        color: 'gray'
		                    }
			                }
			            },
			               {
		                value: 2,
		                color: 'black',
		                width: 1,
		                label: {
		                    text: 'Q3',
		                    align: 'left',
		                    style: {
		                        color: 'gray'
		                    }
			                }
			            }
			            ]
        			},
		        plotOptions: {

		         	 series: {

			                point: {
			                    events: {
			                        click: function() {
			                        		searchPapers(this.series.name,this);								                        	
			                         
			                        }
			                    }
			                }
			            }
	
		            
		        },
		        tooltip: {
		                    headerFormat: '<b>{series.name}</b><br>',
		                    pointFormat: 'Documents: {point.x}, SJR: {point.y},  H index: {point.z}' 
		        }
		    
		        
		};
	chart1 = new Highcharts.Chart(chartOptions);		

}	
	function addSerie(nombre,x,y,z){
			var chart = $('#grafico').highcharts();
			chart.addSeries({                        
			     name: nombre,
		         color: 'rgba('+(Math.floor(Math.random() * 205) + 70)+','+(Math.floor(Math.random() * 205) + 70)+','+(Math.floor(Math.random() * 205) + 70) +',2)',
		         //color: 'rgba(51, 102, 153, .5)',
		         data: [[x,y,z]]  
			}, false);
			//chart.redraw();

	}
	function searchPapers(journal,e){
                $.ajax({
                    type:"POST",
                    url: "gsearch.py",
                    data:"journal="+journal,
					//dataType: "json",
                    success:function(data){
						var x=String(data);
						var obj=$.parseJSON(data);
						$("#paper").empty();
						$.each(obj, function(k, v) {
						    $('<option>').val(v.journal_url).text(v.title).appendTo('#paper');
						});
						papersList(obj,e);
                    },
					error: function (xhr, ajaxOptions, thrownError) {
					   alert(xhr.status);
					   alert(xhr.responseText);
					   alert(thrownError);
					}
                });

            
      }    


      function papersList(obj,e){
      	       hs.htmlExpand(null, {
                                pageOrigin: {
                                   // x: e.pageX || e.clientX,
                                   // y: e.pageY || e.clientY
                                   x: -300,
                                   y: 300
                                },
                                headingText: e.series.name,
                                maincontentText: cargarp(obj),
                                width: 400,
                                height: 700
                });
      }

      function cargarp(obj){
	      	var str = '';
	      	for(var key in obj)
			    str += obj[key]['title'] +'<br/> '+obj[key]['journal_url']+'<br/> ';
			return str;
      }

	//super indentación
	(function(b,a){if(!b){return}var c=b.Chart.prototype,d=b.Legend.prototype;b.extend(c,{legendSetVisibility:function(h){var i=this,k=i.legend,e,g,j,m=i.options.legend,f,l;if(m.enabled==h){return}m.enabled=h;if(!h){d.destroy.call(k);e=k.allItems;if(e){for(g=0,j=e.length;g<j;++g){e[g].legendItem=a}}k.group={}}c.render.call(i);if(!m.floating){f=i.scroller;if(f&&f.render){l=i.xAxis[0].getExtremes();f.render(l.min,l.max)}}},legendHide:function(){this.legendSetVisibility(false)},legendShow:function(){this.legendSetVisibility(true)},legendToggle:function(e){if(typeof e!="boolean"){e=(this.options.legend.enabled^true)}this.legendSetVisibility(e)}})}(Highcharts));
	$('#toggleBtn').click(function () { chart1.legendToggle();});
          
	</script>

</html>