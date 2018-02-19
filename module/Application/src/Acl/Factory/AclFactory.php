<?php
namespace Application\Acl\Factory;

use Interop\Container\ContainerInterface;
use Application\Acl\Acl;
use Application\Module;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AclFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @throws ServiceNotCreatedException
     * @return Acl
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Acl();
    }
}
