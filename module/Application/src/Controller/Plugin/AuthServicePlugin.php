<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Application\Service\AuthAdapter;

class AuthServicePlugin extends AbstractPlugin
{
    /**
     * @vat AuthAdapter
     */
    private $authAdapter;
    
    /**
     * @var AuthenticationService
     */
    private $authService;
    
    /**
     * @param AuthenticationService $authService
     * @return AuthServicePlugin
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
    
    /**
     * @param AuthAdapter $authAdapter
     * @return AuthServicePlugin
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }
    
    /**
     * @return AuthAdapter
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }
    
    /**
     * @return AuthenticationService
     */
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * @param string $userEmail
     * @param string $userPassword
     */
    public function authenticate($userEmail, $userPassword)
    {
        $authAdapter = $this->getAuthAdapter();
        $authAdapter->setEmail($userEmail);
        $authAdapter->setPassword($userPassword);
        
        return $this->getAuthService()->authenticate($authAdapter);
    }
    
    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    public function clearIdentity()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $this->getAuthService()->clearIdentity();
        }
    }
    
    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }
    
    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }
}

