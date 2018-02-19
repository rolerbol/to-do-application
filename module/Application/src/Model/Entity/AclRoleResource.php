<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="AclRoleResources")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\AclRoleResourceRepository")
 */
class AclRoleResource extends AbstractEntity
{
    /**
     * @var integer $aclRoleResourceID
     *
     * @ORM\Column(name="aclRoleResourceID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $aclRoleResourceID;

    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\AclResource")
     * @ORM\JoinColumn(name="aclResourceID", referencedColumnName="aclResourceID")
     */
    protected $aclResource;

    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\Role")
     * @ORM\JoinColumn(name="aclRoleID", referencedColumnName="roleID")
     */
    protected $role;

    /**
     * @var string $aclAllow
     *
     * @ORM\Column(name="aclAllow", type="integer", length=1, nullable=false)
     */
    protected $aclAllow;
}