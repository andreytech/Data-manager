<?php

include_once(DM_PATH.DS."html_fields".DS."HTMLTextarea.php");
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class TextareaLogicField extends LogicField {
	function getField() {
		$value = $this->getParam('value');
		
		switch($this->params['mode']) {
			case 'list':
				if (strlen($value)>100) {
					$value = substr($value, 0, 100).' ...';
				}
				$field = $value;
				break;
			case 'single':
				$field_obj = new HTMLTextarea();
				$field_obj->addParams($this->params);
				$field = $field_obj->getField();
				break;
		}
		
		return $field;
	}
	
	function getValue() {
		$value = $this->getParam('value');
		if ($this->getParam('allow_html')) {
			return $value;
		}else {
			return htmlspecialchars($value, ENT_QUOTES); 
		}
	}
}
