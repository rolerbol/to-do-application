<?php
namespace Application\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Plugin\EntityManagerPlugin;

/**
 * The factory responsible for creating of authentication service.
 */
class EntityManagerPluginFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new EntityManagerPlugin();
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        
        $instance->setEntityManager($entityManager);
                
        return $instance;
    }
}