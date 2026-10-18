<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnerExpectation;
use App\Models\User;
use Validator;
use Redirect;

class PartnerExpectationController extends Controller
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
         $this->rules = [
             'general'                      => [ 'nullable', 'max:3000'],
             'partner_height'               => [ 'nullable', 'max:50'],
             'partner_weight'               => [ 'nullable', 'max:50'],
             'partner_children_acceptable'  => [ 'nullable', 'max:50'],
             'pertner_education'            => [ 'nullable', 'max:255'],
             'partner_profession'           => [ 'nullable', 'max:255'],
             'smoking_acceptable'           => [ 'nullable', 'max:50'],
             'drinking_acceptable'          => [ 'nullable', 'max:50'],
             'partner_diet'                 => [ 'nullable', 'max:50'],
             'partner_body_type'            => [ 'nullable', 'max:50'],
             'partner_personal_value'       => [ 'nullable', 'max:50'],
             'partner_manglik'              => [ 'nullable', 'max:50'],
             'pertner_complexion'           => [ 'nullable', 'max:50'],
         ];
         $this->messages = [
             'general.max'                      => translate('Max 3000 characters'),
             'partner_height.max'               => translate('Max 50 characters'),
             'partner_weight.max'               => translate('Max 50 characters'),
             'partner_children_acceptable.max'  => translate('Max 50 characters'),
             'pertner_education.max'            => translate('Max 255 characters'),
             'partner_profession.max'           => translate('Max 255 characters'),
             'smoking_acceptable.max'           => translate('Max 50 characters'),
             'drinking_acceptable.max'          => translate('Max 50 characters'),
             'partner_diet.max'                 => translate('Max 50 characters'),
             'partner_body_type.max'            => translate('Max 50 characters'),
             'partner_personal_value.max'       => translate('Max 50 characters'),
             'partner_manglik.max'              => translate('Max 50 characters'),
             'pertner_complexion.max'           => translate('Max 50 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             if ($request->ajax()) {
                 return response()->json(['result' => false, 'message' => $validator->errors()->first()], 422);
             }
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $user                 = User::where('id',$id)->first();
         $partner_expectations = PartnerExpectation::where('user_id', $id)->first();
         if(empty($partner_expectations)){
             $partner_expectations           = new PartnerExpectation;
             $partner_expectations->user_id  = $id;
         }

         if ($request->has('general')) $partner_expectations->general = $request->general;
         if ($request->has('partner_height')) $partner_expectations->height = $request->partner_height;
         if ($request->has('partner_weight')) $partner_expectations->weight = $request->partner_weight;
         if ($request->has('partner_marital_status')) $partner_expectations->marital_status_id = $request->partner_marital_status;
         if ($request->has('partner_children_acceptable')) $partner_expectations->children_acceptable = $request->partner_children_acceptable;
         if ($request->has('residence_country_id')) $partner_expectations->residence_country_id = $request->residence_country_id;
         if ($request->has('partner_religion_id')) {
             $partner_expectations->religion_id = $request->partner_religion_id;
             if ($request->partner_religion_id != 2) {
                 $partner_expectations->manglik = null;
                 $partner_expectations->sub_caste_id = null;
             }
         }
         if ($request->has('partner_caste_id')) $partner_expectations->caste_id = $request->partner_caste_id;
         if ($request->has('partner_sub_caste_id') && ($request->partner_religion_id == 2 || $partner_expectations->religion_id == 2)) {
             $partner_expectations->sub_caste_id = $request->partner_sub_caste_id;
         }
         if ($request->has('pertner_education')) $partner_expectations->education = $request->pertner_education;
         if ($request->has('partner_profession')) $partner_expectations->profession = $request->partner_profession;
         if ($request->has('smoking_acceptable')) $partner_expectations->smoking_acceptable = $request->smoking_acceptable;
         if ($request->has('drinking_acceptable')) $partner_expectations->drinking_acceptable = $request->drinking_acceptable;
         if ($request->has('partner_diet')) $partner_expectations->diet = $request->partner_diet;
         if ($request->has('partner_body_type')) $partner_expectations->body_type = $request->partner_body_type;
         if ($request->has('partner_personal_value')) $partner_expectations->personal_value = $request->partner_personal_value;
         if ($request->has('partner_manglik') && ($request->partner_religion_id == 2 || $partner_expectations->religion_id == 2)) {
             $partner_expectations->manglik = $request->partner_manglik;
         }
         if ($request->has('language_id')) $partner_expectations->language_id = $request->language_id;
         if ($request->has('family_value_id')) $partner_expectations->family_value_id = $request->family_value_id;
         if ($request->has('partner_country_id')) $partner_expectations->preferred_country_id = $request->partner_country_id;
         if ($request->has('partner_state_id')) $partner_expectations->preferred_state_id = $request->partner_state_id;
         if ($request->has('pertner_complexion')) $partner_expectations->complexion = $request->pertner_complexion;

          if($partner_expectations->save()){
            if(optional($user->member)->auto_profile_match == 1){
              $ProfileMatchController = new ProfileMatchController;
              $ProfileMatchController->match_profiles($user->id);
            }
            if ($request->ajax()) {
                return response()->json(['result' => true, 'message' => translate('Partner Preferences updated successfully!')]);
            }
            flash(translate('Partner Expectations Info has been updated successfully'))->success();
            return back();
          }
          else {
            if ($request->ajax()) {
                return response()->json(['result' => false, 'message' => translate('Sorry! Something went wrong.')], 500);
            }
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
