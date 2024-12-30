<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubRole extends Model
{
    protected $table = 'sub_roles';

    protected $fillable = ['description']; 

    public function users()
    {
        return $this->hasMany(User::class, 'sub_role', 'id');
    }
}
