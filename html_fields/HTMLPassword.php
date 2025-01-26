<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLPassword extends HTMLField {
		
	function getField() {
		$field = '<input type="password" '; 
		$field .= $this->getHTMLParam('name');
		$field .= $this->getHTMLParam('class', 'css_class');
		$field .= ' value="" ';		
		$field .= ' /> '."\n";
		return $field;
	}
} 