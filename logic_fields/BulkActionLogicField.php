<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLCheck.php");

class BulkActionLogicField extends LogicField {
		
	function getField() {
		$key_field_value = $this->getParam('key_field_value');
		$field = "
		<input type='checkbox' name='ids[]' value='{$key_field_value}' />
		";

		return $field."\n";
	}

	function getValue() {
		return 0;
	}
}
