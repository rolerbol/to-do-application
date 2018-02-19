<?php
namespace Application\Service\Factory;

use \Interop\Container\ContainerInterface;
use \Zend\ServiceManager\Factory\FactoryInterface;
use \Application\Service\AuthAdapter;

/**
 * The factory responsible for creating of authentication service.
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new AuthAdapter();
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
//        
        $instance->setEntityManager($entityManager);
                
        return $instance;
    }
}