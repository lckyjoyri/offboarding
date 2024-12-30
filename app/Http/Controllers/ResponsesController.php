<?php

namespace App\Http\Controllers;

use App\Models\ClearanceRequest;
use Illuminate\Http\Request;

class ResponsesController extends Controller
{
    public function index()
    {
        $clearance_requests = ClearanceRequest::where('status', 5)->with('responses')->get();
        return view('pages.hr.questionnaire.responses.index', compact('clearance_requests'));
    }
}
