<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class FileField extends ListField {

	function getType() {
		return 'file field';
	}
	
	function getParams() {
		return array(
			'type' => 'file field',
			'title' => 'File',
			'non_db_field' => 0,
			'db_field' => 1
		);
	}
		
	function get_field() {
		if($this->read_only) {
			if ($this->value) {
				$html = '<center><img src="http://'.$_SERVER["SERVER_NAME"].'/administrator/images/tick.png">';
				if (isset($this->params['param_show_filename']) && $this->params['param_show_filename']) {
					$html .= '( '.$this->value.' )';
				}
				$html .= '</center>'."\n";
				return $html;
			}else {
				return '<center><img src="http://'.$_SERVER["SERVER_NAME"].'/administrator/images/publish_x.png"></center>'."\n";
			}
		}else {
			$path = '';
			if (isset($this->params['param_path'])) {
				$path = $this->params['param_path'];
			}
			
			if ($path && is_dir($path)) {
				$this->add_item('', JText::_('No file'));
				$dir_handle = opendir($path);
				while ($file = readdir($dir_handle)) {
					if($file == "." || $file == ".." || $file == "index.html" ) continue;
					//$fieldname = current(explode('.',$file));
					//include_once($path.$file);
					$this->add_item($file, $file);
				}
				closedir($dir_handle);
				$ret_val = parent::get_field();
			}else {
				$ret_val .= JText::_('Wrong directory');
			}
			return $ret_val;
		}
	}

	function get_value() {
		return htmlspecialchars($this->value, ENT_QUOTES);
	}

	function loadFieldParams() {
		if ($this->field_params) {
			return true;
		}
		$this->field_params = new FieldParams($this->params);
		$path_field = new InputField('param_path');
		$path_field->set_value('../images/stories/');
		$this->field_params->addField($path_field,JText::_('Path to files folder'));
		$this->field_params->addField(new CheckField('param_show_filename'),JText::_('Show filename'));
	}

	function &getParamsFields() {
		if (!$this->db) {
			return 'DB parameter not set';
		}
		$this->loadFieldParams();

		$retval = $this->field_params->getForm();

		return $retval;
	}

}

?>
