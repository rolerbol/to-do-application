<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Tasks")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\TaskRepository")
 */
class Task extends AbstractEntity
{
    /**
     * @var integer $taskID
     *
     * @ORM\Column(name="taskID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $taskID;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\User")
     * @ORM\JoinColumn(name="taskUserID", referencedColumnName="userID")
     */
    protected $taskUser;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\TaskType")
     * @ORM\JoinColumn(name="taskTypeID", referencedColumnName="taskTypeID")
     */
    protected $taskType;

    /**
     * @var string $taskStatus
     *
     * @ORM\Column(name="taskStatus", type="string", length=255, nullable=false)
     */
    protected $taskStatus;
    
    /**
     * @var string $taskText
     *
     * @ORM\Column(name="taskText", type="string", length=500, nullable=false)
     */
    protected $taskText;
    
    /**
     * @var \DateTime $taskDateTime
     *
     * @ORM\Column(name="taskDateTime", type="datetime", nullable=true)
     */
    protected $taskDateTime;

    /**
     * @var string $taskArchive
     *
     * @ORM\Column(name="taskArchive", type="integer", length=1, nullable=false)
     */
    protected $taskArchive;
    
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