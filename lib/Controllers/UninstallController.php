<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 24.04.2019
 * Time: 20:24
 */

namespace WHMCS\Module\Addon\ofd\Controllers;

use \WHMCS\Database\Capsule;

class UninstallController {

	public static function dropTable( $tableName ) {
		try {
 			Capsule::schema()->dropIfExists( $tableName );
		} catch ( \Exception $e ) {
			return array(
				'status'      => 'error',
				'description' => sprintf( "Ошибка при удалении таблицы %s: %s", $tableName, $e->getMessage() )
			);
		}

		return [];
	}
}