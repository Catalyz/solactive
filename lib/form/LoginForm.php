<?php

class LoginForm extends sfForm{
	public function configure()
	{
		$this->setWidgets(array(
		  'login'    => new sfWidgetFormInputText(array('label' => 'Identifiant')),
		  'password' => new sfWidgetFormInputPassword(array('label' => 'Mot de passe'))
		));

		$this->setValidators(array(
		  'login'      => new sfValidatorString(array('max_length' => 50, 'required' => true)),
		  'password'   => new sfValidatorString(array('max_length' => 50, 'required' => true)),
		));

		$this->widgetSchema->setNameFormat('login[%s]');

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);


		parent::configure();
	}}

?>