<?php

/**
 * TicketTrackingEntry form base class.
 *
 * @method TicketTrackingEntry getObject() Returns the current form's model object
 *
 * @package    tracking.sol-violette.info
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTicketTrackingEntryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'status'             => new sfWidgetFormInputText(),
      'ticket_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Ticket'), 'add_empty' => false)),
      'ticket_tracking_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TicketTracking'), 'add_empty' => false)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'status'             => new sfValidatorInteger(),
      'ticket_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Ticket'))),
      'ticket_tracking_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TicketTracking'))),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ticket_tracking_entry[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TicketTrackingEntry';
  }

}
