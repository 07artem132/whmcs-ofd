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
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;
use WHMCS\View\Menu\MenuFactory;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;

class AdminNosendcheckPage implements PageInterface
{
    private $templateName = 'admin_no_send_check.tpl';
    private $vars = [];

    function __construct()
    {
        $settings = SettingModel::where('key', 'like', 'ofr_ru_%')
            ->get()
            ->keyBy('key')
            ->transform(function ($item, $key) {
                return $item->val;
            })->toArray();

        $this->vars['transactions'] = Transaction::
        whereIn('gateway', explode(',', $settings['ofr_ru_gateway_enabled']))
            ->leftJoin('mod_addon_ofd_fema_checks_list', 'tblaccounts.transid', '=', 'mod_addon_ofd_fema_checks_list.order_id')
            //дата от начала которой мы ищем не отправленные транзакции
            ->where('date', '>', date('Y-m-d H:i:s', (time() - 3 * 24 * 60 * 60)))
            ->where('transid', '!=', '')
            ->where(function ($query) {
                $query->where('status', 'NOT LIKE', 'CONFIRMED')->orWhereNull('status');
            })
            ->get();
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