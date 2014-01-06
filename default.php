<!DOCTYPE html> 
<html>
<meta charset="UTF-8">
<head><title>Data generator and Viewer</title></head>
<link rel="stylesheet" type="text/css" href="table.css" media="screen" />
<script src="jquery-1.10.2.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script type="text/javascript">
/*This is our assignment:
- Build a system to collect data that is generated on an interval (once a minute/hour/day/etc. Store in a database; record time and data value. 
- Build a web app that displays a graph of the collected data with a choice of intervals (per min, per hour, per day, etc.). Add a table report of the data with column headings. The table should be placed below the graph.
*/
var sensorInterval = setInterval(function(){sensorCheck()} , 60000);
var updateInterval = setInterval(function(){userUpdate()} , 1000);
var isActive = false;
var count=59;
function userUpdate()
{
	if(isActive)
	{
		document.getElementById('divUpdate').innerHTML = "Next update in "+ count +" seconds";	//Range 0-100
	}
	else
	{
		document.getElementById('divUpdate').innerHTML = "Not generating any data";
	}
	if(count>0)
		count--;
	else
		count=59;
	
	if(count < 55)
		document.getElementById('divError').innerHTML = " ";
}
function sensorCheck()
{
	if(isActive)
	{
		var reading = Math.round(Math.random()*100);
		document.getElementById('divSensor').innerHTML +=  reading + ", ";	//Range 0-100
		count=59;
		
		$.getJSON( "/hackerati/add.php", { value : reading} , function( data ) 
		{
			if(data['success'] == 0)
				document.getElementById('divError').innerHTML = "Unable to reach server";
			else if(data['success'] == 1)
				document.getElementById('divError').innerHTML = "Update Successful";
		});
	}
}
function myStopFunction()
{
clearInterval(myVar);
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function Generator()//Returns x values in the range 0-100
{
	var quantity = document.getElementById('btnGen').value;
	var start="Start generating data";
	var stop="Stop generating data";
	if(quantity == start)
	{
		isActive=true;
		document.getElementById('btnGen').value=stop;
	}
	else
	{
		isActive=false;
		document.getElementById('btnGen').value=start;
	}
}

function timeConverter(UNIX_timestamp){
 var a = new Date(UNIX_timestamp);
 var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
     var year = a.getUTCFullYear();
     var month = months[a.getUTCMonth()];
     var date = a.getUTCDate();
     var hour = a.getUTCHours();
     var min = a.getUTCMinutes();
     var sec = a.getUTCSeconds();
     var time = date+' '+month+','+year+' '+hour+':'+min+':'+sec ;
     return time;
 }

$(function() {
	var url2='/hackerati/chart.php';
	var url='http://www.highcharts.com/samples/data/jsonp.php?filename=aapl-c.json&callback=?';
	$.getJSON( url2 , function(data) {
		// Create the chart
		$('#container').highcharts( 'StockChart',{
			

			rangeSelector : {
				selected : 1
			},

			title : {
				text : 'Temperature of reactor'
			},
			
			series : [{
				name : 'temp',
				data : data,
				tooltip: {
					valueDecimals: 2
				}
			}]
		});
		
		//Table
		var table = document.getElementById("tableData");
		for(var i=0;i<data.length;i++)
		{
		// Create an empty <tr> element and add it to the 1st position of the table:
		var row = table.insertRow(-1);

		// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);

		// Add some text to the new cells:
		var strTime = timeConverter(data[i][0]);
		cell1.innerHTML = strTime;
		cell2.innerHTML = data[i][1];
		}
	});

});

</script>
<body>
<div style="text-align: center;">
	<input type="button" id="btnGen" value="Start generating data" alt="Click this button to activate the reactor and start recoding temperature values every minute" onclick="Generator()"/>
	<div id="divUpdate"></div>
	<div id="divSensor"></div>
	<div id="divError"></div>
	<div id="container" style="height: 500px; min-width: 500px"></div>
	<div class="CSSTableGenerator" >
		<table id='tableData' border='1'> <thead>
			<tr>
				<th>Timestamp</th>
				<th>Temperature</th>
			</tr>
		</thead>
		</table>
	</div>
</div>
</body>
</html>