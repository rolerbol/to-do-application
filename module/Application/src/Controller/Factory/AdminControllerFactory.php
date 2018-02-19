<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AdminController;
use Application\Service\TaskListService;

/**
 * The factory responsible for creating of authentication service.
 */
class AdminControllerFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service
     * and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new AdminController();

        $taskListService = $container->get(TaskListService::class);

        $instance->setTaskListService($taskListService);

        return $instance;
    }
}