<?php

	/**
		Processes a set of rules against the transactional engine.
	**/
	class TransactionRules {
		/**
			Rule List defination :
				Each rule is an array that gets tested in order.
				'Rule' => array(
					'name' => 'Rule Name',
					'conditions' => array(
						'1'=>array(
							'rule' => 'greaterthanorequal',
							'value'  => '5',
							'variable' => 'Age'
						),
						'2'=>array(
							'rule'=>'lessthan',
							'value'=>'100',
							'variable'=>'Age'
						)
					),
					'result'=>array(
						'1' => array(
							'action' => 'display',
							'value'  => '5'
						)
					)
				)

		**/

		/**
			The ruleset manages everything that happens in the lookup
		**/
		private $ruleList = array();

		/**
			DataSet is the context of the system, what are we looking at?
		**/
		private $dataSet = array();

		/**
			As the rule set processes it can add data into the return data pile.
		**/
		private $resultSet = array();

		function __construct($rList, $dSet){
			$this->ruleList = $rList;
			$this->dataSet  = $dSet;
		}

		/**
			Test the rules
		**/
		public function testRules(){
			foreach ($this->dataSet as $d => $dVal) {
				foreach ($this->ruleList as $i => $val) {
					$this->evaluateThisRule($val, $dVal);
				}
			}
		}

		/**
			Get the result set from the system
		**/
		public function getResult(){
			return $this->resultSet;
		}

		/**
			Evaluate a rule set.
		**/	
		private function evaluateThisRule($r, $dVal){
				(bool) $successful = false;
				//Check all the conditions.
				foreach ($r['conditions'] as $i => $val) {
					$successful = $this->testThisCondition($val, $dVal);
					if(!$successful){
						break;
					}
				}

				$toEval = ($successful) ? $r['Success'] : $r['Failure'];

				foreach ($toEval as $e => $eVal) {
					$this->evaluateThisResult($eVal, $dVal);
				}
		}

		/**
			Test a condition against the system.
		**/
		private function testThisCondition($c, $data){
			//Determine if this rule is valid.
			if(array_key_exists('rule', $c)){
				//Evaluate this rule.
				return (bool) $this->processCondition($c, $data);
			} else {
				return (bool) false;
			}
		}

		/**
			Process a conditin and return the result.
		**/
		private function processCondition($c, $data){
			(bool) $passed = false;
			//Allowed Rules
			switch(strtolower($c['rule'])){
				case 'greaterthanorequal':
					$passed = $this->evalgreaterthanorequal($c['value'], $data[$c['variable']]);
					break;
				case 'lessthan':
					$passed = $this->evallessthan($c['value'], $data[$c['variable']]);
					break;
				case 'lessthanorequal':
					$passed = $this->evallessthanorequal($c['value'], $data[$c['variable']]);
					break;
				case 'equal':
					$passed = $this->evalequal($c['value'], $data[$c['variable']]);
					break;
				case 'exists':
					$passed = $this->evalexists($c['value'], $data);
					break;
				default:
					$passed = false;
					break;
			}
			return $passed;
		}

		/**
			Tests a >= value.
		**/
		private function evalgreaterthanorequal($value, $data){
			return (bool) $passed = ($data >= $value) ? true : false;
		}

		/**
			Tests a <= value.
		**/
		private function evallessthanorequal($value, $data){
			return (bool) $passed = ($data <= $value) ? true : false;
		}

		/**
			Tests a < value
		**/
		private function evallessthan($value, $data){
			return (bool) $passed = ($data < $value) ? true : false;
		}

		/**
			Tests a == value
		**/
		private function evalequal($value, $data){
			return (bool) $passed = ($data == $value) ? true : false;
		}

		/**
			Makes sure this variable exists in the dataset
		**/
		private function evalexists($value, $data){

			return (bool) $passed = (array_key_exists($value, $data)) ? true : false;
		}


		/**
			This processes a result set and returns back whatever it has to do to the dSet array.
		**/
		private function evaluateThisResult($result, $data){
			//Test to make sure this result has everything we need.

			if(array_key_exists('action', $result)){
				return (bool) $this->processResult($result, $data);
			} else {
				return (bool) false;
			}
		}

		/**
			Process a result set and do what it is asking.
		**/
		private function processResult($result, $data){
			switch(strtolower($result['action'])){
				case 'display':
					$this->processDisplayAction($result, $data);
					break;
				case 'result':
					$this->addToResultSet($result, $data);
					break;
				default:
					//Nothing happens here.
					break;
			}

			return (bool) true;
		}

		private function processDisplayAction($result, $data){
			echo $result['value'];
		}

		private function addToResultSet($result, $data){
			$newResult = array();


			//For each resultset see if we need to replace any information.
			foreach ($result['event'] as $i => $val) {
				$passVal = $val;

				if(substr($val, 0,1) == "#"){
					//Replace in the dataset.
					$passVal = str_replace($val, $data[substr($val, 1)], $val);
				}
				$newResult[$i] = $passVal;
			}

			//Push to the array
			array_push($this->resultSet, $newResult);
		}
	}


//Evaluation Test Script
/**
	$rList = array(
		'1' => array(
					'name' => 'Rule Name',
					'conditions' => array(
						'1'=>array(
							'rule' => 'greaterthanorequal',
							'value'  => '49',
							'variable' => 'Age'
						),
						'2'=>array(
							'rule'=>'lessthan',
							'value'=>'100',
							'variable'=>'Age'
						)
					),
					'Success'=>array(
						'1' => array(
							'action' => 'display',
							'value'  => 'Success your age is good'
						)
					),
					'Failure'=>array(
						'1' => array(
							'action' => 'display',
							'value'  => 'Sorry your age is out of our range'
						)
					)
				)
	);

	$dSet1 = array(
		'Age' => 50
	);

	$dSet2 = array(
		'Age' => 10
	);
	
	$PTO = new TransactionRules($rList, $dSet1);
	$PTO->testRules();
	echo '<br>';
	$PTO = new TransactionRules($rList, $dSet2);
	$PTO->testRules();
	**/


?>