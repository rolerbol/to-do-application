<?php
namespace Application\Form;

use \Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('login-form');

         $this->add(array(
             'name' => 'userEmail',
             'type' => 'Email',
             'options' => array(
                 'label' => 'Email',
             ),
             'attributes' => [
                 'class' => 'form-control',
                 'placeholder' => 'Enter email'
             ],
         ));
         
         $this->add(array(
             'name' => 'userPassword',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password',
             ),
             'attributes' => [
                 'class' => 'form-control',
                 'placeholder' => 'Enter password'
             ],
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'id'    => 'signInButton',
                 'class' => 'btn btn-primary',
                 'value' => 'Sign in',
             ),
         ));
     }
     
    public function getInputFilter()
    {
        $inputFilter = parent::getInputFilter();
        
        $inputFilter->add(
            [
                'name'     => 'userEmail',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],            
            ]
        );
        
        $inputFilter->add(
            [
                'name'     => 'userPassword',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],   
            ]
        );
        
        return $inputFilter;
    }
}