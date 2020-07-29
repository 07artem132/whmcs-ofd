<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 17.02.2020, 14:57
 *
 */

namespace WHMCS\Module\Addon\ofd\Tasks;

use WHMCS\Billing\Payment\Transaction;
use WHMCS\Module\Addon\ofd\Controllers\LogController;
use WHMCS\Module\Addon\ofd\Interfaces\TaskInterfaces;
use WHMCS\Module\Addon\ofd\Models\FemaCheckListsModel;
use WHMCS\Module\Addon\ofd\Models\LogModel;
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;

class GetTransactionInfoFromOFDRU implements TaskInterfaces
{
    private $frequency = '* * * * *';

    public $name = 'get check info from ofd.ru';

    function __construct()
    {
    }

    function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     */
    function run(): void
    {
        $settings = SettingModel::where('key', 'like', 'ofr_ru_%')
            ->get()
            ->keyBy('key')
            ->transform(function ($item, $key) {
                return $item->val;
            })->toArray();

        $checks = FemaCheckListsModel::where(function ($query) {
            $query->where('status', 'NOT LIKE', 'CONFIRMED');
            $query->where('status', 'NOT LIKE', 'KKT_ERROR');
        })->orWhereNull('status')
            //->first();
            //->toSql();
            ->get();
        $message = sprintf('Необходимо получить информацию о %s чеках' . PHP_EOL, $checks->count());
        LogController::addSuccess(__CLASS__, $message);
        echo $message;

        if ($checks->count() == 0)
            return;

        ApiOFDCheck::init();
        foreach ($checks as $check) {
            $message = sprintf('Получение информации о чеке %s тип %s' . PHP_EOL, $check->id, $check->type);
            echo $message;
            try {
                LogController::addSuccess(__CLASS__, $message);
                ApiOFDCheck::getInfoFromOFD($check->id);
            } catch (\Throwable $e) {
                sendAdminNotification("system", 'OFD ERROR', $e->getMessage());
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}