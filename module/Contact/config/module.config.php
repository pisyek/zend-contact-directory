<?php

namespace Contact;

use Contact\Controller\ContactController;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'contact' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => ContactController::class,
                        'action' => 'index',
                    ]
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'contact' => __DIR__ . '/../view',
        ],
    ],
];