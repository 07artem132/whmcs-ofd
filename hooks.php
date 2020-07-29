<?php

use WHMCS\Module\Addon\ofd\Configs\ModuleConfig;
use WHMCS\Module\Addon\ofd\Models\FemaCheckListsModel;

add_hook('AdminAreaHeadOutput', 99999999, function ($vars) {
    try {
        if (!isset($_GET['module']) || $_GET['module'] != ModuleConfig::getModuleName()) {
            return null;
        }
        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<link rel="stylesheet" type="text/css" href="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin/' . $item . '">';
        }

        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<script type="text/javascript" charset="utf8" src="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin/' . $item . '"></script>';
        }
    } catch (Exception $e) {
        logActivity(ModuleConfig::getModuleName() . ' [AdminAreaHeadOutput]:' . $e->getMessage(), 0);
    }
});

add_hook('ClientAreaPage', 1, function ($vars) {
    foreach ($vars['transactions'] as &$transaction) {
        $check = FemaCheckListsModel::where('order_id', '=', $transaction['transid'])->first();
        if ($check != null && $check->status == 'CONFIRMED')
            $transaction['check_id'] = $check->id;
    }
    return [
        'transactions' => $vars['transactions']
    ];
});
