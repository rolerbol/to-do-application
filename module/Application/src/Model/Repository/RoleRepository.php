<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Role;

class RoleRepository extends EntityRepository
{
    const ACTIVE_ROLE_GUEST = 'Guest';
    const ACTIVE_ROLE_ADMINISTRATOR = 'Administrator';
    const ACTIVE_ROLE_USER= 'User';
    
    /**
     * @return Role
     * @throws \Exception
     */
    public function getActiveUserRole()
    {
        $roleResult = $this->findOneBy(['roleName' => self::ACTIVE_ROLE_USER, 'active' => '1', 'dateDeleted' => null]);
        
        return $roleResult;
    }

    /**
     * Returns list of roles as array in format 'role' => 'parentRole'
     *
     * @return array List of roles
     */
    public function getAclRoles()
    {
        $query  = $this->_em->createQuery('SELECT r FROM \Application\Model\Entity\Role r');
        $result = $query->getArrayResult();
        $roles  = array();

        foreach ($result as $role) {
            $roles[$role['roleName']] = null;
        }

        return $roles;
    }
}

