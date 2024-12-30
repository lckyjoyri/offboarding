<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';

    public $timestamps = true;

    protected $fillable = [
        'clearance_requests_id',
        'comment',
        'clearing_official_id'
    ]; 
}
