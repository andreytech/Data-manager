<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLSelect.php");

class LookupLogicField extends LogicField {
	protected $items = array();
	
	function loadItems() {
//		if ($this->items) {
//			return true;
//		}
		$this->items = array();
		
		$value = $this->getParam('value');
		
		$add_no_answer = $this->getParam('add_no_answer');
		$mode = $this->getParam('mode');
		if ( ($mode != 'list' && $add_no_answer)
			|| ($mode == 'list' && !$value )
		) {
			$this->items[0] = '&nbsp;-&nbsp;';
		}
		
		$lookup_table = $this->getParam('lookup_table');
		$key_field = $this->getParam('key_field');
		$values_field = $this->getParam('values_field');

		if (!$lookup_table || !$key_field || !$values_field) {
			$name = $this->getParam('name');
			$this->errors[] = "Some lookup table parameters not set. Field '{$name}' ";
			return false;
		}

		$db = DBMysql::getInstance();
		$query = " SELECT `{$key_field}`, `{$values_field}` FROM `{$lookup_table}` ";
		
		if ($mode == 'list' && $value) {
			$query .= " WHERE `{$key_field}` = '{$value}' ";
		}
		
		$db->setQuery( $query );
		$result = $db->getArrays();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}
		if (!$result) {
			$this->items[0] = '&nbsp;-&nbsp;';
		}else {
			foreach ($result as $value) {
				$this->items[$value[$key_field]] = $value[$values_field];
			}
		}
		
		return true;
	}
	
	function getField() {
		$value = $this->getParam('value');
		$result = $this->loadItems();
		if ($result === false) {
			return false;
		}

		switch($this->params['mode']) {
			case 'list':
				$value = current($this->items);
				
				$is_link = $this->getParam('is_link');
				$link = $this->getParam('link');
				$key_field_value = $this->getParam('key_field_value');
				if ($is_link && $link && $key_field_value) {
					$link = str_replace('?', $key_field_value, $link);
					$link = $this->getURLString($link);
					$field = '<a href="'.$link.'">'.$value.'</a>'."\n";
				}else {
					$field = $value;
				}
				
				break;
			case 'single':
				$field_obj = new HTMLSelect();
				$field_obj->addParams($this->params);
				$field_obj->addItems($this->items);
				$field = $field_obj->getField();
				break;
		}
		return $field;
	}

	function getValue() {
		$value = $this->getParam('value');
		return htmlspecialchars($value, ENT_QUOTES);
		//return intval($value);
	}

}