<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class SalesReportLogicField extends LogicField {
	
	function getField() {

		$key_field_value = $this->getParam('key_field_value');

		$db = DBMysql::getInstance();
		$query = " SELECT DATE_FORMAT(`date`, '%e %M %Y') FROM `uchet_sales` WHERE client_id = '{$key_field_value}' ORDER BY date LIMIT 1";
		
		$db->setQuery( $query );
		$first = $db->getResult();

		$query = " SELECT DATE_FORMAT(`date`, '%e %M %Y') FROM `uchet_sales` WHERE client_id = '{$key_field_value}' ORDER BY date DESC LIMIT 1";
		
		$db->setQuery( $query );
		$last = $db->getResult();

		$field = $first.' - '.$last.' <br> <a href="/home/sales_report/'.$key_field_value.'">Отчет по продажам</a>'."\n";
		return $field;
	}

	function getValue() {
		return '';
		//return intval($value);
	}

}