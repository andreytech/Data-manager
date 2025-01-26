<?php

define('DM_PATH', dirname(__FILE__));
define( 'DS', DIRECTORY_SEPARATOR );

include_once DM_PATH.DS.'params.php';
include_once DM_PATH.DS.'classes'.DS.'DBMysql.php';
include_once DM_PATH.DS.'classes'.DS.'DBTable.php';
include_once DM_PATH.DS.'classes'.DS.'DMRenderer.php';

class DataManager extends DBTable{
	private $mode = '';
	private $page = 1;
	private $items_per_page = 0;
	private $key_value = 0;
	private $titles = array();
	private $rendered_fields = array();
	private $fields = array();
	private $table_fields_params = array();
	private $nondb_fields_params = array();
	private $fields_params = array();

	function __construct($table) {
		parent::__construct($table);
		
		$db = DBMysql::getInstance();
		$db->setQuery( 'SET NAMES "utf8"' );
		$db->query();
	}

	function addFieldParam($param, $value) {
		$this->fields_params[$param] = $value;
	}

    protected function getKeyValue() {
        return $this->key_value ? $this->key_value : null;
    }

    public function delete() {
        if (!$this->loadFields()) {
            return false;
        }

        if ($this->key_field) {
            $key_value = $this->getKeyValue();
            if ($key_value) {
                $this->addWhereCondition(" `{$this->key_field}` = '$key_value' ");
            } else {
                return false;
            }
        }

        return parent::delete();
    }
	
	function getMode() {
		return ($this->mode?$this->mode:'list');
	}
	
	function getTableFieldsParams() {
		
	}
	
	function getTitles() {
		if( !$this->loadData() ) {
			return false;
		}
		return $this->titles;
	}
	
	function getFields() {
		if( !$this->loadData() ) {
			return false;
		}
		return $this->rendered_fields;
	}
	
	function loadData() {
		if ($this->rendered_fields) {
			return true;
		}
		
		if( !$this->loadFields() ) {
			return false;
		}

		if ($this->key_value && $this->key_field) {
			$this->addWhereCondition(" `{$this->key_field}` = '$this->key_value' ");
		}

		$this->limit = $this->items_per_page;
		$this->limit_start = ($this->page - 1) * $this->items_per_page;
		
		$mode = $this->getMode();
		if ( !($mode == 'single' && !$this->key_value)
			&& parent::loadData() === false ) {
			return false;
		}
		if ($this->mode == 'list' && !$this->data) {
			return true;
		}
		
		$renderer = new DMRenderer();
		$renderer->setFields($this->fields);
		$renderer->setData($this->data);
		$aditional_params = array (
			'key_field' => $this->key_field
			, 'mode' => $mode
		);
		$renderer->addParams($aditional_params);
		$renderer->addParams($this->fields_params);
		$this->rendered_fields = $renderer->getFields();
		
		if ($this->rendered_fields === false) {
			$this->errors = $renderer->getErrors();
			return false;
		}
		return true;
		
	}
	
	function loadFields() {
		if ($this->fields) {
			return true;
		}
		
		if (!$this->table) {
			$this->errors[] = 'Table not set';
			return false;
		}
		
		if (!$this->mode) {
			$this->mode = 'list';
		}
		
		$table_file_path = DM_PATH.DS.'tables'.DS.$this->table.'.php';
		if (! file_exists($table_file_path) ) {
			$this->errors[] = 'File '.$this->table.'.php not found in tables folder';
			return false;
			
		}
		include $table_file_path;
		
		if (!isset($fields)) {
			$this->errors[] = '"fields" array not found in '.$this->table.'.php';
			return false;
		}
		$this->fields = $fields;
		
		foreach($this->fields as $key => $field) {
			if (isset($field[$this->mode.'_mode']) && !$field[$this->mode.'_mode']) {
				unset($this->fields[$key]);
				continue;
			}
			if (!isset($field['non_db']) || !$field['non_db']) {
				$this->table_fields[] = $field['name'];
				$this->table_fields_params[] = $field;
			}else {
				$this->nondb_fields_params[] = $field;
			}
			if (!isset($field['title'])) {
				$field['title'] = $field['name'];
			}
			$this->titles[$field['name']] = $field['title'];
			if ($field['type'] == 'key') {
				$this->key_field = $field['name'];
			}
		}
		
		return true;
	}
	
	function save($data) {
		$this->setMode('save');
		
		if( !$this->loadFields() ) {
			return false;
		}

		$update_fields_params = array();
		foreach($this->fields as $field) {
			if ($field['type'] != 'key') {
				$field = array_merge($field, $this->fields_params);
				$update_fields_params[] = $field;
			}
		}

		$renderer = new DMRenderer();
		$renderer->setFields($update_fields_params);
		$renderer->setData($data);
		$mode = $this->getMode();
		$aditional_params = array (
			'key_field' => $this->key_field
			, 'mode' => $mode
		);
		$renderer->addParams($aditional_params);
		$data = $renderer->getFieldsData();
		
		if ($data === false) {
			$this->errors = $renderer->getErrors();
			return false;
		}
		
		$this->key_value = (isset($data[$this->key_field])?$data[$this->key_field]:0);
		if ($this->key_field && $this->key_value) {
			$this->addWhereCondition(" `{$this->key_field}` = '$this->key_value' ");
			$result = parent::update($data);
		}else {
			$result = parent::insert($data);

			$db = DBMysql::getInstance();
			$this->key_value = $db->insertid();
		}

		if(!$result) {
			return $result;
		}

		$renderer->afterSaveEvent($this->key_value);

		return $result;
	}

	function setItemsPerPage($items_per_page) {
		$this->items_per_page = $items_per_page;
	}
	
	function setKeyValue($key_value) {
		$this->key_value = $key_value;
	}
	
	function setMode($mode) {
		$this->mode = $mode;
	}
	
	function setPage($page) {
		$this->page = $page;
	}
	
}