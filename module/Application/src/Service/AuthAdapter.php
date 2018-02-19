<?php
namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Application\Model\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * User email.
     * @var string 
     */
    private $email;
    
    /**
     * Password
     * @var string 
     */
    private $password;
    
    /**
     * Sets user email.     
     */
    public function setEmail($email) 
    {
        $this->email = $email;        
    }
    
    /**
     * Sets password.     
     */
    public function setPassword($password) 
    {
        $this->password = (string)$password;        
    }
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * @param EntityManager $entityManager
     * @return AuthAdapter
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
    
    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        $user = $this->getEntityManager()
                        ->getRepository(User::class)
                            ->findOneBy(['userEmail' => $this->email]);
        
        if (null == $user) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['Invalid credentials.']
            ); 
        }   
        
        if (null != $user->getDateDeleted()) {
            return new Result(
                Result::FAILURE, 
                null, 
                ['User is deactivated.']
            );        
        }
        
        $hash = $user->getUserPassword();
        
        if (password_verify($this->password, $hash)) {
            return new Result(
                Result::SUCCESS, 
                $this->getIdentityResult($user), 
                ['Authenticated successfully.']
            );
        }
        
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID, 
            null, 
            ['Invalid credentials.']
        );        
    }
    
    /**
     * @param User $user
     */
    private function getIdentityResult(User $user)
    {
        $userRoles = $user->getUserRoles();

        $roles = [];

        foreach ($userRoles as $userRole) {
            $roles[$userRole->getRoleID()] = [
                'roleID' => $userRole->getRoleID(),
                'name' => $userRole->getRoleName(),
            ];
        }
        return [
            'userID' => $user->getUserID(),
            'userEmail' => $user->getUserEmail(),
            'roles' => $roles
        ];
    }
}