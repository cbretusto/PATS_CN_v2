<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Kitting;
use App\Model\PartsPrepSubKitting;
use App\Model\MaterialKitting;

class SubKitting extends Model
{
    //
    public function kitting(){
    	return $this->hasOne(Kitting::class, 'id', 'pats_kitting_id');
    }
    public function partsprepsubkitting(){
    	return $this->hasOne(PartsPrepSubKitting::class, 'sub_kittings_id', 'id');
    }
    public function materialKitting(){
    	return $this->hasOne(MaterialKitting::class, 'issuance_no', 'issue_no');
    }
}
