<?php

namespace App\Http\Controllers;

use App\Models\ClearanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class EmployeeCOEController extends Controller
{
    public function index()
    {
        $clearance_request = ClearanceRequest::where('user_id', auth()->id())->whereNotNull('generated_coe_path')->first();

        return view('pages.employee.certificate.index', compact('clearance_request'));
    }
    public function download($id)
    {
        $clearance_request = ClearanceRequest::where('user_id', auth()->id())
            ->whereNotNull('generated_coe_path')
            ->first();

        if ($clearance_request) {
            $filePath = 'coe/' . $clearance_request->generated_coe_path;

            \Log::info("Attempting to download file at path: public/$filePath");
            
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->download($filePath);       
            } else {
                \Log::error("File not found at path: public/$filePath");
            }
        } else {
            \Log::error("Clearance request not found for user ID: " . auth()->id() . " and request ID: " . $id);
        }

        return redirect()->back()->with('error', ' An error occurred. COE not found.');
    }
}
