<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLPassword.php");
include_once(DM_PATH.DS."html_fields".DS."HTMLHidden.php");

class PasswordLogicField extends LogicField {

	function getField() {
		$value = $this->getParam('value');
		
		if ( $this->params['mode'] == 'single' ){
			$field_obj = new HTMLHidden();
			$field_obj->addParams($this->params);
			$field = $field_obj->getField();
			
			$this->params['name'] = 'new_pass';
			$field_obj = new HTMLPassword();
			$field_obj->addParams($this->params);
			$field .= $field_obj->getField();
			unset($field_obj);
			$field .= '<br />';

			$this->params['name'] = 'new_pass_confirm';
			$field_obj = new HTMLPassword();
			$field_obj->addParams($this->params);
			$field .= $field_obj->getField();

			if($this->getParam('passwords_doesnt_match')) {
				$field .= '<br /> Пароль и подтверждение не сопадают';
			}elseif($value) {
				$field .= '<br /> Оставить поле пустым чтобы остался старый пароль';
			}
		}else {
			return '';
		}
		
		return $field;
	}
	
	function getValue() {
		if(
			isset($_REQUEST['new_pass'])
			&& $_REQUEST['new_pass']
			&& isset($_REQUEST['new_pass_confirm'])
			&& $_REQUEST['new_pass_confirm']
			&& strcmp($_REQUEST['new_pass'], $_REQUEST['new_pass_confirm']) === 0
		) {
			$new_pass = htmlspecialchars($_REQUEST['new_pass'], ENT_QUOTES);
			$salt = substr(md5(time()), 0, 10);
			return md5($new_pass.$salt).':'.$salt;		
		}
		
		$value = $this->getParam('value');
		return $value;
	}
}
