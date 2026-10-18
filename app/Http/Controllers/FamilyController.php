<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family;
use Validator;
use Redirect;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
        // dd($request->all());
        $this->rules = [
            'father'                => [ 'nullable', 'max:255'],
            'father_occupation'     => [ 'nullable', 'max:255'],
            'mother'                => [ 'nullable', 'max:255'],
            'mother_occupation'     => [ 'nullable', 'max:255'],
            'sibling'               => [ 'nullable', 'max:255'],
            'no_of_brothers'        => [ 'nullable', 'numeric', 'max:30'],
            'no_of_sisters'         => [ 'nullable', 'numeric', 'max:30'],
            'about_parents'         => [ 'nullable', 'max:65535'],
            'about_siblings'        => [ 'nullable', 'max:65535'],
            'about_relatives'       => [ 'nullable', 'max:65535'],
        ];
        $this->messages = [
            'father.max'                => translate('Max 255 characters'),
            'father_occupation.max'     => translate('Max 255 characters'),
            'mother.max'                => translate('Max 255 characters'),
            'mother_occupation.max'     => translate('Max 255 characters'),
            'sibling.max'               => translate('Max 255 characters'),
            'no_of_brothers.numeric'    => translate('No. of brothers should be number type'),
            'no_of_brothers.max'        => translate('Max 30 characters'),
            'no_of_sisters.numeric'     => translate('No. of sisters should be number type'),
            'no_of_sisters.max'         => translate('Max 30 characters'),
            'about_parents.max'         => translate('Max 65535 characters'),
            'about_siblings.max'        => translate('Max 65535 characters'),
            'about_relatives.max'       => translate('Max 65535 characters'),
        ];

        $rules = $this->rules;
        $messages = $this->messages;
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }
        
        $family = Family::where('user_id', $id)->first();
        if(empty($family)){
            $family           = new Family;
            $family->user_id  = $id;
        }

        $family->father                 = $request->father;

        // Dynamic Father occupation handling with sector & position
        if ($request->filled('father_sector') || $request->filled('father_position')) {
            $fSec = trim($request->father_sector ?? '');
            $fPos = trim($request->father_position ?? '');
            if ($fSec && $fPos) {
                $family->father_occupation = $fSec . ' - ' . $fPos;
            } elseif ($fSec) {
                $family->father_occupation = $fSec;
            } else {
                $family->father_occupation = $fPos;
            }
        } elseif ($request->has('father_occupation')) {
            $family->father_occupation  = $request->father_occupation;
        }

        $family->mother                 = $request->mother;

        // Dynamic Mother occupation handling with sector & position
        if ($request->filled('mother_sector') || $request->filled('mother_position')) {
            $mSec = trim($request->mother_sector ?? '');
            $mPos = trim($request->mother_position ?? '');
            if ($mSec && $mPos && strtolower($mSec) !== 'homemaker') {
                $family->mother_occupation = $mSec . ' - ' . $mPos;
            } elseif ($mSec) {
                $family->mother_occupation = $mSec;
            } else {
                $family->mother_occupation = $mPos;
            }
        } elseif ($request->has('mother_occupation')) {
            $family->mother_occupation  = $request->mother_occupation;
        }

        $family->sibling                = $request->sibling;
        $family->no_of_sisters          = $request->no_of_sisters ?? 0;
        $family->no_of_brothers         = $request->no_of_brothers ?? 0;

        // Structured parent info in about_parents JSON
        if ($request->has('father_sector') || $request->has('mother_sector')) {
            $parentData = [
                'father_sector'   => $request->father_sector ?? '',
                'father_position' => $request->father_position ?? '',
                'mother_sector'   => $request->mother_sector ?? '',
                'mother_position' => $request->mother_position ?? '',
            ];
            $family->about_parents = json_encode($parentData);
        } else {
            $family->about_parents      = $request->about_parents;
        }

        // Structured sibling info in about_siblings JSON
        if ($request->has('brother_status') || $request->has('sister_status')) {
            $siblingData = [
                'brother_status' => $request->brother_status ?? '',
                'brother_detail' => $request->brother_detail ?? '',
                'sister_status'  => $request->sister_status ?? '',
                'sister_detail'  => $request->sister_detail ?? '',
            ];
            $family->about_siblings = json_encode($siblingData);
        } else {
            $family->about_siblings     = $request->about_siblings;
        }

        $family->about_relatives        = $request->about_relatives;

        if($family->save()){
            flash(translate('Family info has been updated successfully'))->success();
            return back();
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }

     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
