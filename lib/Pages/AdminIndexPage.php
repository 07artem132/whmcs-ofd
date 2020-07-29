<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 17:21
 *
 */

namespace WHMCS\Module\Addon\ofd\Pages;

use Carbon\Carbon;
use WHMCS\Billing\Payment\Transaction;
use WHMCS\Module\Addon\ofd\Models\FemaCheckListsModel;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;
use WHMCS\View\Menu\MenuFactory;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;

class AdminIndexPage implements PageInterface
{
    private $templateName = 'admin_index.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['checks'] = FemaCheckListsModel::with('transactions')->get()->toArray();
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