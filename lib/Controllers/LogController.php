<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 02.02.2020, 5:31
 *
 */

namespace WHMCS\Module\Addon\ofd\Controllers;


use WHMCS\Module\Addon\ofd\Models\LogModel;

class LogController
{
    public static function addSuccess(string $module, string $action): void
    {
        $log = new LogModel();
        $log->status = 1;
        $log->module = self::namespaceToModule($module);
        $log->message = $action;
        $log->saveOrFail();
    }

    public static function addError(string $module, string $action, \Throwable $e = null): void
    {
        $log = new LogModel();
        $log->status = 0;
        $log->module = self::namespaceToModule($module);
        $log->message = $action . PHP_EOL . self::formatException($e);
        $log->saveOrFail();
    }

    private static function namespaceToModule(string $module): string
    {
        if (preg_match('/((?:\\\\{1,2}\w+|\w+\\\\{1,2})(?:\w+\\\\{0,2})+)/', $module, $matches, PREG_OFFSET_CAPTURE, 0) !== 0) {
            switch (basename(str_replace('\\', '/', $module))) {
                case 'AdminManualcheckPage':
                    return 'OFD чеки';
                case 'ApiOFDCheck':
                    return 'ofd.ru';
                case 'SendTransactionToOFDRU':
                    return 'cron';
                default:
                    return $module;
                    break;
            }
        }
        return $module;
    }

    private static function formatException(\Throwable $e = null): string
    {
        if ($e == null) return '';
        return 'message->' . $e->getMessage() . PHP_EOL .
            'trace->' . $e->getTraceAsString();
    }
}