<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLCheckboxList extends HTMLField {
	protected $items = array();

	function addItem($item, $value = false) {
		if ($value === false) {
			$this->items[] = $item;
		}else {
			$this->items[$value] = $item;
		}
	}

	function addItems($items) {
		$this->items = $this->items + $items;
	}

	function getField() {
		$name = $this->getParam('name');

		//$class = $this->getHTMLParam('class', 'css_class');

		$selected_value = $this->getParam('value');

		$field = '<div style="overflow-y: scroll; height:200px;">';
		foreach ($this->items as $value => $item) {
			$is_checked = 0;
			if (is_array($selected_value) && in_array($value, $selected_value)) {
				$is_checked = 1;
			}elseif ($selected_value==$value) {
				$is_checked = 1;
			}

			$field .= '
	<input type="checkbox" value="'.$value.'" name="'.$name.'[]" id="'.$name.'-'.$value.'" '.($is_checked?' checked ':'').'>
	<label for="'.$name.'-'.$value.'">'.$item.'</label>
	<br>
			';
		}
		$field .= "</div>\n";

		return $field;
	}
}