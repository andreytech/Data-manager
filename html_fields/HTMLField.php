<?php

class HTMLField {
	protected $params = array();
	
	function addParams($params) {
		$this->params = array_merge($this->params, $params); 
	}
	
	function addParam($param, $value) {
		$this->params[$param] = $value;
	}
	
	function getHTMLParam($param, $logic_param = false) {
		if ($logic_param === false) {
			$logic_param = $param;
		}
		return ( isset($this->params[$logic_param])?' '.$param.'="'.$this->params[$logic_param].'" ':'');
	}
	
	function getParam($param) {
		return (isset($this->params[$param])?$this->params[$param]:false);
	}
	
}
