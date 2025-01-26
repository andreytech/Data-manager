<?php

include_once DM_PATH.DS.'classes'.DS.'DMObject.php';

class LogicField extends DMObject {
	protected $params = array();
	
	function addParams($params) {
		$this->params = array_merge($this->params, $params); 
	}
	
	function setParam($param, $value) {
		$this->params[$param] = $value;
	}
	
	function getParam($param) {
		return (isset($this->params[$param])?$this->params[$param]:false);
	}

	function getURLString($link) {
		if (!$link) {
			return DM_BASE_URL;
		}
		if ($link[0] == '/') {
			$link = substr($link, 1);
		}
		return DM_BASE_URL.$link;
	}
	
}
