<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Models\Employee;
use \App\Models\UserRole;
use \App\FlashMessage;

/**
 * Employee administration controller
 * 
 * PHP version 7.0
 */  
class EmployeeAdministration extends AdminAuth
{

    /**
     * Show the employee signup page
     * 
     * @return void
     */  
    public function showAction()
    {
        $employees = Employee::getAll();
        $roles = UserRole::getAllEmployeeRoles();

        View::renderTemplate('employeeadministration/all.html', [
            'employees' => $employees,
            'roles' => $roles
        ]);

    }

    /**
     * Show the signup page
     * 
     * @return void
     */  
    public function showSignupAction()
    {

        $roles = UserRole::getAllEmployeeRoles();

        View::renderTemplate('employeeadministration/signup.html', [
            'roles' => $roles
        ]);

    }

    /**
     * Confirm the signup
     * 
     * @return void
     */  
    public function confirmSignupAction()
    {

        $employee = new Employee($_POST);

        if ($employee->save()) {

            $employee->sendActivationEmail();

            FlashMessage::add('Mitarbeiter erfolgreich angelegt', FlashMessage::SUCCESS);

            $this->redirect('/EmployeeAdministration/show');
    
        } else {

            $roles = UserRole::getAllEmployeeRoles();

            View::renderTemplate('employeeadministration/signup.html', [
               'employee' => $employee,
               'roles' => $roles
            ]);
        } 
    }

    /**
     * Delete selected employee
     * 
     * @return void
     */  
    public function deleteAction()
    {
        $employeeId = $this->route_params['id'];

        $employee = User::findByID($employeeId);

        if ($employee->user_id != $_SESSION['user_id']) {

            FlashMessage::add('Mitarbeiter gelöscht', FlashMessage::INFO);

            $employee->delete();

        } else {

            FlashMessage::add('Administrator kann nicht gelöscht werden', FlashMessage::INFO);

        }

        $this->redirect('/EmployeeAdministration/show');
    }
}