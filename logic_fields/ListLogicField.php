<?php
include_once(DM_PATH.DS."html_fields".DS."HTMLSelect.php");
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class ListLogicField extends LogicField {
	
	function getField() {
		$value = $this->getParam('value');

		switch($this->params['mode']) {
			case 'list':
				$field = $value;
				if (isset($this->params['items'][$value])	) {
					$field = $this->params['items'][$value];
				}
				break;
			case 'single':
				$this->params['size'] = 4;
				$field_obj = new HTMLSelect();
				$field_obj->addParams($this->params);
				$field_obj->addItems($this->params['items']);
				$field = $field_obj->getField();
				break;
		}
		return $field;
	}

	function getValue() {
		$value = $this->getParam('value');
		return htmlspecialchars($value, ENT_QUOTES);
	}

}