<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Helpers\FlashMessages;
use Application\Helpers\RenderMessages;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Application\Helpers\GetCurrentUser;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
                'AuthStorage' => function ($sm) {
                    return new \Application\Helpers\AuthStorage();
                },
                'AuthService' => function ($sm) {
                    $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                    $doctrineAuthAdapter = new ObjectRepository(array(
                        'objectManager' => $entityManager,
                        'identityClass' => 'Application\Entity\User',
                        'identityProperty' => 'email',
                        'credentialProperty' => 'password',
                        'credentialCallable' => function ($identity, $credential) use ($sm) {
                            return md5($credential) == $identity->getPassword();
                        }
                    ));

                    $authService = new AuthenticationService();
                    $authService->setAdapter($doctrineAuthAdapter);
                    $authService->setStorage($sm->get('AuthStorage'));
                    return $authService;
                },
            ),
        );
    }

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getCurrentUser' => function ($sm) {
                    $h = new GetCurrentUser();
                    $h->setServiceLocator($sm->getServiceLocator());
                    return $h;
                },
                'renderMessages' => function ($sm) {
                    $h = new RenderMessages();
                    return $h;
                },
                'renderFlashMessages' => function () {
                    return new FlashMessages();
                },
            )
        );
    }
}
