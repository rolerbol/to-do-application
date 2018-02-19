<?php
namespace Application\Service\Factory;

use \Interop\Container\ContainerInterface;
use \Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\TaskService;

/**
 * The factory responsible for creating of authentication service.
 */
class TaskServiceFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new TaskService();
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        
        $instance->setEntityManager($entityManager);
                
        return $instance;
    }
}