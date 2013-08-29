<?php

class userActions extends sfActions {
    public function executeLogin(sfWebRequest $request)
    {
        $this->form = new LoginForm();

        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if ($operator = CoreConnector::login($this->form->getValue('login'), $this->form->getValue('password'))) {
                    // var_dump($operator);exit;
                    $user =/*(myUser)*/ $this->getUser();

                    $operator['role'] = CoreConnector::getRoleFromFlags($operator);
                    unset($operator['is_member']);
                    unset($operator['is_delegate']);
                    unset($operator['is_referer']);
                    unset($operator['is_admin']);

                    $credentials = array();
                    switch ($operator['role']) {
                        case 'member':
                            $canLogin = false;
                            break;
                        case 'delegate':
                        case 'referer':
                            $canLogin = true;
                            $credentials[] = 'tracking';
                            $credentials[] = 'stats';
                            $credentials[] = 'exports';
                            break;
                        case 'admin':
                            $canLogin = true;
                            $credentials[] = 'tracking_advanced';
                            $credentials[] = 'tracking';
                            $credentials[] = 'stats';
                            $credentials[] = 'exports';
                        	if('0682266819' == $operator['phone']){
                        		$credentials[] = 'root';
                        	}
                            break;

                        default:
                            throw new Exception('Unknown role: ' . $operator['role']);
                    } // switch
                    if ($canLogin) {
                        $user->setAuthenticated(true);
                        foreach($operator as $name => $value) {
                            $user->setAttribute(sprintf('operator.%s', $name), $value);
                        }
                        if ($operator['person_name'] && $operator['company_name']) {
                            $user->setAttribute('operator.name', sprintf('%s (%s)', $operator['company_name'], $operator['person_name']));
                        } else {
                            $user->setAttribute('operator.name', empty($operator['person_name'])?$operator['company_name']:$operator['person_name']);
                        }

                        if (!empty($credentials)) {
                            $user->addCredentials($credentials);
                        }

                        $this->redirect('@homepage');
                    }
                }
                $this->errorMessage = 'Informations incorrectes';
            }
        }
        $this->setLayout('layoutLogin');

        return sfView::SUCCESS;
    }

    public function executeLogout(sfWebRequest $request)
    {
        $this->getUser()->setAuthenticated(false);
        $this->redirect('@homepage');
    }
}