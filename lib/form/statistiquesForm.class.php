<?php

class statistiquesForm extends sfForm {
    public function configure()
    {
        $choices = array();
        $choices['nombre'] = 'Nombre de coupons';
        $choices['transaction-value'] = 'Montant des coupons';
        $choices['transaction-count'] = 'Nombre de transactions';
        $choices['average-transaction-value'] = 'Montant moyen de la transactions';

    	$actors = CoreConnector::getAllUsersList(true);


        $this->setWidgets(array(
                'actor' => new sfWidgetFormChoice(array('label' => 'Acteur', 'choices' => $actors), array('class' => 'inp-styledselect_form_1')),
                'type' => new sfWidgetFormChoice(array('label' => 'Afficher sur la carte', 'choices' => $choices), array('class' => 'inp-styledselect_form_1')),
                'date' => new czWidgetFormDate(array('label' => 'Afficher les statistiques du')),
                'reference_date' => new czWidgetFormDate(array('label' => 'Comparer avec les statistiques du'))
                ));

        $this->setValidators(array(
                'date' => new sfValidatorDate(array('required' => false), array('invalid' => 'Cette date n\'est pas valide')),
                'reference_date' => new sfValidatorDate(array('required' => false), array('invalid' => 'Cette date n\'est pas valide')),
                'type' => new sfValidatorPass(array('required' => false)),
                'actor' => new sfValidatorInteger(array('required' => false))
                ));

        $this->setDefaults(array(
                'date' => time(),
                'type' => 'nombre'
                ));

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('stats[%s]');
        $decorator = new czFormFormatter($this->widgetSchema, $this->validatorSchema);
        $this->widgetSchema->addFormFormatter('custom', $decorator);
        $this->widgetSchema->setFormFormatterName('custom');

        parent::configure();
    }
}

?>