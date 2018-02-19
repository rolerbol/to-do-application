<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\RegisterForm;
use Zend\View\Model\JsonModel;
use Zend\View\Renderer\PhpRenderer;
use Application\Service\RegisterService;
use Zend\ModuleManager\ModuleManager;
use Zend\Code\Scanner\FileScanner;

class AuthController extends AbstractActionController
{
    protected $serviceActions = array(
        'notFoundAction', 'getMethodFromAction'
    );

    /**
     * @var PhpRenderer
     */
    protected $phpRenderer;
    
    /**
     * @var LoginForm
     */
    private $loginForm;
    
    /**
     * @var RegisterForm
     */
    private $registerForm;
    
    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;
    
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
     * @param RegisterForm $registerForm
     * @return AuthController
     */
    public function setRegisterForm(RegisterForm $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }
    
    /**
     * @param RegisterService $registerService
     * @return AuthController
     */
    public function setRegisterService(RegisterService $registerService)
    {
        $this->registerService = $registerService;
        return $this;
    }
    
    /**
     * @param LoginForm $loginForm
     * @return AuthController
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    /**
     * @param \Zend\ModuleManager\ModuleManagerInterface $moduleManager
     * @return AuthController
     */
    public function setModuleManager($moduleManager)
    {
        $this->moduleManager = $moduleManager;
        return $this;
    }
    
    public function indexAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        
        $viewModel = new ViewModel();
        
        $viewModel->setTerminal(true);
        
        $loginView = $this->forward()->dispatch(self::class, ['action' => 'login']);
        
        $registerView = $this->forward()->dispatch(self::class, ['action' => 'register']);
        
        $viewModel->addChild($loginView, 'loginView');
        
        $viewModel->addChild($registerView, 'registerView');
        
