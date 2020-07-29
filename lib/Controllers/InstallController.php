<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 20:23
 */

namespace WHMCS\Module\Addon\ofd\Controllers;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use WHMCS\Database\Capsule;

class InstallController
{

    public static function createTableOFDSettings()
    {
        try {
            $tbl_name = 'mod_addon_ofd_setting';
            if (!Capsule::schema()->hasTable($tbl_name)) {
                Capsule::schema()->create($tbl_name, function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->string('key');
                    $table->string('val');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', $tbl_name, $e->getMessage())
            );
        }
        return [];
    }
    public static function createTableLog()
    {
        try {
            $tbl_name = 'mod_addon_ofd_log';
            if (!Capsule::schema()->hasTable($tbl_name)) {
                Capsule::schema()->create($tbl_name, function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->boolean('status');
                    $table->string('module');
                    $table->text('message');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', $tbl_name, $e->getMessage())
            );
        }
        return [];
    }
    public static function createTableOFDFemaChecksList()
    {
        try {
            $tbl_name = 'mod_addon_ofd_fema_checks_list';
            if (!Capsule::schema()->hasTable($tbl_name)) {
                Capsule::schema()->create($tbl_name, function ($table) {
                    /** @var Blueprint $table */
                    $table->string('id', 36);
                    $table->string('type', 100);
                    $table->string('status', 100)->nullable();
                    $table->string('status_message')->nullable();
                    $table->text('order_id')->collate('utf8_general_ci');
                    $table->string('total');
                    $table->string('fn', 100)->nullable();
                    $table->string('rnm', 100)->nullable();
                    $table->string('fdn', 100)->nullable();
                    $table->string('fpd', 100)->nullable();
                    $table->timestamps();
                    $table->collation = 'utf8_general_ci';
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', $tbl_name, $e->getMessage())
            );
        }
        return [];
    }


}