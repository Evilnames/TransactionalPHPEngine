<?php

	require('../transactionalEngine.php');


	/**
		Given A and B let C = A * B - A
		Given A and C let D = A * C
		Given D and C let E = C - D
		Given E and A let F = A + F
	**/

	/**
		Test Rules System
	**/
	$Rules = array(
		'GetC' => array(
			'type' => 'resultfromvariables',
			'returnedVariable'=>'C',
			'action' => array(
				'1'=>array(
					'Operator'  => 'A',
					'Denomator' => 'B',
					'Action'    => 'Multiply'
				),
				'2' => array(
					'Operator' =>  'C',
					'Denomator' => 'A',
					'Action'    => 'Minus'
				)
			)
		),
		'EvaluateD'=>array(
			'type' => 'resultfromvariables',
			'returnedVariable' => 'D',
			'action' => array(
				'1'=>array(
					'Operator' =>  'A',
					'Denomator' => 'C',
					'Action'    => 'Multiply'
				)
			)
		),
		'EvaluateE'=>array(
			'type' => 'resultfromvariables',
			'returnedVariable' => 'E',
			'action' => array(
				'1'=>array(
					'Operator' =>  'C',
					'Denomator' => 'D',
					'Action'    => 'Minus'
				)
			)
		),
		'EvaluateF'=>array(
			'type' => 'resultfromvariables',
			'returnedVariable' => 'F',
			'action' => array(
				'1'=>array(
					'Operator' =>  'A',
					'Denomator' => 'E',
					'Action'    => 'Plus'
				)
			)
		)
	);

	$Data  = array(
		'1' => array(
			'A' => '5',
			'B' => '6'
		),
		'2' => array(
			'A' => '100',
			'B' => '999'
		)
	);

	$TranTest = new transactionalEngine($Rules, $Data);
	$TranTest->process();
	var_dump($TranTest->getData());
	echo '<br>';
	echo '<br>';
	echo $TranTest->getTime();
?>