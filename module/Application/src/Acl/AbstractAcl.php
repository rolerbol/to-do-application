<?php
namespace Application\Acl;

use \Zend\Mvc\MvcEvent;
use \Zend\Permissions\Acl\Acl as ZendAcl;

/**
 * AbstractAcl class
 */
abstract class AbstractAcl extends ZendAcl
{

    const DEFAULT_ROLE  = 'Guest';
    const GUEST_ROLE_ID = 1;
    const ACTION_ALLOW  = 'allow';
    const ACTION_DENY   = 'deny';

    /**
     * Checks if event target is allowed by acl for specified roles
     *
     * @param Zend\Mvc\MvcEvent $event Request event
     * @param array $roles User roles
     * @return boolean
     */
    abstract public function isPermitted(MvcEvent $event, array $roles);

    /**
     * Returns true if resource is not defined or is allowed for any of the $roles
     *
     * @param string $resource
     * @param array $roles
     * @return boolean
     */
    public function isResourceAllowed($resource, array $roles)
    {
        if (!$this->hasResource($resource)) {
            return true;
        }

        foreach ($roles as $role) {
            if ($this->isAllowed($role, $resource)) {
                return true;
            }
        }

        return false;
    }
}
