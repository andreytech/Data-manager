<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class ManagingLogicField extends LogicField {
	
	function getField() {
		$key_field_value = $this->getParam('key_field_value');
		$delete_link = $this->getParam('delete_link');
		$edit_link = $this->getParam('edit_link');
		
		$field = '';
		if ($edit_link) {
			$edit_link = str_replace('*', $key_field_value, $edit_link);
			$edit_link = $this->getURLString($edit_link);
			$field .= '<a href="'.$edit_link.'">Редактировать</a>'."\n";
		}
		
		if ($delete_link) {
			$delete_link = str_replace('*', $key_field_value, $delete_link);
			$delete_link = $this->getURLString($delete_link);
			$field .= ($field?'&nbsp;':'');
			$field .= '<a href="'.$delete_link.'">Удалить</a>'."\n";
		}
		return $field;
	}

	function getValue() {
		return true;
	}

}
