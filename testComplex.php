<?php
	require('TranRule.php');

	$rList = array(
		'AccrueZeroToTweleve' => array(
			'name' => 'Accrue Zero to Tweleve Months',
			'conditions' => array(
				'1'=>array(
					'rule' => 'greaterthanorequal',
					'value'  => '0',
					'variable' => 'workedmonths'
				),
				'2'=>array(
					'rule'=>'lessthanorequal',
					'value'=>'12',
					'variable'=>'workedmonths'
				),
				'3'=>array(
					'rule'=>'equal',
					'value'=>'1',
					'variable'=>'month'
				),
				'4'=>array(
					'rule'=>'equal',
					'value'=>'1',
					'variable'=>'payrollofmonth'
				),
				'5'=>array(
					'rule'=>'equal',
					'value'=>'true',
					'variable'=>'regularPayroll'
				)
			),
			'Success'=>array(
				'1' => array(
					'action' => 'result',
					'event' => array(
						'entity' => '#entity',
						'event' => 'accrue',
						'value' => '20',
						'date' => Date('m/d/y'),
						'transgroup' => '#transgroup',
						'Alex' => '#transgroup'

					)
				)
			),
			'Failure'=>array(
				'1' => array(
					'action' => 'display',
					'value'  => ''
				)
			)
		),
		'AccrueThirteenToForeverrer' => array(
			'name'	 => 'Accrue Zero to Tweleve Months',
			'conditions' => array(
				'1'=>array(
					'rule' => 'greaterthanorequal',
					'value'  => '13',
					'variable' => 'workedmonths'
				),
				'2'=>array(
					'rule'=>'lessthanorequal',
					'value'=>'9999',
					'variable'=>'workedmonths'
				),
				'3'=>array(
					'rule'=>'equal',
					'value'=>'1',
					'variable'=>'month'
				),
				'4'=>array(
					'rule'=>'equal',
					'value'=>'1',
					'variable'=>'payrollofmonth'
				),
				'5'=>array(
					'rule'=>'equal',
					'value'=>'true',
					'variable'=>'regularPayroll'
				)
			),
			'Success'=>array(
				'1' => array(
					'action' => 'result',
					'event' => array(
						'entity' => '#entity',
						'event' => 'accrue',
						'value' => '40',
						'date' => Date('m/d/y'),
						'transgroup' => '#transgroup'
					)
				)
			),
			'Failure'=>array(
				'1' => array(
					'action' => 'display',
					'value'  => ''
				)
			)
		),
		'Unfreeze Time' => array(
			'name' => 'Rule Name',
			'conditions' => array(
				'2'=>array(
					'rule'=>'equal',
					'value'=>'3',
					'variable'=>'workedmonths'
				),
				'4'=>array(
					'rule'=>'equal',
					'value'=>'1',
					'variable'=>'payrollofmonth'
				),
				'5'=>array(
					'rule'=>'equal',
					'value'=>'true',
					'variable'=>'regularPayroll'
				)
			),
			'Success'=>array(
				'1' => array(
					'action' => 'result',
					'event' => array(
						'entity' => '#entity',
						'event' => 'release',
						'value' => '#frozenHours',
						'date' => Date('m/d/y'),
						'transgroup' => '#transgroup'
					)
				)
			),
			'Failure'=>array(
				'1' => array(
					'action' => 'display',
					'value'  => ''
				)
			)
		)
	);

	$data = array(
		'1' => array(
			'entity'            => 'TAG-Kremer-00125',	
			'workedmonths'		=>	8,
			'month'				=>	1,
			'payrollofmonth'	=>	1,
			'regularPayroll'    =>  true,
			'transgroup'		=>  'bw-140105',
			'frozenHours'		=>	'8'
		),
		'2' => array(
			'entity'            => 'TAG-Biltis-00001',	
			'workedmonths'		=>	36,
			'month'				=>	1,
			'payrollofmonth'	=>	1,
			'regularPayroll'    =>  true,
			'transgroup'		=>  'bw-140105',
			'frozenHours'		=>	'0'
		)
	);

	$PTO = new TransactionRules($rList, $data);
	$PTO->testRules();
	var_dump($PTO->getResult());
?>
