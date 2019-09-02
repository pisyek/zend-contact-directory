<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Model\Contact;
use Contact\Model\ContactTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 *
 * @package Contact
 * @author Hafiz "Pisyek" Suhaimi <hi@pisyek.com>
 * @copyright 2019 Pisyek Studios
 * @link www.pisyek.com
 */
class Module implements ConfigProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                ContactTable::class => function($container) {
                    $tableGateway = $container->get(Model\ContactTableGateway::class);
                    return new ContactTable($tableGateway);
                },
                Model\ContactTableGateway::class => function($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Contact());
                    return new TableGateway('contact', $dbAdapter, null, $resultSetPrototype);
                }
            ],
        ];
    }

    /**
     * @return array
     */
    public function getControllerConfig()
    {
        return [
            'factories' => [
                ContactController::class => function($container) {
                    return new ContactController(
                        $container->get(ContactTable::class)
                    );
                }
            ],
        ];
    }
}