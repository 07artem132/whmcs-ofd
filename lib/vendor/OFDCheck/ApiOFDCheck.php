<?php

namespace WHMCS\Module\Addon\ofd\vendor\OFDCheck;

use DateTimeZone;
use ErrorException;
use WHMCS\Billing\Payment\Transaction;
use WHMCS\Exception;
use WHMCS\Module\Addon\ofd\Controllers\LogController;
use WHMCS\Module\Addon\ofd\Models\FemaCheckListsModel;
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\User\Client;
use WHMCS\Billing\Invoice\item;
use WHMCS\Order\Order;

class ApiOFDCheck
{
    // auth
    protected static $url = null;
    protected static $account = null;
    protected static $key = null;
    protected static $AuthToken = null;
    protected static $ExpirationDateUtc = null;

    // data
    public static $Inn = null;
    public static $TaxationSystem = null;
    public static $Vat = null;
    public static $PaymentType = null;
    public static $PaymentMethod = null;
    public static $AllowGateway = null;
    public static $CollapseName = null;
    public static $Collapse = null;

    public static function init()
    {
        $result = SettingModel::where('key', 'like', 'ofr_ru_%')->get()->keyBy('key')->transform(function ($item, $key) {
            return $item->val;
        })->toArray();

        if ($result['ofr_ru_login'] == 'fermatest1') {
            self::$url = "https://ferma-test.ofd.ru/api";
        } else {
            self::$url = "https://ferma.ofd.ru/api";
        }

        self::$account = $result['ofr_ru_login'];
        self::$key = $result['ofr_ru_password'];

        self::$Inn = $result['ofr_ru_inn'];
        self::$TaxationSystem = $result['ofr_ru_TaxationSystem'];
        self::$Vat = $result['ofr_ru_vat'];
        self::$PaymentType = $result['ofr_ru_payment_type'];
        self::$PaymentMethod = $result['ofr_ru_payment_method'];
        self::$AllowGateway = explode(',', $result['ofr_ru_gateway_enabled']);
        self::$CollapseName = $result['ofr_ru_collapse_name'];
        self::$Collapse = $result['ofr_ru_collapse'];

        if (array_key_exists('ofr_ru_auth_token', $result)) {
            self::$AuthToken = $result['ofr_ru_auth_token'];
            self::$ExpirationDateUtc = $result['ofr_ru_auth_token_exp_date'];
        }
    }

    public static function sendInfoToOFD($transaction_id)
    {
        try {
            $data = self::prepareDataForOFD($transaction_id);
            $result = self::sendDataToOFD(self::getHTTPOpt($data));
            $transaction = Transaction::where('transid', '=', $transaction_id)->first();
            $check = new FemaCheckListsModel();
            $check->id = $result;
            $check->type = $data['Request']['Type'];
            $check->order_id = $transaction_id;
            $check->total = (float)$transaction->amountin;
            $check->saveOrFail();
        } catch (\Throwable $e) {
            LogController::addError(__CLASS__, 'Ошибка при отправке чека в ofd.ru', $e);
            throw new \Exception($e->getMessage());
            //todo send to email
        }
    }

    public static function getInfoFromOFD($check_id)
    {
        try {
            $result = self::UpdateNewCheckStatus($check_id);
            $check = FemaCheckListsModel::findOrFail($check_id);
            $check->status = $result->StatusName;
            $check->status_message = $result->StatusMessage;
            if ($result->Device == null) {
                LogController::addError(__CLASS__, 'Ошибка при получении чека от ofd.ru, device null', new Exception('device null'));
                $check->saveOrFail();
                throw new \Exception('device null');

            }
            $check->fn = $result->Device->FN;
            $check->rnm = $result->Device->RNM;
            $check->fpd = $result->Device->FPD;
            $check->fdn = $result->Device->FDN;
            $check->saveOrFail();
            return $result;
        } catch (\Throwable $e) {
            LogController::addError(__CLASS__, 'Ошибка при получении чека от ofd.ru', $e);
            throw new \Exception($e->getMessage());
            //todo send to email
        }
    }

    public static function UpdateNewCheckStatus($check_id)
    {
        $data = array();
        $data['Request']['ReceiptId'] = $check_id;

        if (self::setAuthToken()) {
            $options = self::getHTTPOpt($data);
            LogController::addSuccess(__CLASS__, 'Начата отправка данных: ' . json_encode($options));
            $context = stream_context_create($options);
            set_error_handler(array(self::class, 'customErrorHandler'));
            try {
                $result = file_get_contents(self::$url . "/kkt/cloud/status?AuthToken=" . self::$AuthToken, false, $context);
                LogController::addSuccess(__CLASS__, 'Получен ответ: ' . json_encode($result));
            } catch (Exception $e) {
                LogController::addError(__CLASS__, 'Произошла ошибка при обновлении статуса чека', $e);
                throw new $e;
            }
            restore_error_handler();
            $result = json_decode($result);
            if (isset($result->Status) && ($result->Status == 'Success')) {
                return $result->Data;
            } else if (isset($result->Status) && ($result->Status == 'Failed')) {
                LogController::addError(__CLASS__, 'Произошла ошибка при обновлении статуса чека', new \Exception(json_encode($result)));
                throw new \Exception($result->Error->Message);
            } else {
                LogController::addError(__CLASS__, 'Произошла ошибка при обновлении статуса чека', new \Exception(json_encode($result)));
                throw new \Exception('some error');
            }
        }
        throw new \Exception('some error');
    }


