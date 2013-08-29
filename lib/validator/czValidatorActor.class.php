<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package symfony
 * @subpackage plugin
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version SVN: $Id: sfGuardValidatorUser.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
class czValidatorActor extends sfValidatorBase {
    public function configure($options = array(), $messages = array())
    {
        $this->addOption('throw_global_error', false);

        $this->addOption('max_length');
        $this->addMessage('max_length', 'Le numéro doit etre composé de 10 chiffres');

        $this->setMessage('invalid', 'Aucun adhérent de la base ne correspond à ce numéro');
        $this->setMessage('required', 'Le numéro est obligatoire');
    }

    protected function doClean($values)
    {
        // don't allow to sign in with an empty username
        if (!$values && ($this->getOption('required') == true)) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('actor_field') => new sfValidatorError($this, 'required')));
        }
		if ($actor = CoreConnector::getUserInfo($values)) {
            return $actor;
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('actor_field') => new sfValidatorError($this, 'invalid')));
    }

    protected function getTable()
    {
        return Doctrine::getTable('Actor');
    }
}