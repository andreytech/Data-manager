<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLInput.php");

class PriceSourcesLogicField extends LogicField {
	
	function getField() {
		$field = '';

		$product_id = $this->getParam('key_field_value');

		$db = DBMysql::getInstance();

		$query = "
			SELECT * FROM uchet_product_price_sources pps
			LEFT JOIN uchet_product_prices pp ON pps.id = pp.source_id AND product_id = '{$product_id}'
			WHERE 1
		";
		$db->setQuery( $query );
		$sources = $db->getObjects();
		foreach($sources as $source) {

			switch($this->params['mode']) {
				case 'list':
					if($source->price) {
						$field .= $source->title.': '.$source->price.' ; ';
					}
					break;
				case 'single':
					$field .= $source->title.'<br>';
					$field_obj = new HTMLInput();
					$field_obj->addParams(array(
						'name' => 'price_for_source['.$source->id.']'
						, 'value' => $source->price
					));
					$field .= $field_obj->getField().'<br>';
					break;
			}


		}

		return $field;
	}

	function getValue() {
		$db = DBMysql::getInstance();

		$product_id = $this->getParam('key_field_value');

		$query = "
			DELETE FROM uchet_product_prices
			WHERE product_id = '{$product_id}'
		";
		$db->setQuery( $query );
		if (!$db->query()) {
			$this->errors[] = $db->getError();
			return false;
		}

		foreach($_POST['price_for_source'] as $source_id => $price) {
			if(!$price) {
				continue;
			}

			$query = "
				INSERT INTO uchet_product_prices
				SET product_id = '{$product_id}', price = '{$price}', source_id = '{$source_id}'
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