        return $viewModel;
    }
    
    public function loginAction()
    {
        $viewModel = new ViewModel();
        
        $loginForm = $this->loginForm;
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $loginForm->setData($postData);
            
            if ($loginForm->isValid()) {
                $result = $this->getAuthService()->authenticate($loginForm->get('userEmail')->getValue(), $loginForm->get('userPassword')->getValue());
                
                if (!$result->isValid()) {
                    $this->flashMessenger()->addMessage("Oops, that's not a match.", 'error', 0);
                } else {
                    $jsonModel = new JsonModel();
                    $jsonModel->setTerminal(true);
                    $jsonModel->setTemplate(null);
                    $jsonModel->setVariable('status', 'success');
                    $jsonModel->setVariable('homeUrl', $this->url()->fromRoute('home'));
                    return $jsonModel;
                }
            }
        }
        
        $viewModel->setVariable('form', $loginForm);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $phpRenderer = $this->phpRenderer;
            
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('application/auth/login.phtml');
            $viewModel->setVariable('ajaxRequest', true);
            
            $content = $phpRenderer->render($viewModel);
            
            $jsonModel = new JsonModel();
            $jsonModel->setTerminal(true);
            $jsonModel->setTemplate(null);
            $jsonModel->setVariable('status', 'success');
            $jsonModel->setVariable('content', $content);
            
            return $jsonModel;
        }
        
        return $viewModel;
    }
    
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity(); 
        
        return $this->redirect()->toRoute('user.index');
    }
    
    public function registerAction()
    {
        $viewModel = new ViewModel();
        
        $registerForm = $this->registerForm;
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $registerForm->setData($postData);
            
            if ($registerForm->isValid()) {
                $registerService = $this->registerService;
                $userEmail = $registerForm->get('userEmail')->getValue();
                $userPassword = $registerForm->get('userPassword')->getValue();
                
                $registerService->setEmail($userEmail);
                $registerService->setPassword($userPassword);
                
                $resultRegister = $registerService->register();
                
                if ($resultRegister) {
                    $result = $this->getAuthService()->authenticate($userEmail, $userPassword);
                
                    if ($result->isValid()) {
                        $jsonModel = new JsonModel();
                        $jsonModel->setTerminal(true);
                        $jsonModel->setTemplate(null);
                        $jsonModel->setVariable('status', 'success');
                        $jsonModel->setVariable('homeUrl', $this->url()->fromRoute('home'));
                        return $jsonModel;
                    } else {
                        $this->flashMessenger()->addMessage("Oops, that's error with authentication!", 'error', 0);
                    }
                } else {
                    $this->flashMessenger()->addMessage("Oops, that's error with registration!", 'error', 0);
                }
            }
        }
        
        $viewModel->setVariable('form', $registerForm);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $phpRenderer = $this->phpRenderer;
            
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('application/auth/register.phtml');
            $viewModel->setVariable('ajaxRequest', true);
            
            $content = $phpRenderer->render($viewModel);
            
            $jsonModel = new JsonModel();
            $jsonModel->setTerminal(true);
            $jsonModel->setTemplate(null);
            $jsonModel->setVariable('status', 'success');
            $jsonModel->setVariable('content', $content);
            
            return $jsonModel;
        }
        
        return $viewModel;
    }

    public function rebuildAction()
    {
        $moduleNames = array_keys($this->moduleManager->getLoadedModules());

        $dirName                 = realpath(dirname(__DIR__) . '/../');
        $directory               = new \RecursiveDirectoryIterator($dirName);
        $iterator                = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
        $regex                   = new \RegexIterator($iterator, '/^.+[a-zA-Z]+Controller\.php$/i', \RecursiveRegexIterator::GET_MATCH);
        $controllerActionMethods = array();

        foreach ($regex as $name => $object) {
            foreach ($moduleNames as $moduleName) {
                if (strstr($name, "/$moduleName/")) {
                    $fs             = new FileScanner($name);
                    $className      = $fs->getClassNames($name);
                    $class          = array_shift($className);
                    $classNameSpace = $fs->getNamespaces();
                    $namespace      = array_shift($classNameSpace);
                    $refClass       = new \ReflectionClass($class);
                    if ($refClass->isAbstract()) {
                        continue;
                    }
                    foreach ($refClass->getMethods() as $method) {
                        if (in_array($method->getName(), $this->serviceActions)) {
                            continue;
                        }
                        if (preg_match('/Action$/', $method->getName())) {
                            $controllerActionMethods[] = $class . '_' . preg_replace('/Action$/', '', $method->getName());
                        }
                    }
                    break;
                }
            }
        }

        $resourceRepo        = $this->getEntityManager()->getRepository('Application\Model\Entity\AclResource');
        $databaseRows        = $resourceRepo->findBy([]);
        $databaseDummyValues = array();

        foreach ($databaseRows as $row) {
            $databaseDummyValues[] = $row->getAclResourceName();
        }

        $removeFromDatabase = array_diff($databaseDummyValues, $controllerActionMethods);
        $addInDatabase      = array_diff($controllerActionMethods, $databaseDummyValues);


        $newResources = [];
        
        foreach ($addInDatabase as $addResource) {
            $resource       = new \Application\Model\Entity\AclResource();
            $resource->setAclResourceName($addResource);
            $this->getEntityManager()->persist($resource);

            $newResources[$addResource] = $resource;
        }

        $this->getEntityManager()->flush();

        foreach ($removeFromDatabase as $removeResource) {
            $resource       = $resourceRepo->findOneBy(array('aclResourceName' => $removeResource));
            /* @var $roleResources \Doctrine\Orm\PersistentCollection */
            $roleResources  = $resource->roleResources;

            if (isset($newResources[$removeResource])) {
                $newResource = $newResources[$removeResource];
                $newRoleResources = $newResource->roleResource;

                foreach ($roleResources as $roleResource) {
                    $newRoleResource = new \Application\Model\Entity\AclRoleResource();
                    $newRoleResource->setRole($roleResource->getRole());
                    $newRoleResource->setAclResource($newResource);
                    $this->getEntityManager()->persist($newResource);
                    $newRoleResources->add($newResource);
                }

                $this->getEntityManager()->flush();
            }
            
            if ($roleResources->count() > 0) {
                $roleResources->clear();
            }

            $this->getEntityManager()->remove($resource);
        }

        $this->getEntityManager()->flush();
//        $this->flashMessenger()->addSuccessMessage('Successful rebuild');

        if (!($this->getRequest() instanceof \Zend\Console\Request)) {
            return $this->redirect()->toRoute('home');
        }
    }
}
