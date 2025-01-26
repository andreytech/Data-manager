<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."dbtables.php");
include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."input.php");


class WysiwygField extends InputField {
	private $fields_obj;

	function getType() {
		return 'wysiwyg editor';
	}

	function getParams() {
		return array(
			'type' => 'wysiwyg editor',
			'title' => 'WYSIWYG',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
		
	function get_field() {
		if($this->read_only) {
			if (strlen($this->value)>100) {
				$this->value = substr($this->value, 0, 100).' ...';
			}
			
			if (!isset($this->params['param_item_link'])) {
				$is_item_link = 0;
			}else {
				$is_item_link = $this->params['param_item_link'];
			}
			
			if ($is_item_link) {
				$item_link = JRoute::_( 'index.php?option=com_datamanager&controller=data_management&component_id='.$this->component_id.'&task=edit&cid[]='. $this->key_field_value );
				$ret_val = '<a href="'.$item_link.'">'.$this->value.'</a>'."\n";
			}else {
				$ret_val = $this->value;
			}
			
			return $ret_val;
		}else {
			$editor =& JFactory::getEditor();

			$this->value = htmlspecialchars_decode($this->value, ENT_QUOTES);
			// parameters : areaname, content, width, height, cols, rows
			return $editor->display( $this->name,  $this->value, '550', '300', '60', '20', array('pagebreak', 'readmore') ) ;

		}
	}
	function get_value() {
		return htmlspecialchars($this->value, ENT_QUOTES);
	}
}

?>
