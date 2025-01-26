<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class CreationDateLogicField extends LogicField {
	
	function getField() {
		$value = $this->getParam('value');
		
		switch($this->params['mode']) {
			case 'list':
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
				if (!$value) {
					$field = 'Текущая дата и время';
				}else {
					$field_obj = new HTMLInput();
					$field_obj->addParams($this->params);
					$field = $field_obj->getField();
				}
				break;
		}
		
		return $field;
	}
	
	function getValue() {
		$value = $this->getParam('value');
		if (!$value) {
			return date('Y-m-d H:i:s');
		}else {
			return htmlspecialchars($value, ENT_QUOTES);
		}
	}
}
