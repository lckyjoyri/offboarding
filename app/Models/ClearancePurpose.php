<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearancePurpose extends Model
{
    protected $table = 'clearance_purpose';

    protected $fillable = ['description']; 

    // public function users()
    // {
    //     return $this->hasMany(User::class, 'sub_role', 'id');
    // }
}
