<?php

/**
 * BaseTicket
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $bubble_tag
 * @property float $amount
 * @property integer $status
 * @property date $expire_at
 * @property Doctrine_Collection $TicketTrackingEntry
 * 
 * @method string              getBubbleTag()           Returns the current record's "bubble_tag" value
 * @method float               getAmount()              Returns the current record's "amount" value
 * @method integer             getStatus()              Returns the current record's "status" value
 * @method date                getExpireAt()            Returns the current record's "expire_at" value
 * @method Doctrine_Collection getTicketTrackingEntry() Returns the current record's "TicketTrackingEntry" collection
 * @method Ticket              setBubbleTag()           Sets the current record's "bubble_tag" value
 * @method Ticket              setAmount()              Sets the current record's "amount" value
 * @method Ticket              setStatus()              Sets the current record's "status" value
 * @method Ticket              setExpireAt()            Sets the current record's "expire_at" value
 * @method Ticket              setTicketTrackingEntry() Sets the current record's "TicketTrackingEntry" collection
 * 
 * @package    tracking.sol-violette.info
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTicket extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ticket');
        $this->hasColumn('bubble_tag', 'string', 13, array(
             'type' => 'string',
             'length' => 13,
             ));
        $this->hasColumn('amount', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('status', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('expire_at', 'date', null, array(
             'type' => 'date',
             ));


        $this->index('bubble_tag_index', array(
             'fields' => 
             array(
              0 => 'bubble_tag',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('TicketTrackingEntry', array(
             'local' => 'id',
             'foreign' => 'ticket_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}