<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\C3LabelHistory;

class C3Label extends Model
{
    //
    protected $table = 'c3_label';
    protected $connection = 'mysql';

    public function c3_label_history(){
        return $this->hasMany(C3LabelHistory::class, 'c3_label_id', 'id');
    }
    public function c3_label_history_hasone(){
        return $this->hasOne(C3LabelHistory::class, 'c3_label_id', 'id');
    }
}
