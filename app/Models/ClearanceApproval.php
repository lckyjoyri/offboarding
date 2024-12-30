<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceApproval extends Model
{
    protected $table = 'clearance_approvals';

    public $timestamps = true;

    protected $fillable = [
        'request_id',
        'seqno',
        'clearing_official_id',
        'comment',
        'isApproved'
    ]; 

    public function sub_role()
    {
        return $this->belongsTo(SubRole::class, 'clearing_official_id', 'id');
    }
}
