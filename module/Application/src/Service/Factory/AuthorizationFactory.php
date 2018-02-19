<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\Authorization;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service               = new Authorization();
        $aclClass              = $container->get(\Application\Acl\DoctrineAcl::class);
        $authenticationService = $container->get(AuthenticationService::class);

        $service->setAclClass($aclClass);
        $service->setAuthenticationService($authenticationService);

        return $service;
    }
}
