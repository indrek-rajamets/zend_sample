<?php
/**
 * Created by JetBrains PhpStorm.
 * User: indrek
 * Date: 15.02.13
 * Time: 13:07
 */
namespace Homework;

use Homework\Model\Student;
use Homework\Model\StudentTable;
use Homework\Model\Homework;
use Homework\Model\HomeworkTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Homweork\Model\StudentTable' =>  function($sm) {
                    $tableGateway = $sm->get('StudentTableGateway');
                    $table = new StudentTable($tableGateway);
                    return $table;
                },
                'StudentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Student());
                    return new TableGateway('student', $dbAdapter, null, $resultSetPrototype);
                },
                'Homweork\Model\HomeworkTable' =>  function($sm) {
                    $tableGateway = $sm->get('HomeworkTableGateway');
                    $table = new HomeworkTable($tableGateway);
                    return $table;
                },
                'HomeworkTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Homework());
                    return new TableGateway('homework', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
