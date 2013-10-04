<?php

class exportForm extends sfForm {
    public function configure()
    {
        $sfUser = sfContext::getInstance()->getUser();

        $widgets = array(
            'from' => new czWidgetFormDate(array('label' => 'Entre le')),
            'to' => new czWidgetFormDate(array('label' => 'Et le'))
            );

        $validators = array(
            'from' => new sfValidatorDate(array('required' => false), array('invalid' => 'La date est invalide')),
            'to' => new sfValidatorDate(array('required' => false), array('invalid' => 'La date est invalide')),
            'operator' => new czValidatorActor(array('required' => false))
            );

        $defaults = array(
            'from' => strtotime('first day of this month'),
            'to' => time()
            );

		if ($sfUser->hasCredential('root')) {
            $widgets['operator'] = new sfWidgetFormInputText(array('label' => 'Agence'), array('class' => 'inp-form'));
            $defaults['operator'] = '';
		} else {
            $widgets['operator'] = new sfWidgetFormInputHidden(array('label' => false));
            $defaults['operator'] = preg_replace('/[^0-9]/', '', $sfUser->getAttribute('operator.phone'));
        }
    	$validators['operator'] = new czValidatorActor(array('required' => false));

        $widgets['actor'] = new sfWidgetFormInputText(array('label' => 'Adhérent'), array('class' => 'inp-form'));
        $validators['actor'] = new czValidatorActor(array('required' => false));
        $defaults['actor'] = '';

        $this->setValidators($validators);
        $this->setWidgets($widgets);
        $this->setDefaults($defaults);

        //region validate Dates
        $today = date('Y-m-d');
        $this->validatorSchema->setPostValidator(
            // new sfValidatorAnd(array(
            new sfValidatorSchemaCompare('from', '<', 'to',
                array('throw_global_error' => true),
                array('invalid' => 'La seconde date doit etre avant la premiere<br />')
                )
            // ,
            // new sfValidatorSchemaCompare('start_date', '<', $today,
            // array('throw_global_error' => true),
            // array('invalid' => 'The start date ("%left_field%") cannot be earlier than today\'s date: ('.$today.')<br />')
            // ),
            // new sfValidatorSchemaCompare('end_date', '>', $today,
            // array('throw_global_error' => true),
            // array('invalid' => 'The end date ("%left_field%") cannot be before today\'s date ("%right_field%")<br />')
            // )
            // )
            // )
            );
        //endregion

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('export[%s]');
        $decorator = new czFormFormatter($this->widgetSchema, $this->validatorSchema);
        $this->widgetSchema->addFormFormatter('custom', $decorator);
        $this->widgetSchema->setFormFormatterName('custom');

        parent::configure();
    }
}

?>