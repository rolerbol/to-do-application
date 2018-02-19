<?php
namespace Application\Form;

use \Zend\Form\Form;

class AddTaskListForm extends Form
{
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('add-task-list-form');

         $this->setAttribute('class', 'form-inline');
         
         $this->add(array(
             'name' => 'name',
             'type' => 'Text',
             'options' => array(
                 'label' => 'List name',
             ),
             'attributes' => [
                 'class' => 'form-control',
                 'placeholder' => 'Sample task list  name'
             ],
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'id'    => 'addTaskListButton',
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
                'name'     => 'name',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 255
                        ],
                    ],
                ],
            ]
        );
        
        return $inputFilter;
    }
}