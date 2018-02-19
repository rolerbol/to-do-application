<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Application\Service\Authorization;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $event)
    {
        $container = $event->getApplication()->getServiceManager();
        
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);

        $authorizationService = $container->get(Authorization::class);
        
        $eventManager = $event->getApplication()->getEventManager();
        
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function($event) use ($authService) {
            $routeMatchName = $event->getRouteMatch()->getMatchedRouteName();
            
            $allowedRouters = [
                'user.index',
                'user.register',
                'user.login'
            ];
            
            if (!$authService->hasIdentity() && !in_array($routeMatchName, $allowedRouters)) {
                $router  = $event->getRouter();
                $url = $router->assemble([], ['name' => 'user.index']);
                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                return $response;
            }
        });
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($event) use ($authService, $authorizationService) {
//            $authorizationService->authorize($event);
            if (!$authService->hasIdentity()) {
                $router  = $event->getRouter();
                $url = $router->assemble([], ['name' => 'user.index']);
                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                return $response;
            }
        });

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($event) use ($authorizationService) {
            $authorizationService->authorize($event);
        });
    }
}
