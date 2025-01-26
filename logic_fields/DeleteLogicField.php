<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class DeleteLogicField extends LogicField {
	
	function getField() {
		$key_field_value = $this->getParam('key_field_value');
		$link = $this->getParam('link');
		$link = str_replace('?', $key_field_value, $link);
		$link = $this->getURLString($link);
		$field = '<a href="'.$link.'">Delete</a>'."\n";
		return $field;
	}

	function getValue() {
		return true;
	}

}