<?php
	require('TranRule.php');

	$rules = array(
		'FormulaTester'=>array(
			'name' => 'Multiply X by Y',
			'conditions' => array(
				'1'=>array(
					'rule' => 'exists',
					'value' => 'x'
				),
				'2'=>array(
					'rule' => 'exists',
					'value' => 'y'
				)
			),
			'Success'=>array(
				'1' => array(
					//Multiply X * Y and add it to the Z variable
					
				)
			),
			'Failure'=>array(
				'1' => array(
					'action' => 'display',
					'value'  => 'Failed'
				)
			)

		)
	);

	$dataSet = array(
		'1' => array(
			'x' => 10,
			'y' => 40,
			'z' => 0
		)
	);


	$FormulaTest = new TransactionRules($rules, $dataSet);
	$FormulaTest->testRules();
	var_dump($FormulaTest->getResult());

?>