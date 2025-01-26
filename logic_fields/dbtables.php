<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_datamanager".DS."fields".DS."list.php");

class DbtablesField extends ListField {
	private $fields_obj;
	protected $read_only = 0;
	protected $is_core = 1;
		
	function getType() {
		return 'db tables field';
	}

	function getParams() {
		return array(
			'type' => 'db tables field',
			'title' => 'Db tables',
			'non_db_field' => 0,
			'db_field' => 0
		);
	}	
	
	function get_field() {
		$tables_list = $this->db->getTableList();
		foreach ($tables_list as $table) {
			$this->add_item($table,$table);
		}
		return parent::get_field();
	}
}

?>
