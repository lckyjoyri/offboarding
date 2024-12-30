<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentType extends Model
{
    protected $table = 'employment_types';

    protected $fillable = ['description']; 

    public $timestamps = false;

}
