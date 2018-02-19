<?php
namespace Application\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Plugin\AuthServicePlugin;
use Zend\Authentication\AuthenticationService;
use Application\Service\AuthAdapter;

/**
 * The factory responsible for creating of authentication service.
 */
class AuthServicePluginFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new AuthServicePlugin();
        
        $authService = $container->get(AuthenticationService::class);
        
        $instance->setAuthService($authService);
        
        $authAdapter = $container->get(AuthAdapter::class);
        
        $instance->setAuthAdapter($authAdapter);
                
        return $instance;
    }
}