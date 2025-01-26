<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLCheckboxList.php");

class ManyToManyLogicField extends LogicField {
	private $items = array();
	//private $selected_values = array();

	function loadItems() {
		
		$key_field_value = $this->getParam('key_field_value');
		$many_to_many_table = $this->getParam('many_to_many_table');
		$link_field = $this->getParam('link_field');
		$lookup_field = $this->getParam('lookup_field');
		$lookup_table = $this->getParam('lookup_table');
		$lookup_table_id_field = $this->getParam('lookup_table_id_field');
		$lookup_table_title_field = $this->getParam('lookup_table_title_field');
		$lookup_table_enabled_field = $this->getParam('lookup_table_enabled_field');

		$db = DBMysql::getInstance();
		$query = "
			SELECT lt.`{$lookup_table_id_field}` AS lookup_table_id_field
				, lt.`{$lookup_table_title_field}` AS lookup_table_title_field
		".
			($lookup_table_enabled_field?
				", lt.`{$lookup_table_enabled_field}` AS lookup_table_enabled_field"
			:
				''
			)
		."
				, mtmt.`{$lookup_field}` AS lookup_field
			FROM `{$lookup_table}` lt
				LEFT JOIN `{$many_to_many_table}` mtmt
					ON lt.`{$lookup_table_id_field}` = mtmt.`{$lookup_field}`
					AND mtmt.`{$link_field}` = '{$key_field_value}'
		";

		$db->setQuery( $query );
		$result = $db->getArrays();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}

		$this->params['value'] = array();
		foreach($result as $item) {
			if(!isset($item['lookup_table_enabled_field'])) {
				$item['lookup_table_enabled_field'] = 0;
			}
			if (
				!(
					$lookup_table_enabled_field
					&& (
						$item['lookup_table_enabled_field']
						|| $item['lookup_field']
					)
				)
			) {

				continue;
			}
			if ($item['lookup_field']) {
				$this->params['value'][] = $item['lookup_field'];
			}
			$this->items[$item['lookup_table_id_field']] = $item['lookup_table_title_field'];
		}
		return true;
	}

	function getField() {

		switch($this->params['mode']) {
			case 'list':
				break;
			case 'single':
				if (!$this->loadItems()) {
					return false;
				}

				$field_obj = new HTMLCheckboxList();
				$field_obj->addItems($this->items);
				$field_obj->addParams($this->params);
				$field = $field_obj->getField();
				break;
		}

		return $field;
	}

	function getValue() {
		return true;
	}

	function afterSave() {
		$name = $this->getParam('name');

		$key_field_value = $this->getParam('key_field_value');
		$many_to_many_table = $this->getParam('many_to_many_table');
		$link_field = $this->getParam('link_field');
		$lookup_field = $this->getParam('lookup_field');

		$db = DBMysql::getInstance();
		$query = "
			SELECT `{$lookup_field}` AS lookup_field
			FROM `{$many_to_many_table}`
			WHERE `{$link_field}` = '{$key_field_value}'
		";

		$db->setQuery( $query );
		$result = $db->getArrays();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}

		$values = array();
		if (isset($_POST[$name])) {
			$values = $_POST[$name];
		}

		$delete_items = array();
		foreach($result as $item) {
			$key = array_search($item['lookup_field'], $values);
			if ($key === false) {
				$delete_items[] = $item['lookup_field'];
			}else {
				unset($values[$key]);
			}
		}

		/// deleting
		if ($delete_items) {
			$delete_str = implode(', ', $delete_items);
			$query = "
				DELETE FROM `{$many_to_many_table}`
				WHERE `{$link_field}` = '{$key_field_value}'
					AND `{$lookup_field}` IN ({$delete_str})
			";
			$db->setQuery( $query );
			if (!$db->query()) {
				$this->errors[] = $db->getError();
				return false;
			}
		}

		// inserting
		if ($values) {
			$insert_params = array();
			foreach($values as $value) {
				$insert_params[] = "('{$key_field_value}', '{$value}')";
			}
			$insert_str = implode(', ', $insert_params);
			$query = "
				INSERT INTO `{$many_to_many_table}`
				( `{$link_field}`, `{$lookup_field}` )
				VALUES {$insert_str}
			";

			$db->setQuery( $query );
			if (!$db->query()) {
				$this->errors[] = $db->getError();
				return false;
			}
		}

		return true;
	}
}
