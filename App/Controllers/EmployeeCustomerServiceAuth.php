<?php

namespace App\Controllers;

use \App\AuthMethod;
use \App\FlashMessage;
use \App\Models\UserRole;

/**
 * EmployeeCustomerService base controller
 * 
 * PHP version 7.0
 */ 
abstract class EmployeeCustomerServiceAuth extends Authenticated
{
    /**
     * Require the user to be authenticated before giving access to all methods * in the controller
     * 
     * @return void
     */ 
    protected function before()
    {
        parent::before();

        $this->user = AuthMethod::getUser();

        $this->requireEmployee();

    }

     /**
     * Require the user to be administrator before giving access to the 
     * requested page.
     * 
     * Remember the requested page for later, then redirect to the login page.
     * 
     * @return void
     */ 
    protected function requireEmployee()
    {
        if ($this->user->user_role_id != UserRole::ROLE_CUSTOMER_SERVICE) {

            FlashMessage::add('Kundenservice Mitarbeiterrolle erforderlich', FlashMessage::INFO);

            $this->redirect('/');
        }
    }
}