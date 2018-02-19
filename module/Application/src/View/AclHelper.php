<?php
namespace Application\View;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use Application\Acl\AbstractAcl;

class AclHelper extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var AbstractAcl
     */
    protected $acl;

    /**
     * @param AbstractAcl $acl
     * @return AclHelper
     */
    public function setAcl(AbstractAcl $acl)
    {
        $this->acl = $acl;
        return $this;
    }

    /**
     * @return AbstractAcl
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param AuthenticationService $authService
     * @return AclHelper
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
    
    /**
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }
    
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * @return boolean true || false
     */
    public function isPermitted($resourceName)
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return false;
        }
        
        try {
            $result = $this->checkResource($resourceName);
        } catch (\Exception $ex) {
            $result = false;
        }

        return $result;
    }

    public function checkResource($resourceName)
    {
        $acl       = $this->getAcl();
        $identity  = $this->getView()->identity();
        $userRoles = empty($identity['roles']) ? array() : $identity['roles'];

        foreach ($userRoles as $role) {
            if ($acl->isAllowed($role['name'], $resourceName)) {
                return true;
            }
        }

        return false;
    }
}