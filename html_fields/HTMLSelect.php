<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLSelect extends HTMLField {
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
		$field = '<select ';
		$name = $this->getParam('name');
		if ($name) {
			$multiple = $this->getParam('multiple');
			$field .= ' name="'.$name.($multiple?'[]':'').'"';
		}

		$field .= $this->getHTMLParam('class', 'css_class');
		$field .= $this->getHTMLParam('size');
		$field .= $this->getHTMLParam('multiple');
		$field .= ' /> '."\n";
		
		$selected_value = $this->getParam('value');

		foreach ($this->items as $value => $item) {
			$is_selected = 0;
			if (is_array($selected_value) && in_array($value, $selected_value)) {
				$is_selected = 1;
			}elseif ($selected_value==$value) {
				$is_selected = 1;
			}
			$field .= ' <option value="'.$value.'" '.($is_selected?' selected ':'').'>'.$item."</option>\n";
		}
		$field .= '</select>'."\n";

		return $field;
	}
} 