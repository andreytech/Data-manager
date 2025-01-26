<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OrderingField extends Field {

	function getType() {
		return 'ordering';
	}
	
	function getParams() {
		return array(
			'type' => 'ordering',
			'title' => 'Ordering',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
	
	function get_field($logic=null) {
		if($this->read_only) {
			$page = $logic->getPageNav();
			ob_start();
			
?>
<span><?php echo $page->orderUpIcon( $logic->current_total_row, !$logic->is_first_condition_row, 'orderup', JText::_('Move Up'), 1 ); ?></span>
<span><?php echo $page->orderDownIcon( $logic->current_total_row, $logic->rows_count, !$logic->is_last_condition_row, 'orderdown', JText::_('Move Down'), 1 ); 
//echo $logic->rows_count;
?></span>
<input
	type="text" name="<?php echo $this->name; ?>[]" size="5"
	value="<?php echo $this->value; ?>" 
	class="text_area" style="text-align: center" />
<?php

			$field = ob_get_clean();
			return $field;
		}else {
			return '<input type="text" class="inputbox" name="'.$this->name.'" '.
			$this->if_set($this->value,' value="'.$this->value.'" ').
			$this->properties." />";
		}
	}
	
	function get_value() {
		return intval($this->value);
	}
}

?>