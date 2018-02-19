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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Application\Service\TaskListService;

class TaskListController extends AbstractActionController
{
    /**
     * @var TaskListService
     */
    private $taskListService;
    
    /**
     * @var PhpRenderer
     */
    protected $phpRenderer;

    /**
     * @var AddTaskListForm
     */
    private $addTaskListForm;

    /**
     * @var TaskService
     */
    private $taskService;

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

    public function addTaskListAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $userID = $identity['userID'];

        $viewModel = new ViewModel();

        $addTaskListForm = $this->addTaskListForm;

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $addTaskListForm->setData($postData);

            if ($addTaskListForm->isValid()) {

                $successCreated = $this->taskListService->createTaskList(
                    [
                        'name' => $addTaskListForm->get('name')->getValue(),
                        'userID' => $userID,
                    ]
                );

                if (!$successCreated) {
                    $this->flashMessenger()->addMessage("Oops, that's error with creation!", 'error', 0);
                } else {
                    $this->flashMessenger()->addMessage("Task list was successfully created!", 'success', 1);
                    $jsonModel = new JsonModel();
                    $jsonModel->setTerminal(true);
                    $jsonModel->setTemplate(null);
                    $jsonModel->setVariable('status', 'success');
                    $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
                    return $jsonModel;
                }
            }
        }

        $viewModel->setVariable('form', $addTaskListForm);

//        if ($this->getRequest()->isXmlHttpRequest()) {
            $phpRenderer = $this->phpRenderer;

            $viewModel->setTerminal(true);
            $viewModel->setTemplate('application/task-list/add-task-list.phtml');
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

    public function removeTaskListAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $userTaskListID = $this->params()->fromRoute('userTaskListID');

        if (empty($userTaskListID)) {
            return $this->redirect()->toRoute('user.index');
        }
        
        try {
            $userTaskListRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class);

            $userTaskList = $userTaskListRepo->findOneBy(['userTaskListID' => $userTaskListID]);

            $userTaskList->setDeleteRequest('1');
            $userTaskList->setDateModified(new \DateTime());

            $this->getEntityManager()->persist($userTaskList);

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage("Task list remove request is sent!", 'success', 1);
        } catch (\Throwable $th) {
            // to do
            $this->flashMessenger()->addMessage("Oops, that's error with sent remove request!", 'error', 1);
        }

        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);
        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
        return $jsonModel;
    }

    public function archiveTaskListAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $userTaskListID = $this->params()->fromRoute('userTaskListID');

        if (empty($userTaskListID)) {
            return $this->redirect()->toRoute('user.index');
        }

        try {
            $userTaskListRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class);

            $userTaskList = $userTaskListRepo->findOneBy(['userTaskListID' => $userTaskListID]);

            $userTaskList->setArchive('1');
            
            $userTaskList->setDateModified(new \DateTime());

            $this->getEntityManager()->persist($userTaskList);

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage("Task list was successfully archived!", 'success', 1);
        } catch (\Throwable $th) {
            // to do
            $this->flashMessenger()->addMessage("Oops, that's error with archiving!", 'error', 1);
        }

        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);
        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
        return $jsonModel;
    }
    
    public function confirmDeleteTaskListAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $userTaskListID = $this->params()->fromRoute('userTaskListID');

        if (empty($userTaskListID)) {
            return $this->redirect()->toRoute('user.index');
        }

        try {
            $userTaskListRepo = $this->getEntityManager()->getRepository(\Application\Model\Entity\UserTaskList::class);

            $userTaskList = $userTaskListRepo->findOneBy(['userTaskListID' => $userTaskListID]);

            $userTaskList->setDeleteRequest('0');
            
            $userTaskList->setDateDeleted(new \DateTime());

            $this->getEntityManager()->persist($userTaskList);

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage("Task list was successfully deleted!", 'success', 1);
        } catch (\Throwable $th) {
            // to do
            $this->flashMessenger()->addMessage("Oops, that's error with deleting!", 'error', 1);
        }

        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);
        $jsonModel->setTemplate(null);
        $jsonModel->setVariable('status', 'success');
        $jsonModel->setVariable('redirectUrl', $this->url()->fromRoute('user.tasks'));
        return $jsonModel;
    }

    public function exportTaskListAction()
    {
        $identity = $this->getAuthService()->getIdentity();

        if (empty($identity) || empty($identity['userID'])) {
            return $this->redirect()->toRoute('user.index');
        }

        $userTaskListID = $this->params()->fromRoute('userTaskListID');

        if (empty($userTaskListID)) {
            return $this->redirect()->toRoute('user.index');
        }

        $userID = $identity['userID'];
        
        try {
            $userTaskLists = $this->taskListService->getUserTaskLists($userID);

            if (empty($userTaskLists[$userTaskListID])) {
                throw new \Exception('User task list is empty');
            }
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $tasks = $userTaskLists[$userTaskListID]['tasks'];

            $aCell = 'A';
            $bCell = 'B';
            $cCell = 'C';
            $dCell = 'D';
            $count = '1';

            $sheet->setCellValue($aCell . $count, 'Date');
            $sheet->setCellValue($bCell . $count, 'Task');
            $sheet->setCellValue($cCell . $count, 'Status');
            $sheet->setCellValue($dCell . $count, 'Type');

            $count++;
            
            foreach ($tasks as $task) {
                $taskDate = $task->getTaskDateTime();
                if(empty($taskDate)) {
                    $taskDate = $task->getDateCreated();
                }

                $sheet->setCellValue($aCell . $count, $taskDate->format('l d.m'));
                $sheet->setCellValue($bCell . $count, $task->getTaskText());
                $sheet->setCellValue($cCell . $count, $task->getTaskStatus());
                $sheet->setCellValue($dCell . $count, $task->getTaskType()->getTaskTypeName());

                $count++;
            }
            
            $userTaskList = $userTaskLists[$userTaskListID]['userTaskList'];
                     
            $fileName = $userTaskList->getName() . '.xlsx';

            $fullPathName = __DIR__ .
                    DIRECTORY_SEPARATOR 
                    . '..'. DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR
                    .'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                    .'data'.DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR . $fileName;
            $writer = new Xlsx($spreadsheet);
            $writer->save($fullPathName);

            // Get file size in bytes
            $fileSize = filesize($fullPathName);

            // Write HTTP headers
            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine(
                     "Content-type: application/vnd.ms-excel");
            $headers->addHeaderLine(
                     "Content-Disposition: attachment; filename=\"" .
                    $fileName . "\"");
            $headers->addHeaderLine("Content-length: $fileSize");
            $headers->addHeaderLine("Cache-control: private");

            // Write file content
            $fileContent = file_get_contents($fullPathName);
            unlink($fullPathName);
            if($fileContent != false) {
                $response->setContent($fileContent);
            } else {
                // Set 500 Server Error status code
                $this->getResponse()->setStatusCode(500);
                return;
            }

            return $this->getResponse();
        } catch (\Throwable $th) {
            $this->flashMessenger()->addMessage("Oops, that's error with export list!", 'error', 1);
            return $this->redirect()->toRoute('user.index');
        }
    }
}
