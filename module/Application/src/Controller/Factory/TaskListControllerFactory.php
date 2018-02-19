<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\TaskListController;
use Zend\View\Renderer\PhpRenderer;
use Application\Service\TaskService;
use Application\Form\AddTaskListForm;
use Application\Service\TaskListService;

/**
 * The factory responsible for creating of authentication service.
 */
class TaskListControllerFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service
     * and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new TaskListController();

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