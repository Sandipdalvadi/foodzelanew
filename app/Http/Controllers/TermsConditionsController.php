<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSettings;
use Validator;
class TermsConditionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $termsConditions = SiteSettings::first();
        if(empty($termsConditions)){
            $termsConditions = new SiteSettings;
        }
        return view('admin.terms_conditions.form',['termsConditions'=>$termsConditions]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'terms_conditions_en' => 'required',
            'terms_conditions_ar' => 'required',
        ]);
        
        if($validator->fails())
        {
            return redirect()->route('admin.terms_conditions.index')->withErrors($validator)->withInput();
        } 
        $siteSettings = SiteSettings::first();
        if(empty($siteSettings)){
            $siteSettings = new SiteSettings;
        }
        $siteSettings->terms_conditions_en = $request->terms_conditions_en;
        $siteSettings->terms_conditions_ar = $request->terms_conditions_ar;
        $siteSettings->save();
        $message = "Terms Updated Successfully";
        return redirect()->route('admin.terms_conditions.index')->with('message', $message );
    }
}
