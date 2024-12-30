<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceRequest extends Model
{
    protected $table = 'clearance_requests';

    protected $fillable = ['user_id', 'clearance_id', 'purpose', 'attachment_file_path', 'remarks', 'status', 'generated_coe_path']; 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clearance_purpose()
    {
        return $this->belongsTo(ClearancePurpose::class, 'purpose');
    }

    public function statusDesc()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function comment_request(){
        return $this->hasMany(Comment::class ,'clearance_requests_id','id')->latest();
    }

    public function clearance()
    {
        return $this->belongsTo(Clearance::class, 'clearance_id', 'id');
    }

    public function clearance_approvals()
    {
        return $this->hasMany(ClearanceApproval::class, 'request_id', 'id');
    }
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
