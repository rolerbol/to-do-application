<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\TaskListService;

class AdminController extends AbstractActionController
{
    /**
     * @var TaskListService
     */
    private $taskListService;

    /**
     * @param TaskListService $taskListService
     * @return TaskController
     */
    public function setTaskListService(TaskListService $taskListService)
    {
        $this->taskListService = $taskListService;
        return $this;
    }
    
    public function indexAction()
    {
        $allUserTaskLists = $this->taskListService->getAllUserLists();

        $viewModel = new ViewModel();

        $viewModel->setVariable('allUsersTaskLists', $allUserTaskLists);

        return $viewModel;
    }
}
