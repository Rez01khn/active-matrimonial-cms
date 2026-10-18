<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Validator;
use Redirect;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->rules = [
            'profession_status' => [ 'nullable', 'max:255'],
            'job_type'          => [ 'nullable', 'max:255'],
            'occupation'        => [ 'nullable', 'max:255'],
            'designation'       => [ 'nullable', 'max:255'],
            'company'           => [ 'nullable', 'max:255'],
            'career_start'      => [ 'nullable'],
            'career_end'        => [ 'nullable'],
        ];
    }

    public function index(){}

    public function create(Request $request)
    {
        $member_id = $request->id;
        return view('frontend.member.profile.career.create', compact('member_id'));
    }

    private function fillCareerData($career, Request $request)
    {
        $status = $request->profession_status;
        $career->profession_status = $status;

        if ($status == 'Business') {
            $career->occupation  = $request->business_occupation ?? $request->occupation;
            $career->company     = $request->business_company ?? $request->company;
            $career->designation = $request->business_designation ?? $request->designation;
            $career->job_type    = $request->business_job_type ?? $request->job_type;
        } elseif ($status == 'Student') {
            $career->occupation  = 'Student';
            $career->company     = $request->student_institution ?? $request->company;
            $career->designation = $request->student_subject ?? $request->designation;
            $career->job_type    = null;
        } elseif ($status == 'Freelancer' || $status == 'Other') {
            $career->occupation  = $request->other_occupation ?? $request->occupation;
            $career->company     = $request->other_company ?? $request->company;
            $career->designation = $request->other_designation ?? $request->designation;
            $career->job_type    = $request->job_type;
        } elseif ($status == 'Not Employed' || $status == 'Homemaker') {
            $career->occupation  = $status;
            $career->company     = null;
            $career->designation = null;
            $career->job_type    = null;
        } else {
            // Job Holder or default
            $career->occupation  = $request->occupation;
            $career->company     = $request->company;
            $career->designation = $request->designation;
            $career->job_type    = $request->job_type;
        }

        $career->start = $request->career_start;
        $career->end   = $request->career_end;
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => false, 'message' => translate('Validation failed')]);
            }
            flash(translate('Something went wrong'))->error();
            return Redirect::back();
        }

        $career                    = new Career;
        $career->user_id           = $request->user_id ?? auth()->id();
        $this->fillCareerData($career, $request);
        $career->present           = 1;

        if($career->save()){
            flash(translate('Career Info has been added successfully'))->success();
            if ($request->ajax()) {
                return response()->json(['result' => true, 'message' => translate('Career info updated successfully')]);
            }
            return back();
        }
        else {
            if ($request->ajax()) {
                return response()->json(['result' => false, 'message' => translate('Failed to save career info')]);
            }
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function show($id){}

    public function edit(Request $request)
    {
        $career = Career::findOrFail($request->id);
        return view('frontend.member.profile.career.edit', compact('career'));
    }

    public function update(Request $request, $id)
    {
        $rules = $this->rules;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => false, 'message' => translate('Validation failed')]);
            }
            flash(translate('Something went wrong'))->error();
            return Redirect::back();
        }

        $career                    = Career::find($id);
        if (!$career) {
            $career                = new Career;
            $career->user_id       = $request->user_id ?? auth()->id();
        }
        $this->fillCareerData($career, $request);

        if($career->save()){
            flash(translate('Career Info has been updated successfully'))->success();
            if ($request->ajax()) {
                return response()->json(['result' => true, 'message' => translate('Career info updated successfully')]);
            }
            return back();
        }
        else {
            if ($request->ajax()) {
                return response()->json(['result' => false, 'message' => translate('Failed to save career info')]);
            }
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function update_career_present_status(Request $request)
    {
        $career = Career::findOrFail($request->id);
        $career->present = $request->status;
        if ($career->save()) {
            $msg = $career->present == 1 ? translate('Enabled') : translate('Disabled');
            flash(translate($msg))->success();
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        if(Career::destroy($id))
        {
            flash(translate('Career info has been deleted successfully'))->success();
            return back();
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
