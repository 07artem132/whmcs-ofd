<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.05.2019
 * Time: 15:52
 */

namespace WHMCS\Module\Addon\ofd\Menu;

use WHMCS\Module\Addon\ofd\Configs\ModuleConfig;
use WHMCS\View\Menu\MenuFactory;

class AdminAreaMenu extends MenuFactory
{
    protected $rootItemName = "Domain Manager nav bar";

    public function navbar()
    {
        return $this->loader->load($this->buildMenuStructure($this->getNavBarStructure()));
    }

    protected function getNavBarStructure()
    {
        $menuItems = [
            [
                "name" => "index",
                "label" => 'Список счетов',
                "uri" => ModuleConfig::getModuleLink() . "&action=index",
                "order" => 1,
                "attributes" => [
                    "class" => !array_key_exists('action', $_GET) || $_GET['action'] === 'index' ? 'active' : ''
                ]
            ],
            [
                "name" => "settings",
                "label" => 'Настройки OFD.RU',
                "uri" => ModuleConfig::getModuleLink() . "&action=ofdru",
                "order" => 1,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'ofdru' ? 'active' : ''
                ]
            ],
            [
                "name" => "cron",
                "label" => 'Крон',
                "uri" => ModuleConfig::getModuleLink() . "&action=cron",
                "order" => 1,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'cron' ? 'active' : ''
                ]
            ],
            [
                "name" => "manualcheck",
                "label" => 'Отправить в ручном режиме чек',
                "uri" => ModuleConfig::getModuleLink() . "&action=manualcheck",
                "order" => 1,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'manualcheck' ? 'active' : ''
                ]
            ],
            [
                "name" => "nosendcheck",
                "label" => 'Транзакции с не сформированными чеками',
                "uri" => ModuleConfig::getModuleLink() . "&action=nosendcheck",
                "order" => 1,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'nosendcheck' ? 'active' : ''
                ]
            ],[
                "name" => "log",
                "label" => 'Лог',
                "uri" => ModuleConfig::getModuleLink() . "&action=log",
                "order" => 1,
                "attributes" => [
                    "class" => array_key_exists('action', $_GET) && $_GET['action'] === 'log' ? 'active' : ''
                ]
            ],
        ];

        return $menuItems;
    }

}


