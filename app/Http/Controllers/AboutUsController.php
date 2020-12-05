<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSettings;
use Validator;
class AboutUsController extends Controller
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
        return view('admin.about_us.form',['termsConditions'=>$termsConditions]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about_us_en' => 'required',
            'about_us_ar' => 'required',
        ]);
        
        if($validator->fails())
        {
            return redirect()->route('admin.about_us.index')->withErrors($validator)->withInput();
        } 
        $siteSettings = SiteSettings::first();
        if(empty($siteSettings)){
            $siteSettings = new SiteSettings;
        }
        $siteSettings->about_us_ar = $request->about_us_ar;
        $siteSettings->about_us_en = $request->about_us_en;
        $siteSettings->save();
        $message = "About Updated Successfully";
        return redirect()->route('admin.about_us.index')->with('message', $message );
    }
}
