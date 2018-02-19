<?php
namespace Application\View\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\AclHelper;
use Zend\Authentication\AuthenticationService;
use Application\Acl\DoctrineAcl;

/**
 * The factory responsible for creating of authentication service.
 */
class AclHelperFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new AclHelper();
        
        $authService = $container->get(AuthenticationService::class);

        $doctrineAcl = $container->get(DoctrineAcl::class);

        $instance->setAuthService($authService);

        $instance->setAcl($doctrineAcl);
        
        return $instance;
    }
}