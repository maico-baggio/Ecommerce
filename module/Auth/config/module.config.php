<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'index',
                        ),
                    ),
                ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'auth' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        ),
                    ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                            'defaults' => array(
                                ),
                            ),
                            'child_routes' => array(//permite mandar dados pela url
                                'wildcard' => array(
                                    'type' => 'Wildcard'
                                ),
                            ),
                        ),
                    ),
                ),
            'pessoas' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/auth/users/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller' => 'Users',
                        'action' => 'index',
                        'page' => 1,
                        ),
                    ),
                ),
            ),
        ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Auth/Entity')
                ),
            'orm_default' => array(
                'drivers' => array(
                    'Auth\Entity' => 'application_entities'
                    )
                ))),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            ),
        ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                ),
            ),
        ),
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => Controller\IndexController::class,
            'Auth\Controller\Users' => Controller\UsersController::class,
            ),
        ),
    'view_manager' => array(//the module can have a specific layout
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            ),
        'template_path_stack' => array(
            'auth' => __DIR__ . '/../view',
            ),
        ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                ),
            ),
        ),
    );
