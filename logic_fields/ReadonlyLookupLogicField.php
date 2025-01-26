<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class ReadonlyLookupLogicField extends LogicField {
	function getField() {
		$value = $this->getParam('value');

		if (!$value) {
			return ' - ';
		}

		$key_field = $this->getParam('key_field');
		$values_field = $this->getParam('values_field');
		$lookup_table = $this->getParam('lookup_table');

		$db = DBMysql::getInstance();
		$query = " SELECT `{$values_field}` FROM `{$lookup_table}` ";
		$query .= " WHERE `{$key_field}` = '{$value}' ";

		$db->setQuery( $query );
		$result = $db->getResult();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}

		return $result;
	}

	function getValue() {
		$name = $this->getParam('name');
		$value = $this->getParam($name.'_value');
		return (int) $value;
	}
}
