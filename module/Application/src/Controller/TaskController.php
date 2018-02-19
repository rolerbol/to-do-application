<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AddTaskForm;
use Application\Service\TaskService;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\JsonModel;
use Application\Form\AddTaskListForm;
use Application\Form\EditTaskForm;
use Application\Service\TaskListService;

class TaskController extends AbstractActionController
{
    /**
     * @var PhpRenderer
     */
    protected $phpRenderer;
    
    /**
     * @var AddTaskForm
     */
    private $addTaskForm;

    /**
     * @var EditTaskForm
     */
    private $editTaskForm;
    
    /**
     * @var AddTaskListForm
     */
    private $addTaskListForm;
    
    /**
     * @var TaskService
     */
    private $taskService;
    
    /**
     * @var TaskListService
     */
    private $taskListService;
    
    /**
     * @param PhpRenderer $phpRenderer
     * @return AuthController
     */
    public function setPhpRenderer(PhpRenderer $phpRenderer)
    {
        $this->phpRenderer = $phpRenderer;
        return $this;
    }
    
    /**
     * @param AddTaskForm $addTaskForm
     * @return TaskController
     */
    public function setAddTaskForm(AddTaskForm $addTaskForm)
    {
        $this->addTaskForm = $addTaskForm;
        return $this;
    }

    /**
     * @param EditTaskForm $editTaskForm
     * @return TaskController
     */
    public function setEditTaskForm(EditTaskForm $editTaskForm)
    {
        $this->editTaskForm = $editTaskForm;
        return $this;
    }
    
    /**
     * @param AddTaskListForm $addTaskListForm
     * @return TaskController
     */
    public function setAddTaskListForm(AddTaskListForm $addTaskListForm)
    {
        $this->addTaskListForm = $addTaskListForm;
        return $this;
    }
    
    /**
     * @param TaskService $taskService
     * @return TaskController
     */
    public function setTaskService(TaskService $taskService)
    {
        $this->taskService = $taskService;
        return $this;
    }
    
    /**
     * @param TaskListService $taskListService
     * @return TaskController
     */
    public function setTaskListService(TaskListService $taskListService)
    {
        $this->taskListService = $taskListService;
        return $this;
    }
    
    public function listAction()
    {
        return new ViewModel();
    }
    
    public function userTasksAction()
    {
        $identity = $this->getAuthService()->getIdentity();
        
        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }
        
        $userID = $identity['userID'];
        
        $userTaskLists = $this->taskListService->getUserTaskLists($userID);
        
        $viewModel = new ViewModel();
        
//        $addTaskView = $this->forward()->dispatch(self::class, ['action' => 'add-task']);
        
//        $viewModel->addChild($addTaskView, 'addTaskView');
        $viewModel->setVariable('userTaskList', $userTaskLists);
        
        return $viewModel;
    }
    
    public function addTaskAction()
    {
        $identity = $this->getAuthService()->getIdentity();
        
        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }
        
        $userTaskListID = $this->params()->fromRoute('userTaskListID');
        
        $userID = $identity['userID'];
        
        $viewModel = new ViewModel();
        
        $viewModel->setVariable('userTaskListID', $userTaskListID);
        
        $addTaskForm = $this->addTaskForm;
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $addTaskForm->setData($postData);
            
            if ($addTaskForm->isValid()) {
                
                $successCreated = $this->taskService->createToDoTask(
                    [
                        'taskText' => $addTaskForm->get('taskText')->getValue(),
                        'taskUserID' => $userID,
                        'userTaskListID' => $userTaskListID
                    ]
                );
                
                if (!$successCreated) {
                    $this->flashMessenger()->addMessage("Oops, that's error with creation!", 'error', 0);
                } else {
                    $this->flashMessenger()->addMessage("Task was successfully created!", 'success', 1);
                    $jsonModel = new JsonModel();
                    $jsonModel->setTerminal(true);
                    $jsonModel->setTemplate(null);
                    $jsonModel->setVariable('status', 'success');
                    $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
                    return $jsonModel;
                }
            }
        }
        
        $viewModel->setVariable('form', $addTaskForm);
        
