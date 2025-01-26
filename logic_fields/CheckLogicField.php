<?php
include_once(DM_PATH.DS."html_fields".DS."HTMLCheck.php");
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class CheckLogicField extends LogicField {
	function getField() {
		$value = $this->getParam('value');
		
		switch($this->params['mode']) {
			case 'list':
				if ($value) {
					$field = 'Да';
				}else {
					$field = 'Нет';
				}
				break;
			case 'single':
				if (!isset($this->params['value']) && isset($this->params['enabled_by_default'])) {
					 $this->params['value'] = $this->params['enabled_by_default'];
				}
				
				$field_obj = new HTMLCheck();
				$field_obj->addParams($this->params);
				$field = $field_obj->getField();
				break;
		}
		
		return $field;
	}
	
	function getValue() {
		$value = $this->getParam('value');
		return ($value?1:0);
	}
}