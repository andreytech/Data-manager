<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class Last_replyLogicField extends LogicField {
	function getField() {
		$value = $this->getParam('value');

		if (!$value) {
			return ' - ';
		}

		$key_field_value = $this->getParam('key_field_value');

		$db = DBMysql::getInstance();
		$query = "
			SELECT u.name
			FROM tickets_users u, tickets_posts p
			WHERE p.author_id = u.id
				AND p.ticket_id = '{$key_field_value}'
			ORDER BY p.date DESC
			LIMIT 1
		";
		$db->setQuery( $query );
		$result = $db->getResult();

		if ($result === false) {
			$this->errors[] = $db->getError();
			return false;
		}

		return $result;
	}

	function getValue() {
		$name = $this->getParam('name');
		$value = $this->getParam($name.'_value');
		return (int) $value;
	}
}
