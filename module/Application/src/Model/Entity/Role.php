<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Roles")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\RoleRepository")
 */
class Role extends AbstractEntity
{
    /**
     * @var integer $roleID
     *
     * @ORM\Column(name="roleID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $roleID;

    /**
     * @var string $roleName
     *
     * @ORM\Column(name="roleName", type="string", length=45, nullable=false)
     */
    protected $roleName;
    
    /**
     * @var string $active
     *
     * @ORM\Column(name="active", type="integer", length=1, nullable=false)
     */
    protected $active;
    
    /**
     * @var \DateTime $dateCreated
     *
     * @ORM\Column(name="dateCreated", type="datetime", nullable=false)
     */
    protected $dateCreated;
    
    /**
     * @var \DateTime $dateModified
     *
     * @ORM\Column(name="dateModified", type="datetime", nullable=true)
     */
    protected $dateModified;
    
    /**
     * @var \DateTime $dateDeleted
     *
     * @ORM\Column(name="dateDeleted", type="datetime", nullable=true)
     */
    protected $dateDeleted;
}