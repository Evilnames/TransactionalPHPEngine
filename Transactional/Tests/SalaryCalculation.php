<?php
	require('../transactionalEngine.php');
	require('../../formatArray.php');

	$Rules = array(
		/**
			Salary CAlculation
		**/
		'SalaryAccrue' => array(
			'type' => 'conditionalresult',
			'conditions' => array(
				'1' => array(
					'variable'=>'salariedYn',
					'action' => 'equal',
					'value' => '1'
				)
			),
			'result' => array(
				'AddSalary' => array(
					'type'=>'addtoresultset',
					'action' => array(
						'1'=>array(
							'transaction' => array(
								'entity' => '#entity',
								'paycode' => 'Salary',
								'flatamount' => '#payrate',
								'total' => '#payrate',
								'transgroup' => '#event'
							)
						)
					)
				),
				'AcumulateResult'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'grossPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'payrate',
							'Denomator' => 'salaryMultiplier',
							'Action'    => 'Multiply'
						),
					)
				),
				'AddToNetPay'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'netPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'payrate',
							'Denomator' => 'salaryMultiplier',
							'Action'    => 'Multiply'
						),
					)
				)
			)
		),

		/**
			Regular Calculation
		**/
		'RegularAccrue' => array(
			'type'=>'conditionalresult',
			'conditions' => array(
				'1' => array(
					'variable'=>'salariedYn',
					'action'=>'equal',
					'value'=>'0'
				)
			),
			'result' => array(
				'figureTotal'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'regularTotal',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'hoursWorked',
							'Denomator' => 'payrate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AcumulateResult'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'grossPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'hoursWorked',
							'Denomator' => 'payrate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AddToNetPay'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'netPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'hoursWorked',
							'Denomator' => 'payrate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AddRegular' => array(
					'type'=>'addtoresultset',
					'action' => array(
						'1'=>array(
							'transaction' => array(
								'entity' => '#entity',
								'paycode' => 'Regular',
								'base' => '#hoursWorked',
								'rate' => '#payrate',
								'total' => '#regularTotal',
								'transgroup' => '#event'
							)
						)
					)
				)
			)
		),

		/**
			Overtime CAlculation
		**/
		'OvertimeAccrue' => array(
			'type'=>'conditionalresult',
			'conditions' => array(
				'1' => array(
					'variable'=>'salariedYn',
					'action'=>'equal',
					'value'=>'0'
				)
			),
			'result' => array(
				'calculateOvertimeRate'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'overtimeRate',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'overtimeMultiplier',
							'Denomator' => 'payrate',
							'Action'    => 'Multiply'
						),
					)
				),

				'figureTotal'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'overtimeTotal',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'overtimeHours',
							'Denomator' => 'overtimeRate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AcumulateResult'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'grossPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'overtimeHours',
							'Denomator' => 'overtimeRate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AddToNetPay'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'netPay',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'overtimeHours',
							'Denomator' => 'overtimeRate',
							'Action'    => 'Multiply'
						),
					)
				),
				'AddOvertime' => array(
					'type'=>'addtoresultset',
					'action' => array(
						'1'=>array(
							'transaction' => array(
								'entity' => '#entity',
								'paycode' => 'Overtime',
								'base' => '#overtimeHours',
								'rate' => '#overtimeRate',
								'total' => '#overtimeTotal',
								'transgroup' => '#event'
							)
						)
					)
				)

			)
		),

		/**
			FICA Calculation
		**/
		'FICACalculation' => array(
			'type'=>'conditionalresult',
			'conditions' => array(
				'1' => array(
					'variable'=>'ficaYTD',
					'action'=>'lessthan',
					'value'=>'6000'
				)
			),
			'result' => array(
				'calculateFICA'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'ficaTotal',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'ficaPercent',
							'Denomator' => 'grossPay',
							'Action'    => 'Multiply'
						),
					)
				),
				'updateFICAYtd'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'ficaYTD',
					'resultType' => 'Plus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'ficaPercent',
							'Denomator' => 'grossPay',
							'Action'    => 'Multiply'
						),
					)
				),
			)
		),

		/**
			Is the resulting FICA pushing the FICAYTD over the limits?  If it is update the ficapercent
		**/
		'FICACap' => array(
			'type'=>'conditionalresult',
			'conditions'=>array(
				'1' => array(
					'variable'=>'ficaYTD',
					'action'=>'greaterthan',
					'value'=>'6000'
				)
			),
			'result'=> array(
				'updateFicaTotal'=>array(
					'type'=>'resultfromvariables',
					'returnedVariable'=>'ficaTotal',
					'resultType'=>'Minus',
					'action'=>array(
						'1'=>array(
							'Numerator'=>'ficaYTD',
							'Denomator'=>'ficaCap',
							'Action' => 'minus'
						)
					)
				),
				'resetFICAYtdValue'=>array(
					'type'=>'resultfromvariables',
					'returnedVariable'=>'ficaYTD',
					'resultType'=>'Minus',
					'action'=>array(
						'1'=>array(
							'Numerator'=>'ficaYTD',
							'Denomator'=>'ficaCap',
							'Action' => 'minus'
						)
					)
				)
			)
		),

		/**
			Update the arrays with the information.
		**/
		'updateFICAResults'=> array(
			'type'=>'conditionalresult',
			'conditions'=>array(
				'1' => array(
					'variable'=>'ficaTotal',
					'action'=>'greaterthan',
					'value'=>'0'
				)
			),
			'result'=>array(
				'postFICA' => array(
					'type'=>'addtoresultset',
					'action' => array(
						'1'=>array(
							'transaction' => array(
								'entity' => '#entity',
								'paycode' => 'FICA',
								'base' => '#grossPay',
								'rate' => '#ficaPercent',
								'total' => '#ficaTotal',
								'transgroup' => '#event'
							)
						)
					)
				),
				'updateNetPay'=>array(
					'type' => 'resultfromvariables',
					'returnedVariable'=>'netPay',
					'resultType' => 'Minus',
					'action'=>array(
						'1'=>array(
							'Numerator'  => 'ficaPercent',
							'Denomator' => 'grossPay',
							'Action'    => 'Multiply'
						),
					)
				),
			)
		)
	);

	$Data = array();

	//Generate Test Payroll data for Speed Tests
	for ($i=0; $i < 10; $i++) { 
		$arr = array();
		$arr['entity'] = rand(0, 1000);
		$arr['hoursWorked'] = rand(0, 80);
		$arr['overtimeHours'] = rand(0, 20);
		$arr['salaryMultiplier'] = 1;
		$arr['event'] = 'BW-140629';
		$arr['salariedYn'] = rand(0,1);
		$arr['payrate'] = ($arr['salariedYn'] == 1) ? rand(1000, 6000) : rand(0,50);
		$arr['ficaYTD'] = rand(0,6000);
		$arr['ficaPercent'] = .015;
		$arr['state'] = 'AZ';
		$arr['overtimeMultiplier'] = 1.5;
		$arr['ficaCap'] = 6000;

		array_push($Data, $arr);
	}

