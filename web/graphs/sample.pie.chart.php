<html>
<head>
</head>

<body>
<?php

include_once 'config.inc.php';

$chart = new QPiechartGoogleGraph;
$chart	->addDrawProperties(
			array(
				"title"=>'Company Performance',
				)
			)
		->addColumns(
			array(
				array('string', 'Task'),
				array('number', 'Hours per Day')
				)
			)
		->setValues(
			array(
				array(0, 0, 'Work'),
				array(0, 1, 11),
				array(1, 0, 'Eat'),
				array(1, 1, 2),
				array(2, 0, 'Commute'),
				array(2, 1, 2),
				array(3, 0, 'Watch TV'),
				array(3, 1, 2),
			)
		);
echo $chart->render();

echo $chart->getReferenceLink();


?></body>
</html>