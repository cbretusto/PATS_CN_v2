<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\DlabelHistory;
use App\User;

class ShipmentConfirmation extends Model
{
    //
    protected $table = 'shipment_confirmation';
    protected $connection = 'mysql';

    public function dlabel_history()
    {
        return $this->hasOne(DlabelHistory::class, 'shipment_confirmation_id', 'id')->where('deleted_at', null);
    }
    public function user_created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
