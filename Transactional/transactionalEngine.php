<?php


	/**
		Given Salary of $2000 a payperiod what is the Salary paycode for this pay period?
		Given an hourly rate of $20 and 40 hours of work what is the Regular paycode?
	**/



	class transactionalEngine{
		private $rules = array();
		private $dataSet = array();
		private $resultSet = array();
		private $startTime = 0;
		private $endTime = 0;

		/**
			@@Goal : Initialize the method with all the rules needed to process
		**/
		function __construct($Rules, &$Data)
		{
			$this->rules=$Rules;
			$this->dataSet=$Data;
		}

		/**
			@@Goal : Process the rules
		**/
		public function process()
		{
			//Track the time
			$this->startTime = microtime(true);

			//For each DataSet
			foreach ($this->dataSet as $d => $data) {
				$this->evaluateRuleSet($this->rules, $this->dataSet[$d]);
			}

			$this->endTime = microtime(true);
		}

		private function evaluateRuleSet($rules, &$data){
			//Evaluate each rule
			foreach ($rules as $i => $rule) {
				$this->evaluateRule($rule, $data);
			}	
		}

		/**
			@@Goal : Test the rule
		**/
		private function evaluateRule($rule, &$data)
		{

			//Determine the TYPE of rule we are evaluating
			switch (strtolower($rule['type'])) {
				case 'resultfromvariables':
					$this->evalResultFromVariableRule($rule, $data);
					break;
				case 'addtoresultset':
					$this->evalAddToResultSet($rule, $data);
					break;
				case 'conditionalresult':
					$this->evalConditionalResult($rule, $data);
					break;
			}

		}

		private function evalResultFromVariableRule($rule, &$data)
		{
			//If this returned variable doesn't exist create it, otherwise its already there ;)
			if(!array_key_exists($rule['returnedVariable'], $data))
				$data[$rule['returnedVariable']] = null;

			foreach ($rule['action'] as $i => $action) {
				//Evaluate the Action
				$result = $this->evaluateAction($action, $data);

				//echo $rule['returnedVariable'] . ' = ' . $data[$rule['returnedVariable']] . ' | Result = ' . $result .' <br/>';

				//Perform the Operation.
				switch(strtolower($rule['resultType'])){
					case 'plus':
						$data[$rule['returnedVariable']] += $result;
						break;
					case 'minus':
						$data[$rule['returnedVariable']] -= $result;
						break;
				}

				//echo $rule['returnedVariable'] . ' = ' . $data[$rule['returnedVariable']] . ' | Result = ' . $result .' <br/>';
			}
		}

		/**
			@@Evaluates the Action on this set of data...
			@@Returns the VAlue
		**/
		private function evaluateAction($action, &$data)
		{
			$var = "";
			//What kind of action is this?
			switch (strtolower($action['Action'])) {
				case 'multiply':
					$var = $this->multiplyAction($action, $data);
					break;
				case 'minus':
					$var = $this->minusAction($action, $data);
					break;
				case 'plus':
					$var = $this->plusAction($action, $data);
					break;
				case 'divide':
					$var = $this->plusAction($action, $data);
					break;
				default:
					$var = null;
					break;
			}

			return $var;
		}

		/**
			Evaluation Action section
		**/
		private function multiplyAction($action, $data)
		{
			return $data[$action['Numerator']] * $data[$action['Denomator']];
		}

		private function minusAction($action, $data)
		{
			return $data[$action['Numerator']] - $data[$action['Denomator']];
		}

		private function plusAction($action, $data)
		{
			return $data[$action['Numerator']] + $data[$action['Denomator']];
		}

		private function divideAction($action, $data)
		{
			return $data[$action['Numerator']] / $data[$action['Denomator']];
		}

		/**
			evaluate conditional results
		**/
		private function evalConditionalResult($rule, &$data)
		{
			$success = false;
			//Test each condition
			foreach ($rule['conditions'] as $i => $condition) {
				$success = $this->evaluateCondition($condition, $data);
			}

			//Process the results.
			if($success){
				$this->evaluateRuleSet($rule['result'], $data);
			}
		}

		/**
			Tests a single result.
			@@Returns a success or failure.
		**/
		private function evaluateCondition($condition, &$data)
		{
			$result = false;
			switch(strtolower($condition['action'])){
				case 'equal':
					$result = $this->equalAction($condition, $data);
					break;
				case 'lessthan':
					$result = $this->lessthenAction($condition, $data);
					break;
				case 'greaterthan' : 
					$result = $this->greaterthenAction($condition, $data);
					break;
			}

			return $result;
		}

		private function equalAction($condition, $data){
			return ($data[$condition['variable']] == $condition['value']) ? true : false;
		}
		private function lessthenAction($condition, $data){
			return ($data[$condition['variable']] < $condition['value']) ? true : false;
		}
		private function greaterthenAction($condition, $data){
			return ($data[$condition['variable']] > $condition['value']) ? true : false;
		}

		/**
			Add to result set area.
		**/
		private function evalAddToResultSet($rule, $data)
		{
			foreach ($rule['action'] as $i => $action) {
				$this->processResultSetAction($action, $data);
			}
		}

		private function processResultSetAction($action, $data)
		{
			$newResult = array();

			//For each resultset see if we need to replace any information.
			foreach ($action['transaction'] as $i => $val) {
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


		/**
			Getters and Setters...
		**/
		/**
			Return the dataset
		**/
		public function getData()
		{
			return $this->dataSet;
		}

		public function getResultSet(){
			return $this->resultSet;
		}

		public function getTime(){
			return ($this->endTime - $this->startTime);
		}
	}






?>

