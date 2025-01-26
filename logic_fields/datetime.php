<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."date.php");

class DatetimeField extends DateField {

	function __construct($name=''){
		parent::__construct($name);
	}
	
	function getType() {
		return 'datetime field';
	}
	
	function getParams() {
		return array(
			'type' => 'datetime field',
			'title' => 'Datetime',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
	
	function get_field() {
		if($this->read_only) {
			$this->value = $this->format_date($this->value, 'm/d/Y g:i a');
			return $this->value;
		}else {
			if (!$this->value || !intval($this->value)) {
				if (isset($this->params['param_is_current_date_default']) && $this->params['is_current_date_default']) {
					$this->value = date('Y-m-d g:i a');
				}else {
					$this->value = '';
				}
			}else {
				$this->value = $this->format_date($this->value, 'm/d/Y g:i a');
			}
			return $this->calendar($this->if_set($this->value,$this->value),$this->name,$this->name, '%m/%d/%Y %I:%M %p', 1);
		}
	}
	
	function get_value() {
		if (!empty($this->value)) {
			$this->value = $this->format_date($this->value, 'Y-m-d G:i:s');
		}
		return $this->value;
	}

}

?>
