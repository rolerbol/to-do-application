<?php
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="TaskTypes")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\TaskTypeRepository")
 */
class TaskType extends AbstractEntity
{
    /**
     * @var integer $taskTypeID
     *
     * @ORM\Column(name="taskTypeID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $taskTypeID;

    /**
     * @var string $taskTypeName
     *
     * @ORM\Column(name="taskTypeName", type="string", length=45, nullable=false)
     */
    protected $taskTypeName;
    
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