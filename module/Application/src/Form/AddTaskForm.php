<?php
namespace Application\Form;

use \Zend\Form\Form;

class AddTaskForm extends Form
{
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('add-task-form');

         $this->setAttribute('class', 'form-inline');
         
         $this->add(array(
             'name' => 'taskText',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Text',
             ),
             'attributes' => [
                 'class' => 'form-control',
                 'placeholder' => 'Sample task text'
             ],
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'id'    => 'addTaskButton',
                 'class' => 'btn btn-primary',
                 'value' => 'Add',
             ),
         ));
     }
     
    public function getInputFilter()
    {
        $inputFilter = parent::getInputFilter();
        
        $inputFilter->add(
            [
                'name'     => 'taskText',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 500
                        ],
                    ],
                ],
            ]
        );
        
        return $inputFilter;
    }
}