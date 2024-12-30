<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    protected $table = 'clearance';

    public $timestamps = true;

    protected $fillable = [
        'employment_type',
        'statement',
    ]; 

    public function officials()
    {
        return $this->hasMany(ClearanceOfficial::class, 'clearance_id', 'id');
    }

    public function employment_type_desc()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type', 'id');
    }
}
