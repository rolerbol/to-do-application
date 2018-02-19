<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AuthController;
use Zend\Authentication\AuthenticationService;
use Application\Form\LoginForm;
use Application\Form\RegisterForm;
use Zend\View\Renderer\PhpRenderer;
use Application\Service\RegisterService;

/**
 * The factory responsible for creating of authentication service.
 */
class AuthControllerFactory implements FactoryInterface
{
    /**
     * This method creates the \Application\View\AclHelper service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = new AuthController();
        $loginForm = $container->get(LoginForm::class);
        $registerForm = $container->get(RegisterForm::class);
        $phpRenderer = $container->get(PhpRenderer::class);
        $registerService = $container->get(RegisterService::class);
        
        $instance->setLoginForm($loginForm);
        $instance->setRegisterForm($registerForm);
        $instance->setPhpRenderer($phpRenderer);
        $instance->setRegisterService($registerService);
        
        $instance->setModuleManager($container->get('ModuleManager'));
        
        return $instance;
    }
}