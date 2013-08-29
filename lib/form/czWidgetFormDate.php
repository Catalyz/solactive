<?php

class czWidgetFormDate extends sfWidgetFormJqueryDate {
	protected function configure($options = array(), $attributes = array()) {
		parent::configure($options, $attributes);

		$culture = 'fr';
		$dateWidget = new sfWidgetFormI18nDate(array('culture' => $culture,'empty_values'=> array('year' => '&nbsp;', 'month' => '&nbsp;', 'day' => '&nbsp;')));
		$this->setOption('date_widget',$dateWidget);
		$this->setOption('image','/images/forms/icon_calendar.jpg');
		$this->setOption('culture',$culture);

	}

	function getJavaScripts(){
		$result = parent::getJavaScripts();
		$result[] = '/js/jquery-ui-1.8.11.custom.min.js';
		$result[] = '/js/jquery.ui.datepicker-fr.js';
		return $result;
	}

	public function render($name, $value = null, $attributes = array(), $errors = array())
	{
		if ($value == '0000-00-00') {
			$value=null;
		}
		return parent::render($name, $value, $attributes, $errors);
	}
}

?>