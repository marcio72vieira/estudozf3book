<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Regex;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Application\Route\StaticRoute;

return [
    'router' => [
        'routes' => [
            'estudo' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/estudo',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'estudo',
                    ],
                ],
            ],
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'about' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'about',
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
            // Add this route for the DownloadController
            'download' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/download[/:action]',
                    'defaults' => [
                        'controller'    => Controller\DownloadController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'barcode' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/barcode[/:type/:label]',
                    'constraints' => [     
                        'type' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'label' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'barcode',
                    ],
                ],
            ],
            'doc' => [
                'type' => Regex::class,
                'options' => [
                    'regex'    => '/doc(?<page>\/[a-zA-Z0-9_\-]+)\.html',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'doc',
                    ],
                    'spec'=>'/doc/%page%.html'
                ],
            ],
            'static' => [
                'type' => StaticRoute::class,
                'options' => [
                    'dir_name'         => __DIR__ . '/../view',
                    'template_prefix'  => 'application/index/static',
                    'filename_pattern' => '/[a-z0-9_\-]+/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'static',
                    ],                    
                ],
            ],
            //Rota criada para utilização de um segundo layout
            'administrator' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/administrator',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'administrator',
                    ],
                ],
            ],
            //Rota criada para utilização do partial-demo
            'partial-demo' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/partial-demo',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'partial-demo',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\DownloadController::class => InvokableFactory::class,
            Controller\EstudoController::class => InvokableFactory::class
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
        //Determina em qual diretório serão encontrados os arquivos de template
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        /*
        //Determinando a estrategia de visualização utilizado apenas com o JasonIndexController.php
        'strategies' => [
            'ViewJsonStrategy',
        ],
        */
    ],
];
