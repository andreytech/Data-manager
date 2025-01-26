<?php

include_once dirname(__FILE__).DS.'HTMLField.php';

class HTMLHidden extends HTMLField {
		
	function getField() {
		$field = '<input type="hidden" '; 
		$field .= $this->getHTMLParam('name');
		$field .= $this->getHTMLParam('value');
		$field .= ' /> '."\n";
		return $field;
	}
} 