<?php

class ticketDetailsForm extends sfForm{
	public function configure()
	{

		$this->setWidgets(array(
		  'date'    => new czWidgetFormDate(array('label' => 'Le')),
		  'operator'    => new sfWidgetFormInputText(array('label' => 'Opérateur'),array('class'=>'inp-form'))
		));

		$this->setValidators(array(
		  'date'      => new sfValidatorDate(array('required' => false),array('invalid'=>'La date est invalide')),
		  'operator'      => new czValidatorActor(array('required'=>false))
		));

		$this->setDefaults(array(
		  'date'      => time(),
		  'operator'      => ''
		));

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
		$this->widgetSchema->setNameFormat('export_tickets[%s]');
		$decorator = new czFormFormatter($this->widgetSchema, $this->validatorSchema);
		$this->widgetSchema->addFormFormatter('custom', $decorator);
		$this->widgetSchema->setFormFormatterName('custom');

		parent::configure();
	}
}

?>