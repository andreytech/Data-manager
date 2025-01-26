<?php
include_once(DM_PATH.DS."logic_fields".DS."LogicField.php");

class WysiwygLogicField extends LogicField {
	function getField() {
		$value = (string) $this->getParam('value');
		$name = (string) $this->getParam('name');
		
		switch($this->params['mode']) {
			case 'list':
				if (strlen($value)>100) {
					$value = substr($value, 0, 100).' ...';
				}
				$field = $value;
				break;
			case 'single':
				$this->params['value'] = htmlspecialchars_decode($value, ENT_QUOTES);
				$this->params['rows'] = 10;
				$this->params['cols'] = 50;
				$this->params['id'] = $name;
				$field_obj = new HTMLTextarea();
				$field_obj->addParams($this->params);
				$field = $field_obj->getField();

				ob_start();
?>
			
<script type="text/javascript" src="/libraries/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/libraries/ckeditor/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
var editor = CKEDITOR.replace('<?php echo $name; ?>',
	{
		skin : 'v2'
//			, extraPlugins : 'autogrow'

	});

CKFinder.setupCKEditor( editor, '/libraries/ckeditor/ckfinder/' ) ;

//AjexFileManager.init({
//	returnTo: 'ckeditor',
//	editor: ckeditor
//});

</script>
<?php
				$field .= ob_get_clean();
				break;
		}
		
		return $field;
	}
	
	function getValue() {
		$value = $this->getParam('value');
		return htmlspecialchars($value, ENT_QUOTES);
	}
}
