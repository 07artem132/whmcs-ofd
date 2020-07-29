<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 19.01.2020, 20:34
 *
 */

namespace WHMCS\Module\Addon\ofd\Models;


use WHMCS\Model\AbstractModel;

class FemaCheckListsModel extends AbstractModel
{
    protected $table = "mod_addon_ofd_fema_checks_list";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function transactions()
    {
          return $this->belongsTo("WHMCS\Billing\Payment\Transaction", "order_id",'transid');
    }

}