<?php

class trackingActorForm extends sfForm{
	public function configure()
	{
		$this->setWidgets(array(
		  'login'    => new sfWidgetFormInputText(array('label' => 'Identifiant'),array('class'=>'inp-form'))
		));

		$this->setValidators(array(
			'login'      => new czValidatorActor(array('required'=>TRUE))
		));

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		$this->widgetSchema->setNameFormat('trackingActor[%s]');

		$decorator = new czFormFormatter($this->widgetSchema, $this->validatorSchema);
		$this->widgetSchema->addFormFormatter('custom', $decorator);
		$this->widgetSchema->setFormFormatterName('custom');

		parent::configure();
	}
}

?>