<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;

class AclResourceRepository extends EntityRepository
{
    /**
     * Returns list of resources as array in format 'resource' => 'parentResource'
     *
     * @return array List of resources
     */
    public function getAclResources()
    {
        $query     = $this->_em->createQuery('SELECT r FROM \Application\Model\Entity\AclResource r');
        $result    = $query->getArrayResult();
        $resources = array();

        foreach ($result as $resource) {
            $resources[$resource['aclResourceName']] = null;
        }

        return $resources;
    }
}

