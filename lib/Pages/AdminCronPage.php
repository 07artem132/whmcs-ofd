<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 17:21
 *
 */

namespace WHMCS\Module\Addon\ofd\Pages;

use Carbon\Carbon;
use WHMCS\Module\Addon\ofd\Configs\ModuleConfig;
use WHMCS\Module\Addon\ofd\Models\LogModel;
use WHMCS\View\Menu\MenuFactory;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;

class AdminCronPage implements PageInterface
{
    private $templateName = 'admin_cron.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['lastCronEvent']=LogModel::where('status',1)->where('module','cron')->orderBy('created_at','DESC')->first();
        $this->vars['cronPath'] = ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/cron.php';
    }

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
            'Главная' => '',
        ];
    }
}