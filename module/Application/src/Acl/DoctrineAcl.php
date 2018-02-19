<?php

namespace Application\Acl;

use Doctrine\ORM\EntityManager;
use Zend\Cache\Storage\StorageInterface;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * Application Acl class
 */
class DoctrineAcl extends AbstractAcl implements Rebuildable
{

    /**
     * Constant for max iterations counter
     */
    const MAX_ITERATIONS = 100;

    /**
     * @var \Doctrine\ORM\EntityManager EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cacheAdapter;

    /**
     * Class constructor. Initializes Acl from Database using Doctrine
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Zend\Cache\Storage\StorageInterface $cacheAdapter
     */
    public function __construct(EntityManager $entityManager, StorageInterface $cacheAdapter = null)
    {
        $this->setEntityManager($entityManager);

        if (null !== $cacheAdapter) {
            $this->setCacheAdapter($cacheAdapter);
        }

        $this->buildAcl();
    }

    /**
     * Cache adapter getter
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    /**
     * Cache adapter setter
     *
     * @param \Zend\Cache\Storage\StorageInterface $cacheAdapter
     * @return \Application\Acl\DoctrineAcl
     */
    public function setCacheAdapter(StorageInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }

    /**
     * Returns true if caching is used
     *
     * @return boolean
     */
    public function hasCache()
    {
        return (null !== $this->getCacheAdapter());
    }

    /**
     * EntityManager setter
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return \Application\Acl\DoctrineAcl
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * EntityManager getter
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Builds acl
     *
     * @return \Application\Acl\DoctrineAcl
     */
    protected function buildAcl()
    {
        $this->addRoles($this->getDoctrineRoles())
            ->addResources($this->getDoctrineResources())
            ->addRules($this->getDoctrineRules());

        return $this;
    }

    /**
     * Clears cache(if used) and Acl and rebuilds it from database
     *
     * @return \Application\Acl\DoctrineAcl
     */
    public function rebuildAcl()
    {
        if ($this->hasCache()) {
            $this->getCacheAdapter()->flush();
        }

        $this->removeRoleAll();
        $this->removeResourceAll();
        $this->buildAcl();

        return $this;
    }

    /**
     * Returns list of roles as array in format 'role' => 'parentRole'
     *
     * @return array List of roles
     */
    protected function getDoctrineRoles()
    {
        if (!$this->hasCache()) {
            return $this->getEntityManager()->getRepository('Application\Model\Entity\Role')->getAclRoles();
        }

        $cacheAdapter = $this->getCacheAdapter();

        if (!$cacheAdapter->hasItem('roles')) {
            $roles = $this->getEntityManager()->getRepository('Application\Model\Entity\Role')->getAclRoles();
            $cacheAdapter->setItem('roles', $roles);
        }

        return $cacheAdapter->getItem('roles');
    }

    /**
     * Returns list of resources as array in format 'resource' => 'parentResource'
     *
     * @return array List of resources
     */
    protected function getDoctrineResources()
    {
        if (!$this->hasCache()) {
            return $this->getEntityManager()->getRepository('Application\Model\Entity\AclResource')->getAclResources();
        }

        $cacheAdapter = $this->getCacheAdapter();

        if (!$cacheAdapter->hasItem('resources')) {
            $resources = $this->getEntityManager()->getRepository('Application\Model\Entity\AclResource')->getAclResources();
            $cacheAdapter->setItem('resources', $resources);
        }

        return $cacheAdapter->getItem('resources');
    }

    /**
     * Returns list of rules in the following format:
     * array(
     *  'allow' => array(
     *          'role1' => 'resource1, resource2',
     *          ....
     *   ),
     *  'deny'  => array(
     *          'role2' => 'resource3, resource4',
     *          ....
     *   )
     * )
     *
     * @return array List of rules
     */
    protected function getDoctrineRules()
    {
        if (!$this->hasCache()) {
            return $this->getEntityManager()->getRepository('Application\Model\Entity\AclRoleResource')->getAclRules();
        }

        $cacheAdapter = $this->getCacheAdapter();

        if (!$cacheAdapter->hasItem('rules')) {
            $rules = $this->getEntityManager()->getRepository('Application\Model\Entity\AclRoleResource')->getAclRules();
            $cacheAdapter->setItem('rules', $rules);
        }

        return $cacheAdapter->getItem('rules');
    }

