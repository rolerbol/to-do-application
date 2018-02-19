<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\TaskService;
use Application\Model\Entity\User;

/**
 */
class TaskListService
{
    /**
     * Entity manager.
     * @var TaskService
     */
    private $TaskService;

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
    public function createTaskList($taskData)
    {
        $userID = $taskData['userID'];
        $name = $taskData['name'];
        
        $user = $this->getEntityManager()->getRepository(User::class)->find($userID);
        
        $entity = new \Application\Model\Entity\UserTaskList();
        $entity->setName($name);
        $entity->setUser($user);
        $entity->setDeleteRequest('0');
        $entity->setArchive('0');
        $dateCreated = new \DateTime();
        
        $entity->setDateCreated($dateCreated);
        
        $this->getEntityManager()->persist($entity);
        
        $this->getEntityManager()->flush();
        
        return true;
    }
    
    /**
     * @param strint $userID
     */
    public function getUserTaskLists($userID)
    {
        $taskListRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class);
        
        $userTaskLists = $taskListRepo->findBy(
            [
                'user' => $userID,
                'dateDeleted' => null,
                'deleteRequest' => '0',
                'archive' => '0'
            ],
            [
                'dateCreated' => 'DESC'
            ]
        );
        
        $resultTasks = [];
        
        foreach ($userTaskLists as $userTaskList) {
            $listID = $userTaskList->getUserTaskListID();
            
            if (empty($resultTasks[$listID])) {
                $resultTasks[$listID] = [];
            }
            
            $resultTasks[$listID]['userTaskList'] = $userTaskList;

            $resultTasks[$listID]['tasks'] = [];
            
            $listTasks = $userTaskList->getTasks();
            
            foreach ($listTasks as $userTask) {
                $deletedDate = $userTask->getDateDeleted();
                
                if (null != $deletedDate) {
                    continue;
                }

                if ($userTask->getTaskArchive()) {
                    continue;
                }
                
                $taskDate = $userTask->getTaskDateTime();
                if(empty($taskDate)) {
                    $taskDate = $userTask->getDateCreated();
                }

                $timestamp = $taskDate->format('U');
                
                $resultTasks[$listID]['tasks'][$timestamp] = $userTask;
            }
            
            arsort($resultTasks[$listID]['tasks']);
        }
        
        arsort($resultTasks);
        
        return $resultTasks;
    }

    /**
     * @return mixed
     */
    public function getAllUserLists()
    {
        $taskListRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class);

        $userTaskLists = $taskListRepo->findBy(
            [
                'dateDeleted' => null,
                'archive' => '0'
            ],
            [
                'dateCreated' => 'DESC'
            ]
        );

        $resultTasks = [];

        foreach ($userTaskLists as $userTaskList) {
            $userID = $userTaskList->getUser()->getUserID();

            if (empty($resultTasks[$userID])) {
                $resultTasks[$userID] = [];
            }

            $resultTasks[$userID]['user'] = $userTaskList->getUser();

            if (!empty($resultTasks[$userID]['userTaskLists'])) {
                $resultTasks[$userID]['userTaskLists'] = [];
            }
            
            $listID = $userTaskList->getUserTaskListID();

            if (empty($resultTasks[$userID]['userTaskLists'][$listID])) {
                $resultTasks[$userID]['userTaskLists'][$listID] = [];
            }

            $resultTasks[$userID]['userTaskLists'][$listID]['userTaskList'] = $userTaskList;

            $resultTasks[$userID]['userTaskLists'][$listID]['tasks'] = [];

            $listTasks = $userTaskList->getTasks();

            foreach ($listTasks as $userTask) {
                $deletedDate = $userTask->getDateDeleted();

                if (null != $deletedDate) {
                    continue;
                }
                
                if ($userTask->getTaskArchive()) {
                    continue;
                }

                $taskDate = $userTask->getTaskDateTime();
                if(empty($taskDate)) {
                    $taskDate = $userTask->getDateCreated();
                }

                $timestamp = $taskDate->format('U');

                $resultTasks[$userID]['userTaskLists'][$listID]['tasks'][$timestamp] = $userTask;
            }

            arsort($resultTasks[$userID]['userTaskLists'][$listID]['tasks']);
        }

        arsort($resultTasks);

        return $resultTasks;
    }
}