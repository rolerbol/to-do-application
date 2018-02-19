<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;

class AclRoleResourceRepository extends EntityRepository
{
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
    public function getAclRules()
    {
        $query = $this->_em->createQuery(
            'SELECT roleResource, role, resource
                FROM \Application\Model\Entity\AclRoleResource roleResource
                JOIN roleResource.role role
                JOIN roleResource.aclResource resource'
        );

        $result = $query->getArrayResult();

        return $this->extractRules($result);
    }

    /**
     * Extract rules from result in appropriate format
     *
     * @param array $result
     * @return array
     */
    protected function extractRules($result)
    {
        $rules = array();

        foreach ($result as $rule) {
            $resourceName = $rule['aclResource']['aclResourceName'];
            $roleName     = $rule['role']['roleName'];

            if ($rule['aclAllow']) {
                $rules['allow'][$roleName][] = $resourceName;
            } else {
                $rules['deny'][$roleName][] = $resourceName;
            }
        }

        return $rules;
    }
}

