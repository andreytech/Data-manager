<?php

include_once DM_PATH.DS.'classes'.DS.'DMObject.php';

class DBTable extends DMObject{
	protected $table = '';
	protected $ordering_field = '';
	protected	$is_ordering_asc = 1;
	protected $limit_start = 0;
	protected $limit = 0;
	protected $table_fields = array();
	protected $where_conditions = array();
	protected $key_field = '';
	protected $data = array();
		
	function __construct($table) {
		$this->table = $table;
	}
	
	function addWhereCondition($where_condition) {
		$this->where_conditions[] = $where_condition;
	}
	
	function delete() {
		$db = DBMysql::getInstance();
		
		$where_str = $this->getWhereStr();
		
		$query = "DELETE FROM `{$this->table}` WHERE {$where_str} ";
		$db->setQuery( $query );
		return $db->query();
	}
	
	function loadData() {
		$db = DBMysql::getInstance();
		$fields_str = '`'.implode('`,`',$this->table_fields).'`';
		$query = "
			SELECT {$fields_str}
			FROM `{$this->table}`
		";
		if ($this->where_conditions) {
			$query .= ' WHERE '.$this->getWhereStr();
		}
		if ($this->ordering_field) {
			$query .= " ORDER BY `{$this->ordering_field}` ";
			$query .= ($this->is_ordering_asc?'':' DESC ');
		}
		if ($this->limit) {
			$query .= " LIMIT {$this->limit_start}, {$this->limit} ";
		}
		
		$db->setQuery( $query );
		$result = $db->getArrays();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}

		$this->data = $result;
		
		return true;
	}

	function getFieldValue($field) {
		return (isset($this->data[0][$field])?$this->data[0][$field]:false);
	}
	
	function getUpdateFields() {
		$update_fields = array();
		foreach ($this->table_fields as $field) {
			if ($field != $this->key_field) {
				$update_fields[] = $field;
			}
		}
		return $update_fields;
	}
	
	function getWhereStr() {
		if ($this->where_conditions) {
			return implode(' AND ', $this->where_conditions);
		}
		return '';
	}
	
	function insert($data) {
		$db = DBMysql::getInstance();
		
		$update_fields = $this->getUpdateFields();
		foreach($update_fields as $key => $field) {
			if (isset($data[$field])) {
				$values[] = $data[$field];
			}else {
				unset($update_fields[$key]);
			}
		}
		$fields_str = '`'.implode('`,`',$update_fields).'`';
		$values_str = "'".implode("', '", $values)."'";
		
		$query = "INSERT INTO `{$this->table}`( {$fields_str} ) VALUES ( {$values_str} )";

		$db->setQuery( $query );
		return $db->query();
	}
	
	function setOrderingField($ordering_field, $is_ordering_asc = 1) {
		$this->ordering_field = $ordering_field;
		$this->is_ordering_asc = $is_ordering_asc;
	}
	
	function update($data) {
		$db = DBMysql::getInstance();
		
		$update_fields = $this->getUpdateFields();
		
		$params = array();
		foreach($update_fields as $field) {
			if (isset($data[$field])) {
				$params[] = " `{$field}` = '{$data[$field]}' ";
			}
		}
		$params_str = implode(', ', $params);
		
		$where_str = $this->getWhereStr();
		
		$query = "UPDATE `{$this->table}` SET {$params_str} WHERE {$where_str} ";
		$db->setQuery( $query );
		return $db->query();		
	}
	
/*	
	function isTableExists() {
		$db = DBMysql::getInstance();
		$query = "show tables like '{$this->table}'";
		$db->setQuery( $query );
		$is_exist = $db->getResult();
		$is_exist = !empty($is_exist);
		return $is_exist;
	}
	
	function getFields() {
		$db = DBMysql::getInstance();
		$query = 'SHOW FIELDS FROM ' . $this->table;
		$db->setQuery( $query );
		$fields = $db->getObjects();
		foreach($fields as $key => $field) {
			$fields[$key] = $field->Field;
		}
		return $fields;
	}
*/
}