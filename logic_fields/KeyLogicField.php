<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLHidden.php");

class KeyLogicField extends LogicField {
		
	function getField() {
		$field_obj = new HTMLHidden();
		$field_obj->addParams($this->params);
		$field = $field_obj->getField();

		$value = $this->getParam('value');
		return $field."\n".$value;
	}
}

?>