/**
	$Data = array(
		'1' => array(
			'entity' => 'TAG-Kremer-00125',
			'hoursWorked' => '40',
			'overtimeHours' => '0',
			'salaryMultiplier'=>'1',
			'event'	=> 'BW-140629',
			'salariedYn'=>'1',
			'payrate' => '4000',
			'ficaYTD' => '2000',
			'ficaPercent' => '.015',
			'state'=>'AZ',
			'overtimeMultiplier' => '1.5'
		),
		'2' => array(
			'entity' => 'TAG-Biltis-00001',
			'hoursWorked' => '40',
			'overtimeHours' => '10',
			'event'	=> 'BW-140629',
			'salariedYn'=>'0',
			'payrate' => '23',
			'ficaYTD' => '2000',
			'ficaPercent' => '.015',
			'state'=>'AZ',
			'overtimeMultiplier' => '1.5'
		),
		'3' => array(
			'entity' => 'TAG-Lincoln-00024',
			'hoursWorked' => '40',
			'overtimeHours' => '0',
			'salaryMultiplier'=>'1',
			'event'	=> 'BW-140629',
			'salariedYn'=>'1',
			'payrate' => '2300',
			'ficaYTD' => '6500',
			'ficaPercent' => '.015',
			'state'=>'AZ',
			'overtimeMultiplier' => '1.5'
		),
		'4' => array(
			'entity' => 'TAG-Simson-00002',
			'hoursWorked' => '23',
			'overtimeHours' => '5',
			'event'	=> 'BW-140629',
			'salariedYn'=>'0',
			'payrate' => '12.22',
			'ficaYTD' => '500',
			'ficaPercent' => '.015',
			'state'=>'AZ',
			'overtimeMultiplier' => '1.5'
		)
	);
**/

	$TranTest = new transactionalEngine($Rules, $Data);
	$TranTest->process();
	html_show_array($TranTest->getData());
	echo '<br>';
	echo '<br>';
	html_show_array($TranTest->getResultSet());
	echo '<br>';
	echo '<br>';
	echo $TranTest->getTime();
?>