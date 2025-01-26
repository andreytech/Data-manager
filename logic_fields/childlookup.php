<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."list.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/administrator/components/com_datamanager/fields/input.php");


class ChildlookupField extends Field {
	private $fields_obj;
		
	function getType() {
		return 'child lookup field';
	}
	
	function getParams() {
		return array(
			'type' => 'child lookup field',
			'title' => 'Child lookup',
			'non_db_field' => 1,
			'db_field' => 0
		);
	}
	
	function get_field() {
		if (empty($this->params)) {
			return '';
		}
		$lookup_field_id = $this->params['param_lookup_field_id'];
		$query = " SELECT f.params, f.component_id, f.name FROM #__dm_fields f WHERE f.id='{$lookup_field_id}'";
		$this->db->setQuery( $query );
		$lookup_field = $this->db->loadObject();
		
		$component_path = JURI::current().'?option=com_datamanager&controller=data_management&component_id[]='.$lookup_field->component_id.
			'&__field_filter_'.$lookup_field->name.'='.$this->key_field_value;
		
		$ret_val = '<center><a href="'.$component_path.'"><img src="'.JURI::root().'includes/js/ThemeOffice/mainmenu.png"> </a> </center>'."\n";
		
		return $ret_val;
	}
	
	function get_value() {
		return true;
	}
	
	function loadFieldParams() {
		if ($this->field_params) {
			return true;
		}
		$this->field_params = new FieldParams($this->params);
				
		$tables_list_field = new ListField('param_lookup_field_id');
		
		$query = " SELECT f.id, f.title FROM #__dm_fields f WHERE type='lookup field'";
		$this->db->setQuery( $query );
		$lookup_fields = $this->db->loadObjectList();
		
		foreach($lookup_fields as $lookup_field) {
			$tables_list_field->add_item($lookup_field->id,$lookup_field->title);
		}
		
		$this->field_params->addField($tables_list_field,JText::_('Parent Lookup Field'));
	}

	function &getParamsFields() {
		$this->loadFieldParams();
		
		$retval = $this->field_params->getForm();
		
		return $retval;
	}

}

?>
