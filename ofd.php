<?php

use WHMCS\Module\Addon\ofd\Configs\ModuleConfig;
use WHMCS\Module\Addon\ofd\Controllers\InstallController;
use WHMCS\Module\Addon\ofd\Controllers\PageController;
use WHMCS\Module\Addon\ofd\Controllers\UninstallController;
use WHMCS\Module\Addon\ofd\Menu\AdminAreaMenu;
use WHMCS\Module\Addon\ofd\Models\FemaCheckListsModel;
use WHMCS\Module\Addon\ofd\Models\SettingModel;
use WHMCS\Module\Addon\Setting;
use WHMCS\Module\Addon\ofd\vendor\qrcode\QRCode;

function ofd_config()
{
    $configarray = [
        "name" => "Чеки онлайн-кассы в ОФД",
        "description" => "Данное дополнение позволяет интегрировать whmcs счета с чеками онлайн-касс в ОФД",
        "version" => "1",
        "author" => "service-voice",
        "fields" => [
            "DeleteTableWhenDisabled" => [
                "FriendlyName" => "Удалять данные модуля при отключении ?",
                "Type" => "yesno",
                "Description" => " Отметьте здесь дабы удалить данные модуля при отключении оного.",
            ]
        ]
    ];

    return $configarray;
}

function ofd_output($vars)
{
    $PageController = new PageController($vars);
    $PageController->setDefaultAction('index');
    $PageController->setSuffixTemplate('admin');
    $PageController->setMenuTemplate('include\navbar.tpl');
    $PageController->setBreadcrumbTemplate('include\breadcrumb.tpl');
    $PageController->setMenu((new AdminAreaMenu())->navbar());
    $PageController->run();
}

function ofd_clientarea($vars)
{
    $id = decrypt(base64_decode($_GET['id']));
    $check = FemaCheckListsModel::find($id);

    if ($check == null || $check->status != 'CONFIRMED')
        return array(
            'pagetitle' => 'Транзакция с таким id не найдена',
            'breadcrumb' => array('index.php?m=ofd' => 'Чек'),
            'templatefile' => 'templates/clientarea.tpl',
            'requirelogin' => false,
            'forcessl' => false,
            'vars' => array(
                'check' => $check,
            ),
        );
    $qrText = sprintf(
        't=%s&s=%s&fn=%s&i=%s&fp=%s&n=%s',
        $check->created_at->format('Ymd') . 'T' . $check->created_at->format('Hi'),
        number_format((float)$check->total, 2, '.', ''),
        $check->fn,
        $check->fdn,
        $check->fpd,
        $check->type == 'Income' ? 1 : 2
    );
    $generator = new QRCode($qrText, ['w' => 300, 'h' => 300]);
    $image = $generator->render_image();
    $stream = fopen('php://memory', 'r+');
    imagepng($image, $stream);
    rewind($stream);
    $qr = base64_encode(stream_get_contents($stream));

    imagedestroy($image);
    $settings = SettingModel::where('key', 'like', 'ofr_ru_%')->get()->keyBy('key')->transform(function ($item, $key) {
        return $item->val;
    })->toArray();

    return array(
        'pagetitle' => 'Чек для транзакции #' . $check->order_id,
        'breadcrumb' => array('index.php?m=ofd' => 'Чек'),
        'templatefile' => 'templates/clientarea.tpl',
        'requirelogin' => false,
        'forcessl' => false,
        'vars' => array(
            'check' => $check,
            'qr' => $qr,
            'settings' => $settings,
        ),
    );
}

function ofd_activate()
{
    if (!empty($error = InstallController::createTableOFDSettings())) {
        return $error;
    }
    if (!empty($error = InstallController::createTableLog())) {
        return $error;
    }
    if (!empty($error = InstallController::createTableOFDFemaChecksList())) {
        return $error;
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно активирован',
    );
}

function ofd_deactivate()
{
    if (!empty($dropTable = Setting::Module(ModuleConfig::getModuleName())->where('setting', '=', 'DeleteTableWhenDisabled')->first())) {
        if ($dropTable->value === 'on') {
            if (!empty($error = UninstallController::dropTable('mod_addon_ofd_setting'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_ofd_fema_checks_list'))) {
                return $error;
            }
            if (!empty($error = UninstallController::dropTable('mod_addon_ofd_fema_checks_list'))) {
                return $error;
            }
        }
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно деактивирован'
    );
}