    private static function sendDataToOFD($options)
    {
        if (self::setAuthToken()) {
            LogController::addSuccess(__CLASS__, 'Начата отправка данных: ' . json_encode($options));
            $context = stream_context_create($options);
            set_error_handler(array(self::class, 'customErrorHandler'));
            try {
                $result = file_get_contents(self::$url . "/kkt/cloud/receipt?AuthToken=" . self::$AuthToken, false, $context);
                LogController::addSuccess(__CLASS__, 'Получен ответ: ' . json_encode($result));
            } catch (\Exception $e) {
                restore_error_handler();
                LogController::addError(__CLASS__, 'Ошибка при отправке чека в ofd.ru', $e);
                throw  new $e;
            }
            $result = json_decode($result);
            if (isset($result->Status) && ($result->Status == 'Success')) {
                return $result->Data->ReceiptId;
            } else if (isset($result->Status) && ($result->Status == 'Failed')) {
                LogController::addError(__CLASS__, 'Вернулся ответ со статусом "Failed"', new \Exception(json_encode($result)));
                throw new \Exception($result->Error->Message);
            } else {
                LogController::addError(__CLASS__, 'Произошла не известная ошибка при отправке данных в ofd.ru', new \Exception(json_encode($result)));
                throw new \Exception('some error');
            }
        }
        LogController::addError(__CLASS__, 'Произошла не известная ошибка при отправке данных в ofd.ru');
        throw new \Exception('some error');
    }

    private static function checkToken()
    {
        if (self::$AuthToken && (self::$ExpirationDateUtc > (time() - 10))) {
            return self::$AuthToken;
        } else {
            return false;
        }
    }

    private static function setAuthToken()
    {
        if (self::checkToken()) {
            return true;
        }
        $data = array(
            "Login" => self::$account,
            "Password" => self::$key,
        );
        $options = self::getHTTPOpt($data);
        LogController::addSuccess(__CLASS__, 'Начата отправка данных: ' . json_encode($options));
        $context = stream_context_create($options);
        set_error_handler(array(self::class, 'customErrorHandler'));
        try {
            $result = file_get_contents(self::$url . '/Authorization/CreateAuthToken', false, $context);
            LogController::addSuccess(__CLASS__, 'Получен ответ: ' . json_encode($result));
        } catch (Exception $e) {
            restore_error_handler();
            LogController::addError(__CLASS__, 'Произошла ошибка при получении токена', $e);
            throw new $e;
        }
        $result = json_decode($result);
        if (isset($result->Status) && ($result->Status == 'Success')) {
            self::$AuthToken = $result->Data->AuthToken;
            self::$ExpirationDateUtc = strtotime($result->Data->ExpirationDateUtc);
            LogController::addSuccess(__CLASS__, 'Токен успешно создан');
            return true;
        } else if (isset($result->Status) && ($result->Status == 'Failed')) {
            LogController::addError(__CLASS__, 'Произошла ошибка при получении токена', new \Exception(json_encode($result)));
            throw new \Exception($result->Error->Message);
        } else {
            LogController::addError(__CLASS__, 'Произошла ошибка при получении токена', new \Exception(json_encode($result)));
            throw new \Exception('some error');
        }
    }

    private static function getHTTPOpt($data)
    {
        $options = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
            'http' => array(
                'timeout' => 10,
                'ignore_errors' => true,
                'content' => json_encode($data),
                'header' => "Content-type: application/json\r\n" .
                    "Accept: application/json" . "\r\n",
                "Content-Length: " . strlen(json_encode($data)) . "\r\n",
                'method' => 'POST',
            )
        );
        return $options;
    }

    private static function prepareDataForOFD($transaction_id)
    {
        $data = array();
        $transaction = Transaction::where('transid', '=', $transaction_id)->firstOrFail();
        if ($transaction->invoiceid != 0)
            $type = 'Income';
        elseif ($transaction->refundid != 0)
            $type = 'IncomeReturn';
        $invoice = $transaction->invoice()->first();
        $invoiceItems = $invoice->items()->get();
        $client = $transaction->client()->first();
        $data['Request']['Inn'] = self::$Inn;
        $data['Request']['Type'] = $type;
        $data['Request']['InvoiceId'] = $transaction_id . '-' . $type;
        $data['Request']['LocalDate'] = date('Y-m-d\TH:i:s');
        $data['Request']['CustomerReceipt'] = array(
            'TaxationSystem' => self::$TaxationSystem,
            'Email' => $client->email,
            'PaymentType' => (int)self::$PaymentType,
            'Items' => array(),
        );
        foreach ($invoiceItems as $item_data) {
            $product_name = $item_data->description;
            $product_price = $item_data->amount;
            $item_quantity = 1;
            $item_total = $item_data->amount;
            array_push($data['Request']['CustomerReceipt']['Items'],
                array(
                    'Label' => $product_name,
                    'Price' => (float)$product_price,
                    'Quantity' => $item_quantity,
                    'Amount' => (float)$item_total,
                    'Vat' => self::$Vat,
                    'PaymentMethod' => self::$PaymentMethod,
                )
            );
        }

        if ((int)self::$Collapse) {
            $data['Request']['CustomerReceipt']['Items'] = [
                [
                    'Label' => self::$CollapseName,
                    'Price' => (float)$transaction->amountin,
                    'Quantity' => 1,
                    'Amount' => (float)$transaction->amountin,
                    'PaymentMethod' => self::$PaymentMethod,
                    'Vat' => self::$Vat,
                ]
            ];
        }
        return $data;
    }

    public static function customErrorHandler($errno, $errstr, $errfile, $errline, array $errcontext)
    {
        if (0 === error_reporting()) {
            return false;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}