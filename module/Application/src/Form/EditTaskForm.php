<?php
namespace Application\Form;

use \Zend\Form\Form;
use Application\Model\Repository\TaskRepository;

class EditTaskForm extends Form
{
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('edit-task-form');

//         $this->setAttribute('class', 'form-inline');

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
             'name' => 'taskStatus',
             'type' => 'Select',
             'options' => array(
                 'label' => 'Text',
                 'value_options' => [
                    TaskRepository::TASK_STATUS_CREATED => TaskRepository::TASK_STATUS_CREATED,
                    TaskRepository::TASK_STATUS_IN_PROCESSING => TaskRepository::TASK_STATUS_IN_PROCESSING,
                    TaskRepository::TASK_STATUS_COMPLETED => TaskRepository::TASK_STATUS_COMPLETED,
                    TaskRepository::TASK_STATUS_CLOSED => TaskRepository::TASK_STATUS_CLOSED,
                 ],
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