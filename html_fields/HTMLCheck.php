<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLCheck extends HTMLField {
		
	function getField() {
		$value = $this->getParam('value');
		
		$field = '<input type="checkbox" value="1" '; 
		$field .= $this->getHTMLParam('name');
		$field .= $this->getHTMLParam('class', 'css_class');
		$field .= ($value?' checked ':'');
		$field .= ' /> '."\n";
		return $field;
	}
} 
