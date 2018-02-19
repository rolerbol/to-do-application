<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;

class TaskTypeRepository extends EntityRepository
{
    const TASK_TYPE_TO_DO_ID = '1';
    
    /**
     * @return \Application\Model\Entity\TaskType
     * @throws \Exception
     */
    public function getToDoTaskType()
    {
        $taskTypeResult = $this->findOneBy(['taskTypeID' => self::TASK_TYPE_TO_DO_ID, 'active' => '1', 'dateDeleted' => null]);
        
        return $taskTypeResult;
    }
}

