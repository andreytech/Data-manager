<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class OrderingLogicField extends LogicField {
	function getField() {
		$value = (int) $this->getParam('value');
		
		switch($this->params['mode']) {
			case 'list':
				$field = $value;
				break;
			case 'single':
				$field_obj = new HTMLInput();
				$field_obj->addParams($this->params);
				$field = $field_obj->getField();
				break;
		}
		
		return $field;
	}
	
	function getValue() {
		$value = (int) $this->getParam('value');
		$name = $this->getParam('name');
		$table = $this->getParam('table');

		if (!$table) {
			return 1;
		}
		
		if (!$value) {
			$db = DBMysql::getInstance();
			$query = "SELECT MAX(`{$name}`) + 1 FROM `{$table}` ";
			$db->setQuery( $query );
			$value = $db->getResult();
			if (!$value) {
				return 1;
			}
		}
		return $value;
	}
}
