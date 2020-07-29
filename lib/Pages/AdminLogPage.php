<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 05.01.2020, 18:04
 *
 */

namespace WHMCS\Module\Addon\ofd\Pages;

use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;
use WHMCS\Module\Addon\ofd\Models\LogModel;
use WHMCS\View\Menu\MenuFactory;

class AdminLogPage implements PageInterface
{
    private $templateName = 'admin_log.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['logs'] = LogModel::orderBy('id', 'desc')->get();
    }

    /**
     * @return string
     */
    function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return array
     */
    function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return MenuFactory|null
     */
    function getSubMenu(): ?MenuFactory
    {
        return null;
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        return [
            'Главная' => 'addonmodules.php?module=DomainManager',
            'Лог' => '',
        ];
    }
}