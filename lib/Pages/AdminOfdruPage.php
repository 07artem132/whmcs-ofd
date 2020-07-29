<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 11.01.2020, 17:21
 *
 */

namespace WHMCS\Module\Addon\ofd\Pages;

use Carbon\Carbon;
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\View\Menu\MenuFactory;
use WHMCS\Module\Addon\ofd\Interfaces\PageInterface;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;
use  \WHMCS\Module\Gateway as ModuleGateway;

class AdminOfdruPage implements PageInterface
{
    private $templateName = 'admin_ofdru.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['AvailableGateways'] = (new ModuleGateway())->getAvailableGateways();
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $savePositions=$_POST;
            $savePositions['ofr_ru_gateway_enabled']=implode(',',$savePositions['ofr_ru_gateway_enabled']);
            unset($savePositions['token']);
            foreach ($savePositions as $saveKey=>$saveItem) {
                $dbItem = SettingModel::firstOrCreate(array('key' => $saveKey));
                $dbItem->val=$saveItem;
                $dbItem->save();
            }
        }
        $this->vars['settings'] = SettingModel::where('key', 'like', 'ofr_ru_%')->get()->keyBy('key')->transform(function($item,$key){
            return $item->val;
        })->toArray();

        $this->vars['settings']['ofr_ru_gateway_enabled']=explode(',',$this->vars['settings']['ofr_ru_gateway_enabled']);
        // ApiOFDCheck::init();
        // dd(ApiOFDCheck::createAuthToken());
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