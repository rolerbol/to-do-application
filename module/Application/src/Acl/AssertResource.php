<?php
namespace Application\Acl;

use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * AssertResource class used as resource in ACL assertions
 */
class AssertResource extends GenericResource
{

    /**
     * Additional data
     * @var mixed $data
     */
    protected $data;

    /**
     * Additonal data getter
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Additional data setter
     *
     * @param mixed $data
     * @return \Application\Acl\AssertResource
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
