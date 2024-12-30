<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmploymentType;
use App\Models\SubRole;
use App\Models\User;
use App\Models\Clearance;
use App\Models\ClearanceOfficial;
use App\Models\Comment;
use App\Models\ClearanceApproval;


class ClearanceController extends Controller
{
    public function index(){

        //1:1 clearance type, exclude used type
        $usedEmploymentTypeIds = Clearance::pluck('employment_type')->toArray();
        $employment_types = EmploymentType::whereNotIn('id', $usedEmploymentTypeIds)->get()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->description,
            ];
        });
        
        //1:1 subrole, exclude inactive subrole
        $excludedSubRoles = User::where('status', 1)->pluck('sub_role')->toArray();

        $subRoles = SubRole::whereIn('id', $excludedSubRoles)->get()->map(function ($subrole) {
            return [
                'id' => $subrole->id,
                'name' => $subrole->description,
            ];
        });

        $forms = Clearance::with(['officials', 'employment_type_desc'])->get();

        return view('pages.hr.clearance.clearance_form.index', compact('employment_types', 'subRoles', 'forms'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'employment_type' => 'required|integer',
            'statement' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->with('error', implode('<br>', $validator->errors()->all()));
        }

        $clearingOfficial = $request->input('clearing_official', []);

        // handle duplicates in clearing_official
        $uniqueClearingOfficials = array_unique($clearingOfficial);
        if (count($clearingOfficial) !== count($uniqueClearingOfficials)) {

            return redirect()->back()->with('error', 'Duplicate clearing officials are not allowed.');
        }

        $clearance = Clearance::create([
            'employment_type' => $request->employment_type,
            'statement' => $request->statement
        ]);

        $seqno = $request->input('seqno', []);
        $title = $request->input('title', []);

        $totalRows = count($seqno);

        foreach ($seqno as $index => $seq) {
            ClearanceOfficial::create([
                'clearance_id' => $clearance->id,  
                'seqno' => $seqno[$index],
                'title' => $title[$index],
                'clearing_official' => $clearingOfficial[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Clearance Form Added.');

    }

    public function details($id){

        $form = Clearance::with(['officials', 'employment_type_desc'])->find($id);
        $employment_types = map_options(EmploymentType::class, 'id', 'description');

        //1:1 subrole, exclude active subrole
        $excludedSubRoles = User::where('status', 1)->pluck('sub_role')->toArray();

        $subRoles = SubRole::whereIn('id', $excludedSubRoles)->get()->map(function ($subrole) {
            return [
                'id' => $subrole->id,
                'name' => $subrole->description,
            ];
        });

        return view('pages.hr.clearance.clearance_form.edit', compact('form', 'subRoles', 'employment_types'));
    }

    public function update(Request $request, $id){
        
        try{

            $clearingOfficial = $request->input('clearing_official', []);

            // handle duplicates in clearing_official
            $uniqueClearingOfficials = array_unique($clearingOfficial);

            if (count($clearingOfficial) !== count($uniqueClearingOfficials)) {
                return redirect()->back()->with('error', 'Duplicate clearing officials are not allowed.');
            }

            $clearance = Clearance::find($id);

            $clearance->update([
                'employment_type' => $request->employment_type,
                'statement' => $request->statement
            ]);

            $deletedIds = $request->input('deleted_ids') ? explode(',', $request->input('deleted_ids')) : [];
            if (!empty($deletedIds)) {
                ClearanceOfficial::whereIn('id', $deletedIds)->delete();
            }

            $seqnos = $request->input('seqno') ?? [];
            $titles = $request->input('title') ?? [];
            $clearing_officials = $request->input('clearing_official') ?? [];
            $update_officials = $request->input('official_id') ?? [];
            if (!empty($update_officials)) {
                foreach ($update_officials as $index => $officialId) {
                    if (!empty($officialId)) {
                        $official = ClearanceOfficial::find($officialId);
            
                        if ($official) {
                            $official->seqno = $seqnos[$index] ?? null;
                            $official->title = $titles[$index] ?? null;
                            $official->clearing_official = $clearing_officials[$index] ?? null;
                            $official->save();
                        }
                    }
                }
            }

            foreach ($seqnos as $index => $seqno) {
                // dd($seqnos , $index,$seqno);
                if (empty($update_officials[$index])) { 
                    ClearanceOfficial::create([
                        'clearance_id' => $clearance->id,
                        'seqno' => $seqno,
                        'title' => $titles[$index] ?? null,
                        'clearing_official' => $clearing_officials[$index] ?? null,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Form has been successfully updated.');

        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Please check the details and try again.');
        }
    }



    public function comment(Request $request, $id){
       try{
        $comment = Comment::create([
            'clearance_requests_id' => $id,
            'comment' => $request->comment,
            'clearing_official_id' => $request->clearing_official_id
        ]);

        $update_approval_comment = ClearanceApproval::where('request_id', $id)
        ->where('isApproved', 0)
        ->take(1)
        ->update(['comment' => $request->comment]);
// dd($update_approval_comment);

        return redirect()->back()->with('success', 'Successfully Commented.');
       }catch(\Exception $e){
        return redirect()->back()->with('error', $e->getMessage());
       }

    }
}
