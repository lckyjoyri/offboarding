<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'responses';
    protected $fillable = ['clearance_request_id', 'question_id', 'response'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function clearance_request()
    {
        return $this->belongsTo(ClearanceRequest::class);
    }
}
