<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLTextarea extends HTMLField {
		
	function getField() {
		$field = '<textarea '; 
		$field .= $this->getHTMLParam('name');
		$field .= $this->getHTMLParam('class', 'css_class');
		$field .= $this->getHTMLParam('rows');
		$field .= $this->getHTMLParam('cols');
		$field .= ' >';
		$field .= $this->getParam('value');
		$field .= '</textarea>'."\n";
		
		return $field;
	}
} 