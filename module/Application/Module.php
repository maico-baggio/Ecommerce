<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
            $result = $e->getResult();
            $result->setTerminal(TRUE);

        });
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
    }

    public function mvcPreDispatch($event) {

        $di = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');
        $authService = $di->get('AuthService');

        if (!$authService->authorize( $controllerName, $actionName)){
            //throw new \Exception("Você não tem permissão para acessar este recurso!");

            $redirect = $event->getTarget()->redirect();
            return $redirect->toUrl('/application/auth/noauthorize');   
        }

        return true;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {

                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
            )
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'Application\Controller\Auth' => function ($sm) {
                    $controller = new \Application\Controller\AuthController($sm->getServiceLocator());

                    return $controller;
                },
                'Application\Controller\Index' => function ($sm) {
                    $controller = new \Application\Controller\IndexController($sm->getServiceLocator());

                    return $controller;
                },
            ),
        );
    }
}