<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="AclResources")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\AclResourceRepository")
 */
class AclResource extends AbstractEntity
{
    /**
     * @var integer $aclResourceID
     *
     * @ORM\Column(name="aclResourceID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $aclResourceID;

    /**
     * @var string $aclResourceName
     *
     * @ORM\Column(name="aclResourceName", type="string", length=255, nullable=false)
     */
    protected $aclResourceName;

    /**
     * @ORM\OneToMany (targetEntity="\Application\Model\Entity\AclRoleResource", mappedBy="aclResource", orphanRemoval=true, cascade={"all"})
     */
    protected $roleResources;

    public function __construct()
    {
        $this->roleResources = new ArrayCollection();
    }
}