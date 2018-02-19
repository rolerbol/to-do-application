<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\UserRepository")
 */
class User extends AbstractEntity
{
    /**
     * @var integer $userID
     *
     * @ORM\Column(name="userID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $userID;

    /**
     * @var string $userEmail
     *
     * @ORM\Column(name="userEmail", type="string", length=255, nullable=false)
     */
    protected $userEmail;
    
    /**
     * @var string $userPassword
     *
     * @ORM\Column(name="userPassword", type="string", length=60, nullable=false)
     */
    protected $userPassword;
    
     /**
     * @ORM\ManyToMany(targetEntity="Application\Model\Entity\Role")
     * @ORM\JoinTable(name="UserRoles",
     *      joinColumns={@ORM\JoinColumn(name="userID", referencedColumnName="userID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="roleID", referencedColumnName="roleID", unique=true)}
     * )
     */
    protected $userRoles;

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
    
    public function __construct() {
        $this->userRoles = new ArrayCollection();
    }
}