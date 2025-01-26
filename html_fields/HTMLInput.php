<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLInput extends HTMLField {
		
	function getField() {
		$field = '<input type="text" '; 
		$field .= $this->getHTMLParam('name');
		$field .= $this->getHTMLParam('value');
		$field .= $this->getHTMLParam('class', 'css_class');
		$field .= ' /> '."\n";
		return $field;
	}
} 