<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 20:34
 *
 */

namespace WHMCS\Module\Addon\ofd\Models;


use WHMCS\Model\AbstractModel;

class SettingModel extends AbstractModel {
	protected $table = "mod_addon_ofd_setting";
	protected $primaryKey = 'id';
	public $incrementing = true;
	protected $fillable = [
	    'key',
	    'val',
	];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}