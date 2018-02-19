<?php
namespace Application\Form;

use \Zend\Form\Form;

class RegisterForm extends Form
{
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('register-form');

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
             'name' => 'rePassword',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Confirm Password',
             ),
             'attributes' => [
                 'class' => 'form-control',
                 'placeholder' => 'Enter password again'
             ],
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'id'    => 'registerButton',
                 'class' => 'btn btn-primary',
                 'value' => 'Register',
             ),
         ));
     }
     
    public function getInputFilter()
    {
        $inputFilter = parent::getInputFilter();
        
        $inputFilter->add(
            [
                'name'     => 'userEmail',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,                            
                        ],
                    ],
                ],
            ]
        );
        
        $inputFilter->add(
            [
                'name'     => 'userPassword',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]
        );
        
        $inputFilter->add(
            [
                'name'     => 'rePassword',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'userPassword',
                        ],
                    ],
                ],
            ]
        );
        
        return $inputFilter;
    }
}