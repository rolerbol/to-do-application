<?php
namespace Application\Service;

use Application\Model\Entity\User;
use Application\Model\Entity\Task;
use Doctrine\ORM\EntityManager;

/**
 */
class TaskService
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * @param EntityManager $entityManager
     * @return AuthAdapter
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
    
    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * @param array 
     * @return boolean
     */
    public function createToDoTask($taskData)
    {
        $userID = $taskData['taskUserID'];
        $taskText = $taskData['taskText'];
        $userTaskListID = $taskData['userTaskListID'];
        
        $toDoType = $this->getEntityManager()->getRepository(\Application\Model\Entity\TaskType::class)->getToDoTaskType();
        
        $taskUser = $this->getEntityManager()->getRepository(User::class)->find($userID);
        
        $task = new Task();
        $task->setTaskText($taskText);
        $task->setTaskUser($taskUser);
        $task->setTaskType($toDoType);
        $task->setTaskArchive('0');
        
        $task->setTaskStatus(\Application\Model\Repository\TaskRepository::TASK_STATUS_CREATED);
        
        $dateCreated = new \DateTime();
        
        $task->setDateCreated($dateCreated);
        $task->setTaskDateTime($dateCreated);
        
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
        
        $userTaskList = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class)->find($userTaskListID);
        
        $tasks = $userTaskList->getTasks();
        $tasks->add($task);
        
        $userTaskList->setTasks($tasks);
        
        $this->getEntityManager()->persist($userTaskList);
        
        $this->getEntityManager()->flush();
        
        return true;
    }
}