<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\C3LabelHistory;

class C3LabelHistoryDetails extends Model
{
    //
    protected $table = 'c3_label_history_details';
    protected $connection = 'mysql';

    public function user_created_by(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function user_received_by(){
        return $this->hasOne(User::class, 'id', 'received_by');
    }
    public function c3_label_history(){
        return $this->hasOne(C3LabelHistory::class, 'id', 'c3_label_history_id');
    }
}