    /**
     * Populates the Acl Roles
     * Now roles will be added independently of their order
     *
     * @param array $roles
     * @return \Application\Acl\DoctrineAcl
     */
    protected function addRoles($roles)
    {
        $queue = new \SplQueue();

        //  enqueue all roles to work with
        foreach ($roles as $role => $parent) {
            $queue->enqueue(array($role => $parent));
        }
        $iterations = 0;
        $current    = $queue->dequeue();
        while ($current && $iterations < self::MAX_ITERATIONS) {
            $role   = key($current);
            $parent = $current[$role];

            $next = null;
            if ($queue->count()) {
                $next = $queue->dequeue();
            }
            //  add role if not added and if parent exists
            if (!$this->hasRole($role) && ($this->hasRole($parent) || empty($parent))) {
                $this->addRole(new Role($role), $parent);
                $current = $next;
                continue;
            }

            $queue->enqueue($current);
            $current = $next;
            $iterations+=1;
        }
        //  if maximum iteration limit is reached
        if ($iterations >= self::MAX_ITERATIONS) {
            throw new \RuntimeException("Some roles were not added due to circularity");
        }

        return $this;
    }

    /**
     * Populates the Acl Resources
     *
     * @param array $resources
     * @return \Application\Acl\DoctrineAcl
     */
    protected function addResources($resources)
    {
        foreach ($resources as $resource => $parent) {
            if (!$this->hasResource($resource)) {
                $this->addResource(new AssertResource($resource), $parent);
            }
        }

        return $this;
    }

    protected function addPrivileges($privileges)
    {
        foreach ($privileges as $resource) {
            if (!$this->hasResource($resource)) {
                $this->addResource($resource);
            }
        }

        return $this;
    }

    /**
     * Populates Acl rules
     *
     * @param array $rules
     * @return \Application\Acl\DoctrineAcl
     */
    protected function addRules($rules)
    {
        if (!empty($rules[self::ACTION_ALLOW])) {
            foreach ($rules[self::ACTION_ALLOW] as $role => $resources) {
                $this->allow($role, $resources);
            }
        }

        if (!empty($rules[self::ACTION_DENY])) {
            foreach ($rules[self::ACTION_DENY] as $role => $resources) {
                $this->deny($role, $resources);
            }
        }

        return $this;
    }

    /**
     * Populates the Acl Privileges
     *
     * @param array $privilegeRules
     * @return \Application\Acl\DoctrineAcl
     */
    protected function addPrivilegeRules($privilegeRules)
    {
        if (!empty($privilegeRules[self::ACTION_ALLOW])) {
            foreach ($privilegeRules[self::ACTION_ALLOW] as $role => $privileges) {
                foreach ($privileges as $privilegeResource) {
                    if (!$this->hasResource($privilegeResource)) {
                        $this->addResource($privilegeResource);
                    }
                }
                $this->allow($role, $privileges);
            }
        }

        if (!empty($privilegeRules[self::ACTION_DENY])) {
            foreach ($privilegeRules[self::ACTION_DENY] as $role => $privileges) {
                foreach ($privileges as $privilegeResource) {
                    if (!$this->hasResource($privilegeResource)) {
                        $this->addResource($privilegeResource);
                    }
                }

                $this->deny($role, $privileges);

            }
        }

        return $this;
    }

    /**
     * Adds assertions to the Acl
     *
     * @param array $assertions - see AssertionRepository for the format
     * @param \Zend\Mvc\MvcEvent $event
     * @return void
     */
    protected function addAssertions(array $assertions, $event)
    {
        if (empty($assertions)) {
            return;
        }

        $assertionsCache = array();
        $serviceManager  = $event->getApplication()->getServiceManager();
        $assertResource  = $this->getResource($assertions['resource']);
        $assertResource->setData($serviceManager->get('AssertResourceDataFactory')->makeData($event));

        foreach ($assertions['assertions'] as $role => $assertionsList) {
            $compositeAssertion = $serviceManager->get('CompositeAssertion');

            foreach ($assertionsList as $assertionName) {
                if (!isset($assertionsCache[$assertionName])) {
                    $assertionsCache[$assertionName] = $serviceManager->get($assertionName);
                }

                $compositeAssertion->push($assertionsCache[$assertionName]);
            }

            $this->allow($role, $assertResource, null, $compositeAssertion);
        }
    }

    /**
     * Checks if event target is allowed by acl for specified roles
     *
     * @param MvcEvent $event
     * @param array $roles
     * @return bool
     * @throws \Exception
     */
    public function isPermitted(MvcEvent $event, array $roles)
    {
        if (null !== $event->getRouteMatch()) {
            $resource = $this->makeResourceFromEvent($event);

            if (!$this->hasResource($resource)) {
                throw new \Exception('Resource `' . $resource . '` is not defined');
            }

//            $this->addAssertions($this->getDoctrineAssertions($resource), $event);

            foreach ($roles as $role) {

                if ($this->isAllowed($role, $resource)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Composes resource name from event
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @return string
     */
    protected function makeResourceFromEvent($event)
    {
        $controllerClassName = get_class($event->getTarget());
        $filter              = new DashToCamelCase();
        $action              = lcfirst($filter->filter($event->getRouteMatch()->getParam('action')));
        return "{$controllerClassName}_{$action}";
    }

    /**
     * Generates cache key base
     *
     * @param string $name
     */
    protected function generateCacheKeyBase($name)
    {
        return str_replace(array('\\'), '', $name);
    }
}
