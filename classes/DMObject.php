<?php

class DMObject {
	protected $errors = array();

	function getErrors() {
		return $this->errors;
	}

	function getErrorsStr() {
		return implode(' ',$this->errors);
	}
	
}