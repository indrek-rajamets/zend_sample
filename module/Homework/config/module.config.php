<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Homework\Controller\Student' => 'Homework\Controller\StudentController',
            'Homework\Controller\Homework' => 'Homework\Controller\HomeworkController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'student' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/student[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Homework\Controller\Student',
                        'action'     => 'index',
                    ),
                ),
            ),
            'homework' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/homework[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Homework\Controller\Homework',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'homework' => __DIR__ . '/../view',
        ),
    ),
);