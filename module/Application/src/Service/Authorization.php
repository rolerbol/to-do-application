<?php
namespace Application\Service;

use Zend\Mvc\MvcEvent as MvcEvent;
use Application\Acl\AbstractAcl;

/**
 * Authorization Service class
 */
class Authorization
{

    /**
     * @var \Application\Acl\AbstractAcl Acl used
     */
    protected $aclClass;

    /**
     * @var AuthenticationServiceInterface
     */
    protected $authenticationService;

    /**
     * Redirects to CAS if user is not logged in or current role does not have
     * enough permissions
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @return mixed
     * @throws \Exception
     */
    public function authorize(MvcEvent $event)
    {
        $sm          = $event->getApplication()->getServiceManager();
        $authService = $sm->get('Zend\Authentication\AuthenticationService');
        $acl         = $this->getAclClass();
        $roles       = $this->getUserRoles($authService);

        if (!$acl->isPermitted($event, $roles)) {
            if (null == $event->getRouteMatch() && $authService->hasIdentity()) {
                throw new \Exception("Page not found", 404);
            }

            if ($authService->hasIdentity()) {
                throw new \Exception("Page not allowed", 403);
            }

            $event->stopPropagation(true);

            $response = $event->getResponse();
            $response->getHeaders()->addHeaderLine('Location', '/home');
            $response->setStatusCode(302);
            return $response;
        }
    }

    /**
     * Constructs list of current user roles
     *
     * @param \Zend\Authentication\AuthenticationService $authService
     * @return array
     */
    protected function getUserRoles($authService)
    {
        $roles = array(AbstractAcl::DEFAULT_ROLE);

        if ($authService->hasIdentity()) {
            $user = $authService->getIdentity();

            if (!empty($user['roles'])) {
                foreach ($user['roles'] as $role) {
                    $roles[] = $role['name'];
                }
            }
        }

        return $roles;
    }

    /**
     * @param AuthenticationServiceInterface $authenticationService
     * @return Authorization
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;

        return $this;
    }

    /**
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * Sets the ACL class to be used
     *
     * @param \Application\Acl\AbstractAcl
     * @return \Application\Service\Authorization
     */
    public function setAclClass(AbstractAcl $aclClass)
    {
        $this->aclClass = $aclClass;

        return $this;
    }

    /**
     * Gets the ACL Class
     *
     * @return \Application\Acl\AbstractAcl
     */
    public function getAclClass()
    {
        return $this->aclClass;
    }
}
