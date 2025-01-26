<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

//include_once($_SERVER["DOCUMENT_ROOT"].DS."administrator".DS."components".DS."com_datamanager".DS."fields".DS."field.php");
include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."textarea.php");

class RadioField extends Field {
	private $items = array();
		
	function add_item($val, $title) {
		$this->items[$val] = $title;
	}
	
	function get_item($val) {
		return (isset($this->items[$val])?$this->items[$val]:0);
	}
	 
	function getType() {
		return 'radio field';
	}
	
	function getParams() {
		return array(
			'type' => 'radio field',
			'title' => 'Radio',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
		
	function get_field() {
		if (isset($this->params['param_items'])) {
			$items = explode("\n",$this->params['param_items']);
			foreach ($items as $item) {
				$item = trim($item);
				$this->add_item($item,$item);
			}
		}
		if($this->read_only) {
			if (isset($this->items[$this->value])) {
				return $this->items[$this->value];
			}
			return $this->value;
		}else {
			//$retval = '<select name="'.$this->name.'" id="'.$this->name.'" '.$this->properties.' class="inputbox">'."\n";
			foreach ($this->items as $val => $title) {
				$val = trim($val);
				$title = trim($title);
				$retval .= "<input type = 'Radio' name ='{$this->name}' value='{$val}' ".($this->value==$val?' checked ':'')." {$this->properties} >{$title}<br/>\n";
				//$retval .= ' <option value="'.$val.'" '.($this->value==$val?' checked ':'').'>'.$title."</option>\n";
			}
			//$retval .= '</select>'."\n";
			return $retval;
		}
	}
	
	function get_value() {
		return htmlspecialchars($this->value, ENT_QUOTES);
	}
		
	function loadFieldParams() {
		if ($this->field_params) {
			return true;
		}
		$this->field_params = new FieldParams($this->params);
		$this->field_params->addField(new TextareaField('param_items'),JText::_('Radio items'));
	}
	
	function &getParamsFields() {
		if (!$this->db) {
			return 'DB parameter not set';
		}
		$this->loadFieldParams();
		
		$retval = $this->field_params->getForm();
		
		return $retval;
	}

}

?>
