<?php
namespace Application\Acl\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Application\Acl\DoctrineAcl;
use Application\Module;
use Zend\ServiceManager\Factory\FactoryInterface;

class DoctrineAclFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DoctrineAcl
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);
        $cacheAdapter  = null;
//        $config        = $container->get('config');
//
//        if (!empty($config[Module::CONFIG_KEY][$requestedName]['cacheAdapter'])) {
//            $cacheAdapter = $container->get($config[Module::CONFIG_KEY][$requestedName]['cacheAdapter']);
//        }

        return new DoctrineAcl($entityManager, $cacheAdapter);
    }
}
