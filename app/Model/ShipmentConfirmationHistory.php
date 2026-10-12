<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ShipmentConfirmation;
use App\User;

class ShipmentConfirmationHistory extends Model
{
    //
    protected $table = 'shipment_confirmation_history';
    protected $connection = 'mysql';

    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_updated_by(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
