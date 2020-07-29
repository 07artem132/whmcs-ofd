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
use WHMCS\Module\Addon\ofd\Models\LogModel;
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\Module\Addon\ofd\vendor\OFDCheck\ApiOFDCheck;

class SendTransactionToOFDRU implements TaskInterfaces
{
    private $frequency = '* * * * *';

    public $name = 'send transaction to ofd.ru';

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

        $transactions = Transaction::
        whereIn('gateway', explode(',', $settings['ofr_ru_gateway_enabled']))
            ->leftJoin('mod_addon_ofd_fema_checks_list', 'tblaccounts.transid', '=', 'mod_addon_ofd_fema_checks_list.order_id')
            //дата от начала которой мы ищем не отправленные транзакции
            ->where('date', '>', date('Y-m-d H:i:s', (time() - 3 * 24 * 60 * 60)))
            ->where('transid', '!=', '')
            ->where(function ($query) {
                $query->whereNull('order_id');
            })
            //->first();
            ->get();
        $message = sprintf('Необходимо отправаить информацию о %s транзакциях' . PHP_EOL, $transactions->count());
        LogController::addSuccess(__CLASS__, $message);
        echo $message;

        if ($transactions->count() == 0)
            return;

        ApiOFDCheck::init();
        foreach ($transactions as $transaction) {

            if ($transaction->invoiceid != 0)
                $type = 'Income';
            elseif ($transaction->refundid != 0)
                $type = 'IncomeReturn';
            else
                $type='unknown';

            $message = sprintf('Отправка транзакции %s тип %s' . PHP_EOL, $transaction->transid, $type);
            echo $message;
            try {
                LogController::addSuccess(__CLASS__, $message);
                ApiOFDCheck::sendInfoToOFD($transaction->transid);
            } catch (\Throwable $e) {
                sendAdminNotification("system",'OFD ERROR',$e->getMessage());
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}