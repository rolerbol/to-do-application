<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\TaskController;
use Application\Form\LoginForm;
use Application\Form\RegisterForm;
use Zend\View\Renderer\PhpRenderer;
use Application\Service\RegisterService;
use Application\Form\AddTaskForm;
use Application\Service\TaskService;
use Application\Form\AddTaskListForm;
use Application\Form\EditTaskForm;
use Application\Service\TaskListService;

/**
 * The factory responsible for creating of authentication service.
 */
class TaskControllerFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new TaskController();

        $editTaskForm = $container->get(EditTaskForm::class);

        $instance->setEditTaskForm($editTaskForm);

        $addTaskForm = $container->get(AddTaskForm::class);
        
        $instance->setAddTaskForm($addTaskForm);
        
        $addTaskListForm = $container->get(AddTaskListForm::class);
        
        $instance->setAddTaskListForm($addTaskListForm);
        
        $taskService = $container->get(TaskService::class);
        
        $instance->setTaskService($taskService);
        
        $taskListService = $container->get(TaskListService::class);
        
        $instance->setTaskListService($taskListService);
        
        $phpRenderer = $container->get(PhpRenderer::class);
        
        $instance->setPhpRenderer($phpRenderer);
        
        return $instance;
    }
}