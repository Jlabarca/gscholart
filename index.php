<?php
	require_once("php/facade.php");
	$facade=new Facade();
	$countryList=$facade->retrieveCountries();
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
				<span id="category_block">
					<select id="category">
						<option value="">select</option>
					</select>
				</span>
				
				
				<span style='display:none' id="block">
					<select disabled='disabled' id="journal">
						<option value="">select</option>
					</select>
				</span>
				
				<input type="button" value="Show Legend" id="toggleBtn" />
				
				<span style='display:none' id="block2">
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
			$("#country").change(function(){
				country=$('#country').val();
				$.ajax({
					type:"POST",
					url:"php/retrieveCategories.php",
					data:"country="+country,
					error:function(){
						alert("Error");
					},
					success:function(data){
						$("#grafico").empty();		
						$("#category").empty();
						$("#category").append(data);
					}
				});
			});
		});	
		
		$(document).ready(function(){
			$('#category').change(function(){
				country=$('#country').val();
				category=$('#category').val();
				alert(country+"--<"+category);
				
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
									var size = $("#journal option").length;
									var data = new Array(size);
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
									data.sort(function(a, b) {
										    if (a[1] === b[1]) {
										        return 0;
										    }
										    else {
										        return (a[1] < b[1]) ? -1 : 1;
										    }
									});
									//for(x in data)
									//	alert(data[x][1]);

									var qx = [data[parseInt(size/4)][1],data[parseInt(2*size/4)][1],data[parseInt(3*size/4)][1]];
									data.sort(function(a, b) {
										    if (a[2] === b[2]) {
										        return 0;
										    }
										    else {
										        return (a[2] < b[2]) ? -1 : 1;
										    }
									});
									var qy = [data[parseInt(size/4)][2],data[parseInt(2*size/4)][2],data[parseInt(3*size/4)][2]];
									if(chart1!=null)
										chart1.destroy();
									highchart(qx,qy);
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
     function highchart(qx,qy){
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
		                value: qx[0],
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
		                value: qx[1],
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
		                value: qx[2],
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
		                value: qy[0],
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
		                value: qy[1],
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
		                value: qy[2],
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
                                maincontentText: "<table border='1''>"+cargarp(obj)+"</table>",
                                width: 400,
                                height: 700
                });
      }
function cargarp(obj){
	var str = '',journal_url='-',citations_url='-',pdf_url='-';
	str+="<tr><td>Title</td><td>Journal URL</td><td>Citations URL</td><td>PDF URL</td></tr>";
	for(var key in obj){
		if(obj[key]['journal_url']!=null)
			journal_url='<a href='+obj[key]['journal_url']+' target="blank_">Go</a>';	
		if(obj[key]['citations_url']!=null)
			citations_url='<a href='+obj[key]['citations_url']+' target="blank_">Go</a>';
		if(obj[key]['pdf_url']!=null)
			pdf_url='<a href='+obj[key]['pdf_url']+' target="blank_">Go</a>';
			
		str += "<tr><td>"+obj[key]['title']+'</td><td>'+journal_url+'</td><td>'+citations_url+'</td><td>'+pdf_url+'</td></tr>';
	}
	return str;
}

	//super indentación
	(function(b,a){
		if(!b){
			return
		}
		var c=b.Chart.prototype,d=b.Legend.prototype;
		b.extend(c,{legendSetVisibility:function(h){
					var i=this,k=i.legend,e,g,j,m=i.options.legend,f,l;
					if(m.enabled==h){
						return
					}m.enabled=h;
					if(!h){
						d.destroy.call(k);
						e=k.allItems;
						if(e){
							for(g=0,j=e.length;g<j;++g){
								e[g].legendItem=a
							}
						}
						k.group={}
					}
					c.render.call(i);
					if(!m.floating){
						f=i.scroller;
						if(f&&f.render){
							l=i.xAxis[0].getExtremes();
							f.render(l.min,l.max)}
						}
				},legendHide:function(){
					this.legendSetVisibility(false)
				},legendShow:function(){
					this.legendSetVisibility(true)
				},legendToggle:function(e){
					if(typeof e!="boolean"){
						e=(this.options.legend.enabled^true)
					}
					this.legendSetVisibility(e)
				}
			})}(Highcharts));
	




	$('#toggleBtn').click(function () { 
		chart1.legendToggle();
        chart1.xAxis[0].setExtremes(0.3,2.1);	
        chart1.zoomOut();
	});
/*
plotBand = chart1.xAxis[0].addPlotBand({
            from: 5.5,
            to: 7.5,
            color: '#FCFFC5',
            id: 'plot-band-1'
        });

    $buttonChange.click(function() {
        $.extend(plotBand.options, {
            color: '#000',
            to: 10,
            from: 2
        });
        plotBand.svgElem.destroy();
        plotBand.svgElem = undefined;
        plotBand.render();
        
    });
    
    $buttonChangeSize.click(function() {
        $.extend(plotBand.options, {
            to: 5,
            from: 1.5
        });
        plotBand.render();
    });      
    */    
	</script>

</html>