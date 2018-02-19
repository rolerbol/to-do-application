<?php
namespace Application\Service;

use Application\Model\Entity\User;
use Application\Model\Entity\Role;
use Doctrine\ORM\EntityManager;

/**
 */
class RegisterService
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
    
    public function register() 
    {
        $userRepo = $this->getEntityManager()->getRepository(User::class);
        
        $roleRepo = $this->getEntityManager()->getRepository(Role::class);
        
        try {
            $existUser = $userRepo->findOneBy(['userEmail' => $this->email]);
            
            if (!empty($existUser)) {
                throw new \Exception('User exist');
            }
            
            $activeUserRole = $roleRepo->getActiveUserRole();
            
            $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
            $user = new User();
            $user->setUserEmail($this->email);
            $user->setUserPassword($passwordHash);
            
            $userRoles = $user->getUserRoles();
            /* @var $userRoles \Doctrine\Common\Collections\ArrayCollection */
            
            $userRoles->add($activeUserRole);
            
            $user->setUserRoles($userRoles);
            
            $this->getEntityManager()->persist($user);
            
            $this->getEntityManager()->flush();
        } catch (\Throwable $th) {
            return false;
        }
        
        return true;
    }
}