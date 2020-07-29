<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 17:21
 *
 */

namespace WHMCS\Module\Addon\ofd\Pages;

use Carbon\Carbon;
use WHMCS\Module\Addon\ofd\Controllers\LogController;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;
use WHMCS\View\Menu\MenuFactory;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;
use WHMCS\Billing\Payment\Transaction;

class AdminManualcheckPage implements PageInterface
{
    private $templateName = 'admin_manual_check.tpl';
    private $vars = [];

    function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            try {
                LogController::addSuccess(__CLASS__, 'Ручная отправка транзакции, adminid->' . $_SESSION['adminid']);
                ApiOFDCheck::init();
                ApiOFDCheck::sendInfoToOFD($_POST['transaction_id']);
            } catch (\Exception $e) {
                dump($e);
            }
        }
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