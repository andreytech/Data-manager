<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class HiddenLogicField extends LogicField {
		
	function getField() {
		$field_obj = new HTMLHidden();
		$field_obj->addParams($this->params);
		$field = $field_obj->getField();

		return $field;
	}
	
	function getValue() {
		$value = $this->getParam('value');
		return $value;
	}	
	
}

?>
