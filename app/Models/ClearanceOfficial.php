<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceOfficial extends Model
{
    protected $table = 'clearance_official';
    
    protected $fillable = [
        'clearance_id',
        'seqno',
        'title',
        'clearing_official'
    ]; 

    public function clearance()
    {
        return $this->belongsTo(Clearance::class, 'clearance_id', 'id');
    }
}
