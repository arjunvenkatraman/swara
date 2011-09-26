<html>
<head>
</head>

<body>
<center>  
<?php
include_once 'config.inc.php';
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
#print_r($arr);
$x=0;
$y=0;
$valarray=array();
for ($c=0; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=$arr[$c];
                $x++;
}
$x=0;
$y=1;
for ($c=1; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=(int)$arr[$c];
                $x++;
}
    
#print_r($valarray);
$chart = new QLinechartGoogleGraph;
$chart	->addDrawProperties
            (
		array
                (
			"title"=>'Calls By Date',
		)
	    )
                ->addColumns
                (
			array
                        (
				array('string', 'Date'),
				array('number', 'Calls'),
                        )
		)
		->setValues
                (
			$valarray
		);

echo "<hr>";
echo "<br>";
echo $chart->render();
$row = 1;
$arr = array();
exec("head -6 /home/swara/stats/CallsByProvider.csv > /home/swara/stats/CallsByProvider-tmp.csv");
if (($handle = fopen("/home/swara/stats/CallsByProvider-tmp.csv", "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    {
        $num = count($data);
        $row++;
        for ($c=0; $c < $num; $c++)
        {
            if ($data[$c] != 'Provider' and $data[$c] != 'Number of Calls')
            {
                #echo $data[$c] . " " ;
                $arr[]=$data[$c];
        	#if (($c%2) == FALSE)
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
#print_r($arr);
$x=0;
$y=0;
$valarray=array();
for ($c=0; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=$arr[$c];
                $x++;
}
$x=0;
$y=1;
for ($c=1; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=(int)$arr[$c];
                $x++;
}

$chart = new QPiechartGoogleGraph;
$chart	->addDrawProperties(
			array(
				"title"=>'Calls by Provider',
				)
			)
		->addColumns(
			array(
				array('string', 'Provider'),
				array('number', 'Calls')
				)
			)
		->setValues(
			$valarray
		);
echo "<hr>";
echo "<br>";
echo $chart->render();


$row = 1;
$arr = array();
exec("head -6 /home/swara/stats/CallsByCircle.csv > /home/swara/stats/CallsByCircle-tmp.csv");
if (($handle = fopen("/home/swara/stats/CallsByCircle-tmp.csv", "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    {
        $num = count($data);
        $row++;
        for ($c=0; $c < $num; $c++)
        {
            if ($data[$c] != 'Circle' and $data[$c] != 'Number of Calls')
            {
                #echo $data[$c] . " " ;
                $arr[]=$data[$c];
        	#if (($c%2) == FALSE)
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
#print_r($arr);
$x=0;
$y=0;
$valarray=array();
for ($c=0; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=$arr[$c];
                $x++;
}
$x=0;
$y=1;
for ($c=1; $c < count($arr); $c+=2)
{
    
                $valarray[$c][0]=$x;
                $valarray[$c][1]=$y;
                $valarray[$c][2]=(int)$arr[$c];
                $x++;
}
$chart = new QPiechartGoogleGraph;
$chart	->addDrawProperties(
			array(
				"title"=>'Calls by Circle',
				)
			)
		->addColumns(
			array(
				array('string', 'Circle'),
				array('number', 'Calls')
				)
			)
		->setValues(
			$valarray
		);
echo "<hr>";
echo "<br>";
echo $chart->render();

#echo $chart->getReferenceLink();

?>
</center>
</body>
</html>