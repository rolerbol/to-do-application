<?php
namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Application\Listener\AuthenticationListener;
use Application\Listener\Factory\AuthenticationListenerFactory;
use Application\View\AclHelper;
use Application\View\Factory\AclHelperFactory;
use Application\Controller\Plugin\EntityManagerPlugin;
use Application\Controller\Plugin\Factory\EntityManagerPluginFactory;
use Application\Service\Factory\AuthenticationServiceFactory;
use Application\Service\AuthAdapter;
use Application\Service\Factory\AuthAdapterFactory;
use Application\Controller\Plugin\AuthServicePlugin;
use Application\Controller\Plugin\Factory\AuthServicePluginFactory;
use Application\Form\LoginForm;
use Application\Form\RegisterForm;
use Application\Service\RegisterService;
use Application\Service\Factory\RegisterServiceFactory;
use Application\Service\TaskService;
use Application\Service\Factory\TaskServiceFactory;
use Application\Form\AddTaskListForm;
use Application\Acl\DoctrineAcl;
use Application\Acl\Factory\DoctrineAclFactory;
use Application\Service\Authorization;
use Application\Service\Factory\AuthorizationFactory;
use Application\Form\EditTaskForm;
use Application\Service\TaskListService;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user.resource.rebuild'        => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/user/resource/rebuild',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'rebuild'
                    ]
                ]
            ],
            'user.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user.login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'user.logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'user.register' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/register',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
            'user.tasks' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/tasks',
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'user-tasks',
                    ],
                ],
            ],
            'tasks.list' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/tasks/list',
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'list',
                    ],
                ],
            ],
            'tasks.add' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/add[/:userTaskListID]',
                    'constraints' => [     
                        'userTaskListID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'add-task',
                    ],
                ],
            ],
            'tasks.edit' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/edit[/:taskID]',
                    'constraints' => [
                        'taskID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'edit-task',
                    ],
                ],
            ],
            'tasks.remove' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/remove[/:taskID]',
                    'constraints' => [
                        'taskID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'remove-task',
                    ],
                ],
            ],
            'tasks.archive' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/arvhice[/:taskID]',
                    'constraints' => [
                        'taskID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'archive-task',
                    ],
                ],
            ],
            'tasks.list.add' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/tasks/list/add',
                    'defaults' => [
                        'controller' => Controller\TaskListController::class,
                        'action'     => 'add-task-list',
                    ],
                ],
            ],
            'tasks.list.remove' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/list/remove[/:userTaskListID]',
                    'constraints' => [
                        'userTaskListID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskListController::class,
                        'action'     => 'remove-task-list',
                    ],
                ],
            ],
            'tasks.list.confirm.delete' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/list/confirm/delete[/:userTaskListID]',
                    'constraints' => [
                        'userTaskListID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskListController::class,
                        'action'     => 'confirm-delete-task-list',
                    ],
                ],
            ],
            'tasks.list.archive' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/list/archive[/:userTaskListID]',
                    'constraints' => [
                        'userTaskListID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskListController::class,
                        'action'     => 'archive-task-list',
                    ],
                ],
            ],
            'tasks.list.export' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tasks/list/export[/:userTaskListID]',
                    'constraints' => [
                        'userTaskListID' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\TaskListController::class,
                        'action'     => 'export-task-list',
                    ],
                ],
            ],
            'admin.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\TaskController::class => Controller\Factory\TaskControllerFactory::class,
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
            Controller\TaskListController::class => Controller\Factory\TaskListControllerFactory::class
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            EntityManagerPlugin::class => EntityManagerPluginFactory::class,
            AuthServicePlugin::class => AuthServicePluginFactory::class,
        ],
        'aliases' => [
            'getEntityManager' => EntityManagerPlugin::class,
            'getAuthService'   => AuthServicePlugin::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationListener::class => AuthenticationListenerFactory::class,
            \Zend\Authentication\AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthAdapter::class => AuthAdapterFactory::class,
            LoginForm::class => InvokableFactory::class,
            RegisterForm::class => InvokableFactory::class,
            RegisterService::class => RegisterServiceFactory::class,
            Form\AddTaskForm::class => InvokableFactory::class,
            EditTaskForm::class => InvokableFactory::class,
            TaskService::class => TaskServiceFactory::class,
            AddTaskListForm::class => InvokableFactory::class,
            DoctrineAcl::class => DoctrineAclFactory::class,
            Authorization::class => AuthorizationFactory::class,
            TaskListService::class => Service\Factory\TaskListServiceFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'aclHelper' => AclHelper::class,
        ],
        'factories' => [
            AclHelper::class => AclHelperFactory::class
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => array(
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Model/Entity'
                ],
            ),
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
];
