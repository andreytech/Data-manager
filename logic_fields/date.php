<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class DateField extends Field {

	function __construct($name=''){
		JHTML::_('behavior.calendar');
		parent::__construct($name);
	}
	
	function getType() {
		return 'date input box';
	}

	function getParams() {
		return array(
			'type' => 'date input box',
			'title' => 'Date',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
	
	function get_field() {
		if($this->read_only) {
			$this->value = $this->format_date($this->value, 'm/d/Y');
			return $this->value;
		}else {
			if (!$this->value || !intval($this->value)) {
				if (isset($this->params['param_is_current_date_default']) && $this->params['param_is_current_date_default']) {
					$this->value = date('m/d/Y');
				}else {
					$this->value = '';
				}
			}else {
				$this->value = $this->format_date($this->value, 'm/d/Y');
			}
			return $this->calendar($this->if_set($this->value,$this->value),$this->name,$this->name, '%m/%d/%Y');
		}
	}
	function get_value() {
		if (!empty($this->value)) {
			$this->value = $this->format_date($this->value, 'Y-m-d');
		}
		return $this->value;
	}

	function loadFieldParams() {
		if ($this->field_params) {
			return true;
		}
		$this->field_params = new FieldParams($this->params);
		$this->field_params->addField(new CheckField('param_is_current_date_default'),JText::_('Current date is default'));
	}

	function &getParamsFields() {
		if (!$this->db) {
			return 'DB parameter not set';
		}
		$this->loadFieldParams();

		$retval = $this->field_params->getForm();

		return $retval;
	}

	function format_date($date, $format='m/d/Y') {
		if ($date == '0000-00-00' || !$date) {
			return '';
		}
		if (($timestamp = strtotime($date)) === false) {
			$msg = JText::_("Invalid date value")." '{$date}' ".JText::_('in')." ".__FUNCTION__.", ".JText::_('class')." ".__CLASS__;
			JError::raiseWarning('500', $msg);
		} else {
			$date = date($format, $timestamp);
		}

		return $date;
	}

	function calendar($value, $name, $id, $format = '%Y-%m-%d', $is_time = 0, $attribs = null) {
		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString( $attribs );
		}
		
		$attribs = ' class="inputbox" ';

		$document =& JFactory::getDocument();
		$document->addScriptDeclaration('window.addEvent(\'domready\', function() {Calendar.setup({
        inputField     :    "'.$id.'",     // id of the input field
        ifFormat       :    "'.$format.'",      // format of the input field
        button         :    "'.$id.'_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        '.($is_time?'
        showsTime      :    true,
        ':'').'
        singleClick    :    true
    });});');

		return '<input type="text" name="'.$name.'" id="'.$id.'" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" '.$attribs.' />'."\n".
                 '<img class="calendar" src="'.JURI::root(true).'/templates/system/images/calendar.png" alt="calendar" id="'.$id.'_img" />'."\n";
	}

}

?>
