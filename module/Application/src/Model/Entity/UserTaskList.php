<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="UserTaskLists")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\UserTaskListRepository")
 */
class UserTaskList extends AbstractEntity
{
    /**
     * @var integer $userTaskListID
     *
     * @ORM\Column(name="userTaskListID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $userTaskListID;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string $archive
     *
     * @ORM\Column(name="archive", type="integer", length=1, nullable=false)
     */
    protected $archive;
    
    /**
     * @var string $deleteRequest
     *
     * @ORM\Column(name="deleteRequest", type="integer", length=1, nullable=false)
     */
    protected $deleteRequest;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Model\Entity\Task")
     * @ORM\JoinTable(name="UserTaskListTasks",
     *      joinColumns={@ORM\JoinColumn(name="userTaskListID", referencedColumnName="userTaskListID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="taskID", referencedColumnName="taskID")}
     * )
     */
    protected $tasks;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\User")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    protected $user;

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
        $this->tasks = new ArrayCollection();
    }
}