//        if ($this->getRequest()->isXmlHttpRequest()) {
            $phpRenderer = $this->phpRenderer;
            
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('application/task/add-task.phtml');
            $viewModel->setVariable('ajaxRequest', true);
            
            $content = $phpRenderer->render($viewModel);
            
            $jsonModel = new JsonModel();
//            $jsonModel->setTerminal(true);
            $jsonModel->setTemplate(null);
            $jsonModel->setVariable('status', 'success');
            $jsonModel->setVariable('content', $content);
            
            return $jsonModel;
//        }
        
//        return $viewModel;
    }

    public function editTaskAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $taskID = $this->params()->fromRoute('taskID');

        $userID = $identity['userID'];

        if (empty($taskID)) {
            return $this->redirect()->toRoute('user.tasks');
        }

        try {
            $taskRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\Task::class);

            $task = $taskRepo->findOneBy(['taskID' => $taskID]);

            if (empty($task)) {
                return $this->redirect()->toRoute('user.tasks');
            }
        } catch (\Throwable $th) {
            return $this->redirect()->toRoute('user.tasks');
        }

        $viewModel = new ViewModel();

        $viewModel->setVariable('taskID', $taskID);

        $editTaskForm = $this->editTaskForm;

        $editTaskForm->get('taskText')->setValue($task->getTaskText());
        $editTaskForm->get('taskStatus')->setValue($task->getTaskStatus());
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $editTaskForm->setData($postData);

            if ($editTaskForm->isValid()) {

                $task->setTaskText($editTaskForm->get('taskText')->getValue());
                $task->setTaskStatus($editTaskForm->get('taskStatus')->getValue());
                $task->setDateModified(new \DateTime());

                try {
                    $this->getEntityManager()->persist($task);
                    $this->getEntityManager()->flush();
                    $this->flashMessenger()->addMessage("Task was successfully edited!", 'success', 1);
                    $jsonModel = new JsonModel();
                    $jsonModel->setTerminal(true);
                    $jsonModel->setTemplate(null);
                    $jsonModel->setVariable('status', 'success');
                    $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
                    return $jsonModel;
                } catch (\Throwable $th) {
                    $this->flashMessenger()->addMessage("Oops, that's error with editing!", 'error', 0);
                }
            }
        }

        $viewModel->setVariable('form', $editTaskForm);
        $phpRenderer = $this->phpRenderer;

        $viewModel->setTerminal(true);
        $viewModel->setTemplate('application/task/edit-task.phtml');
        $viewModel->setVariable('ajaxRequest', true);

        $content = $phpRenderer->render($viewModel);

        $jsonModel = new JsonModel();

        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('content', $content);

        return $jsonModel;
    }

    public function archiveTaskAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $taskID = $this->params()->fromRoute('taskID');

        if (empty($taskID)) {
            return $this->redirect()->toRoute('user.tasks');
        }

        try {
            $taskRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\Task::class);

            $task = $taskRepo->findOneBy(['taskID' => $taskID]);

            if (empty($task)) {
                return $this->redirect()->toRoute('user.tasks');
            }

            $task->setTaskArchive('1');

            $task->setDateModified(new \DateTime());
            
            $this->getEntityManager()->persist($task);

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage("Task was successfully archived!", 'success', 1);
        } catch (\Throwable $th) {
            $this->flashMessenger()->addMessage("Oops, that's error with archiving!", 'error', 1);
        }

//        $userID = $identity['userID'];

        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);
        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
        return $jsonModel;
    }

    public function removeTaskAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $taskID = $this->params()->fromRoute('taskID');

        if (empty($taskID)) {
            return $this->redirect()->toRoute('user.tasks');
        }

        try {
            $taskRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\Task::class);

            $task = $taskRepo->findOneBy(['taskID' => $taskID]);

            if (empty($task)) {
                return $this->redirect()->toRoute('user.tasks');
            }
            
            $task->setDateDeleted(new \DateTime());

            $this->getEntityManager()->persist($task);

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage("Task was successfully removed!", 'success', 1);
        } catch (\Throwable $th) {
            $this->flashMessenger()->addMessage("Oops, that's error with removing!", 'error', 1);
        }
        
//        $userID = $identity['userID'];

        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);
        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
        return $jsonModel;
    }
}
