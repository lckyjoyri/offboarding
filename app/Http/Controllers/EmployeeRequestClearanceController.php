<?php

namespace App\Http\Controllers;

use App\Models\ClearanceApproval;
use App\Models\ClearancePurpose;
use App\Models\ClearanceRequest;
use App\Models\EmploymentType;
use App\Models\Clearance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeRequestClearanceController extends Controller
{
    public function index()
    {
        $purposes = map_options(ClearancePurpose::class, 'id', 'description');
        $employment_types = map_options(EmploymentType::class, 'id', 'description');
        $clearance_request = ClearanceRequest::where('user_id', auth()->id())->first();

        if(!is_null($clearance_request))
        {
            $clearance_approvals = ClearanceApproval::where('request_id', $clearance_request->id)
                ->get();
        } else {
            $clearance_approvals = null;
        }

        return view('pages.employee.clearance.index', compact('purposes', 'employment_types', 'clearance_request', 'clearance_approvals'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clearance_id' => 'required',
            'attachment' => 'required|file|mimes:pdf|max:10240',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            \Log::error('Clearance Request Validation Failed', [
                'errors' => $validator->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->with('error', ' An error occurred! PDF Only');
        }

        $data = $request->only(['clearance_id', 'purpose', 'remarks']);
        $data['status'] = 1; 

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            
            $filePath = $file->storeAs('attachments', $fileName, 'public');
            
            $data['attachment_file_path'] = $filePath;
        }

        $user = auth()->user();

        try {
            $user->clearance_requests()->create($data);
        } catch (\Exception $e) {
            \Log::error('Error saving clearance request', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            return redirect()->back()->with('error', 'There was an issue saving your request');
        }

        return redirect()->back()->with('success', 'Clearance Request created successfully.');
    }

}
