<!--
You are free to copy and use this sample in accordance with the terms of the
Apache license (http://www.apache.org/licenses/LICENSE-2.0.html)
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Google Visualization API Sample
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        
	
	<?php
	$row = 1;
	$arr = array();
	if (($handle = fopen("/home/swara/stats/CallsByDate.csv", "r")) !== FALSE) 
	{
    		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    		{
        		$num = count($data);
        		$row++;
        		for ($c=0; $c < $num; $c++)
        		{
            			if ($data[$c] != 'Date' and $data[$c] != 'Number of Calls')
            			{
                			#echo $data[$c] . " " ;
                			$arr[]=$data[$c];
                			#if (($c%2) == FALSE)
                			#{
                				#$arr[$data[$c]] = $data[$c+1];
                			#}
               
                
            			}		
            
       			}		
        		if ($row==1000)
        		{
            			break;
        		}
        
    		}
    		#print_r($arr);
    		fclose($handle);
	}	
	echo "var data = new google.visualization.DataTable();"
        echo "data.addColumn('string', 'Date');"
        echo "data.addColumn('number', 'Calls');"
        echo "data.addRow(['A', 11]);"
        echo "data.addRow(['B', 2]);"
        echo "data.addRow(['C', 4]);"
        echo "data.addRow(['D', 8]);"
        echo "data.addRow(['E', 7]);"
        echo "data.addRow(['F', 7]);"
        echo "data.addRow(['G', 8]);"
        echo "data.addRow(['H', 4]);"
        echo "data.addRow(['I', 2]);"
        echo "data.addRow(['J', 3.5]);"
?>       
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('visualization')).
            draw(data, {curveType: "function",
                        width: 500, height: 400,
                        vAxis: {maxValue: 10}}
                );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 500px; height: 400px;"></div>
  </body>
</html>
